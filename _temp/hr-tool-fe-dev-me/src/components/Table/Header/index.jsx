import { PlusCircleFilled } from '@ant-design/icons';
import { Button } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import {
  setIsOpenedDrawer,
  setModeTextDrawer,
} from '../../Drawer/slice/drawer';
import './style.scss';

export default function Header({
  title,
  addPermission,
  addNavigate,
  addTitle,
  buttons = ['add'],
}) {
  const { t } = useTranslation();
  const navigate = useNavigate();

  //Begin: defined Buttons
  const definedButtons = {
    add: {
      permission: addPermission,
      component: () => {
        return (
          <Button
            onClick={onAdd}
            type="primary"
            icon={<PlusCircleFilled />}
            style={{ textTransform: 'uppercase' }}
          >
            {addTitle || `${t('common.create')} ${title}`}
          </Button>
        );
      },
    },
  };

  const newButtons = [];
  buttons.forEach(btn => {
    if (typeof btn === 'string' && definedButtons[btn]) {
      newButtons.push(definedButtons[btn]);
    } else newButtons.push(btn);
  });
  //End: defined Buttons

  const titleLower = title.toLowerCase();
  const dispatch = useDispatch();
  const onAdd = () => {
    if (addNavigate) {
      navigate(addNavigate);
    } else {
      dispatch(setIsOpenedDrawer(true));
      dispatch(
        setModeTextDrawer({
          btn: `${t('common.create')}`,
          title: `${t('common.create')} ${titleLower}`,
        }),
      );
    }
  };
  return (
    <div className="header-list">
      <h3>{title}</h3>
      <div className="buttons">
        {newButtons?.map((btn, i) => {
          const Component = btn.component;
          return btn.permission && <Component key={i} />;
        })}
      </div>
    </div>
  );
}
