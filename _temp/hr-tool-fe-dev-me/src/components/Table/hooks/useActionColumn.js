import {
  DeleteOutlined,
  EditOutlined,
  ExclamationCircleOutlined,
} from '@ant-design/icons';
import { Button, message, Modal } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import {
  setInitialDrawer,
  setIsOpenedDrawer,
  setModeDrawer,
  setModeTextDrawer,
} from '../../Drawer/slice/drawer';

export default function useActionColumn({
  editPermission,
  deletePermission,
  handleEdit,
  editNavigate,
  handleDelete,
  deleteApi,
  deleteContentKey,
  fetchData,
  selects,
  idKey,
  setSelects,
  title,
  actionButtons = ['edit'],
}) {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const handleDeleteDefault = async id => {
    try {
      await deleteApi(id);
      message.success(t('typework.deleteSuccessfully'));
      if (selects && selects.includes(id)) {
        setSelects(selects.filter(select => select !== id));
      }
      fetchData();
    } catch (res) {
      console.log('res :', res);
      if (!res.status) message.error(t('typework.networkError'));
      if (res.data.message.includes('delete'))
        message.error(t('basicTable.dependData'));
    }
  };

  const handleEditDefault = record => {
    const titleLower = title.toLowerCase();
    dispatch(setInitialDrawer(record));
    dispatch(setIsOpenedDrawer(true));
    dispatch(
      setModeTextDrawer({
        btn: 'Sửa',
        title: `Sửa ${titleLower}`,
      }),
    );
  };

  const handleNavigate = record => {
    navigate(editNavigate.replace(':id', record.id));
  };

  const editFunc = editNavigate
    ? handleNavigate
    : handleEdit || handleEditDefault;
  //Begin: defined Buttons
  const definedButtons = {
    edit: {
      permission: editPermission,
      component: record => {
        return (
          <Button icon={<EditOutlined />} onClick={() => editFunc(record)}>
            {t('typework.editAction')}
          </Button>
        );
      },
    },
    delete: {
      permission: deletePermission,
      component: record => {
        return (
          <Button
            icon={<DeleteOutlined />}
            onClick={() => confirm(undefined, record)}
          >
            {t('typework.deleteAction')}
          </Button>
        );
      },
    },
  };
  // End: defined Buttons

  const newButtons = [];
  actionButtons.forEach(btn => {
    if (typeof btn === 'string' && definedButtons[btn]) {
      newButtons.push(definedButtons[btn]);
    } else newButtons.push(btn);
  });

  const actionContent = record => {
    return (
      <div className="action-content">
        {newButtons?.map((btn, i) => {
          return (
            (btn.permission === undefined ? true : btn.permission) && (
              <React.Fragment key={i}>{btn.component(record)}</React.Fragment>
            )
          );
        })}
      </div>
    );
  };

  function confirm(_, record) {
    const deleteFunc = handleDelete || handleDeleteDefault;
    Modal.confirm({
      title: (
        <div className="language__multipleDelete--title">
          <span>{t('language.areYouSureRemove')}</span>
          <span style={{ color: 'red' }}>
            {t('language.totalItems', { quantity: '' })}
          </span>
          <span>{t('language.areThis?')}</span>
        </div>
      ),
      content: record[deleteContentKey] || record.title,
      icon: <ExclamationCircleOutlined />,
      okText: t('typework.okConfirm'),
      cancelText: t('typework.cancelConfirm'),
      onOk: () => deleteFunc(idKey ? record[idKey] : record.id),
      getContainer: document.querySelector('.table-fullscreen'),
    });
  }

  const ActionContent = ({ record }) => {
    const condition = newButtons.some(item =>
      [undefined, true].includes(item.permission),
    );

    return condition ? actionContent(record) : null;
  };

  return { ActionContent };
}
