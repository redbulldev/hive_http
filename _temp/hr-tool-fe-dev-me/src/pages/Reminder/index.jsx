import React, { useEffect, useState } from 'react';
import { Calendar, Col, Row } from 'antd';
import './styles.scss';
import { isEqual } from 'lodash';
import Header from './components/Header';
import {
  enDaysLabels,
  getFirstDateAndLastDateOnThePanel,
  MAX_EVENTS_ON_CELL,
} from './constants';
import moment from 'moment';
import useHandleData from './hooks/useHandleData';
import Detail from './components/Detail';
import CalendarEventForm from '../../components/MsCalendar/CalendarEventForm';

export default function Reminder() {
  const [dates, setDates] = useState(null);
  const [selectedDate, setSelectedDate] = useState(
    moment().format('DD/MM/YYYY'),
  );
  const { data } = useHandleData({ params: dates });

  const handleOnPanelChange = (date, check = true) => {
    const { firstDate, lastDate } = getFirstDateAndLastDateOnThePanel(date);
    const newState = {
      startdatetime: firstDate.toISOString(),
      enddatetime: lastDate.toISOString(),
    };
    if (!isEqual(dates, newState) || !check) {
      setDates(newState);
    }
  };

  useEffect(() => {
    handleOnPanelChange(moment(selectedDate, 'DD/MM/YYYY'));
  }, [selectedDate]);

  const fetchData = () => {
    handleOnPanelChange(moment(selectedDate, 'DD/MM/YYYY'), false);
  };

  const getMaxCharacterOnCell = () => {
    const cell = document.querySelector(
      '.ant-picker-cell-inner.ant-picker-calendar-date',
    );
    const width = cell.offsetWidth;
    return Math.ceil((width - 75) / 6);
  };

  useEffect(() => {
    // Edit day labels to english
    const reminderDays = document.querySelector('.reminder thead tr');
    const htmlArr = enDaysLabels.map(day => `<th>${day}</th>`);
    reminderDays.innerHTML = htmlArr.join('');
    // default date to call api
    handleOnPanelChange(moment());
  }, []);

  const renderDateCell = date => {
    const arr = data?.[date.format('DD/MM/YYYY')];
    if (!arr) return null;
    const count = {};
    arr.forEach(event => {
      if (event.hrtoolData) {
        const position = event.hrtoolData?.position;
        if (!count[position]) {
          count[position] = 1;
        } else {
          count[position]++;
        }
      }
    });
    const elements = [];
    let overMaxEvent = false;
    for (const key in count) {
      if (elements.length < MAX_EVENTS_ON_CELL) {
        elements.push(
          <li key={key}>
            <span className="reminder-cell-list--icon"></span>
            <span>{`${
              key.length > getMaxCharacterOnCell()
                ? key.slice(0, getMaxCharacterOnCell()) + '...'
                : key
            } (${count[key]})`}</span>
          </li>,
        );
      } else {
        overMaxEvent = true;
      }
    }

    if (overMaxEvent) {
      elements.push(
        <span key={100000} className="reminder-cell-list-more">
          ...
        </span>,
      );
    }

    return <ul className="reminder-cell-list">{elements}</ul>;
  };

  const handleSelectDate = date => {
    setSelectedDate(date.format('DD/MM/YYYY'));
  };

  return (
    <div className="reminder">
      <CalendarEventForm fetchData={fetchData} />
      <Header selectedDate={selectedDate} setSelectedDate={setSelectedDate} />
      <Row>
        <Col span={17}>
          <div className="reminder-calendar">
            <Calendar
              headerRender={props => {
                return null;
              }}
              onPanelChange={handleOnPanelChange}
              dateCellRender={renderDateCell}
              onSelect={handleSelectDate}
              value={moment(selectedDate, 'DD/MM/YYYY')}
            />
          </div>
        </Col>
        <Col span={7}>
          <Detail selectedDate={selectedDate} data={data} />
        </Col>
      </Row>
    </div>
  );
}
