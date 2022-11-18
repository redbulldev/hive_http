import { Form, message } from 'antd';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import commonSettingApi from '../../../api/commonSettingApi';
import NoPermission from '../../../components/NoPermission';
import { hasPermission } from '../../../utils/hasPermission';
import SettingContent from './components/SettingContent';
import SettingHeader from './components/SettingHeader';
import { Header } from '../../../components/Table';
import { SaveBlueButton } from '../../../components/Buttons';

function Common(props) {
  message.config({
    getContainer() {
      return document.body;
    },
  });

  const [form] = Form.useForm();
  const { t } = useTranslation();
  const { userInfor } = useSelector(state => state.auth);
  const [listDeadlineDay, setListDeadlineDay] = useState({});
  const [reloadComponent, setReloadComponent] = useState(false);

  const fetchListDeadlineDay = async () => {
    try {
      const response = await commonSettingApi.getById(1);
      setListDeadlineDay(response.data.data);
    } catch (error) {
      if (!error.status) {
        return message.error(t('request.lostConnect'));
      }
      return message.error(t('settings.failToFetchDeadlineDay'));
    }
  };

  useEffect(() => {
    fetchListDeadlineDay();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [reloadComponent]);

  const handleSubmitForm = async () => {
    const values = form.getFieldsValue();
    try {
      const data = Object.keys(values).reduce((acc, cur) => {
        acc[cur] = parseInt(values[cur]);
        return acc;
      }, {});
      await commonSettingApi.edit(1, data);
      setReloadComponent(!reloadComponent);
      message.success(t('settings.editDeadlineDaySuccess'));
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('settings.failToEditDeadlineDay'));
      }
    }
  };

  return hasPermission(userInfor, 'general', 'view') ? (
    <div className="settings">
      <Header
        title={t('settings.setting')}
        buttons={[
          {
            permission: hasPermission(userInfor, 'general', 'edit'),
            component: () => (
              <SaveBlueButton onClick={handleSubmitForm}>
                {t('settings.saveSettingTitleBtn')}
              </SaveBlueButton>
            ),
          },
        ]}
      />
      <SettingContent
        form={form}
        // onFinish={handleSubmitForm}
        listDeadlineDay={listDeadlineDay}
      />
    </div>
  ) : (
    <NoPermission />
  );
}

export default Common;
