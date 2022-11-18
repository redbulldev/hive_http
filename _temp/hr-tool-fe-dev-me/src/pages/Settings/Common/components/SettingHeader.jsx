import { Button, PageHeader } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { hasPermission } from '../../../../utils/hasPermission';

function SettingHeader() {
  const { t } = useTranslation();
  const { userInfor } = useSelector(state => state.auth);

  return (
    <PageHeader
      ghost={false}
      title={t('settings.setting')}
      className="settings__header"
      extra={[
        hasPermission(userInfor, 'general', 'edit') && (
          <Button key="1" type="primary" htmlType="submit" form="settingForm">
            <span className="language__addBtn">
              {t('settings.saveSettingTitleBtn')}
            </span>
          </Button>
        ),
      ]}
    />
  );
}

export default SettingHeader;
