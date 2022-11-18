import { EditOutlined, RedditOutlined } from '@ant-design/icons';
import React from 'react';
import moment from 'moment';
import { enFullDaysLabels, priorityColors } from '../constants';
import { Button } from 'antd';
import { useDispatch } from 'react-redux';
import {
  setInitialDrawer,
  setIsOpenedDrawer,
  setModeTextDrawer,
} from '../../../components/Drawer/slice/drawer';

export default function Detail({ selectedDate, data }) {
  const items = data?.[selectedDate];

  const dispatch = useDispatch();

  const getHeader = () => {
    const day =
      enFullDaysLabels[moment(selectedDate, 'DD/MM/YYYY').isoWeekday() - 1];
    return `${day}, ${selectedDate}`;
  };

  const handleEdit = item => {
    dispatch(setInitialDrawer(item));
    dispatch(setIsOpenedDrawer(true));
    dispatch(
      setModeTextDrawer({
        btn: 'Sửa',
        title: 'Sửa nhắc nhở',
      }),
    );
  };

  const getItems = () => {
    return items.map(item => {
      const { id } = item;
      const { priority, fullName, description, level, position, cvId } =
        item?.hrtoolData;
      return (
        <div
          className="reminder-detail-item"
          onDoubleClick={() => window.open('/cv/' + cvId, '_blank')}
          key={id}
        >
          <div
            className="reminder-detail-bar"
            style={{
              backgroundColor: priority ? priorityColors?.[priority] : '',
            }}
          ></div>
          <div className="reminder-detail-item-body">
            <div className="reminder-detail-position">
              <div>{position}</div>
              <div>{level}</div>
            </div>
            <div className="reminder-detail-description">
              <div className="reminder-detail-fullName">{fullName}</div>
              <div>{description}</div>
            </div>
            <Button
              icon={<EditOutlined />}
              type="text"
              style={{ alignSelf: 'flex-end' }}
              onClick={() => handleEdit(item)}
            ></Button>
          </div>
        </div>
      );
    });
  };

  return (
    <div className="reminder-detail">
      <h2>{getHeader()}</h2>
      {!items && (
        <div className="reminder-detail-no-data">
          <RedditOutlined style={{ fontSize: 25 }} />
          <span>No Data</span>
        </div>
      )}
      {items && <div>{getItems()}</div>}
    </div>
  );
}
