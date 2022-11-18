import React from 'react';
import { Button, DatePicker } from 'antd';

import monthLocale from 'antd/es/date-picker/locale/en_US';
import { LeftOutlined, RightOutlined } from '@ant-design/icons';
import moment from 'moment';
import { MsLoginAndLogoutBtn } from '../../../components/MsCalendar';

export default function Header({ selectedDate, setSelectedDate }) {
  const selectedDateMoment = moment(selectedDate, 'DD/MM/YYYY');
  const setSelectedDateFromMoment = date =>
    setSelectedDate(date.format('DD/MM/YYYY'));

  const onChangeMonth = e => {
    setSelectedDateFromMoment(e);
    if (moment().format('DD/MM/YYYY') === e.format('DD/MM/YYYY')) {
    }
  };

  const moveMonth = type => {
    const factor = type === 'plus' ? 1 : -1;
    const newValue = selectedDateMoment.clone().add(factor, 'months');
    setSelectedDateFromMoment(newValue);
    if (moment().format('DD/MM/YYYY') === newValue.format('DD/MM/YYYY')) {
    }
  };

  const toToday = () => {
    {
      const date = moment();
      setSelectedDateFromMoment(date);
    }
  };

  return (
    <div className="reminder-header">
      <h1>Lịch nhắc nhở</h1>

      <Button className="today" onClick={toToday}>
        Today
      </Button>

      <LeftOutlined className="icon" onClick={() => moveMonth('subtract')} />

      <DatePicker
        onChange={onChangeMonth}
        allowClear={false}
        picker="month"
        className="month-picker"
        suffixIcon={null}
        format={'MMM YYYY'}
        locale={monthLocale}
        value={selectedDateMoment}
      />

      <RightOutlined className="icon" onClick={() => moveMonth('plus')} />

      <MsLoginAndLogoutBtn />
    </div>
  );
}
