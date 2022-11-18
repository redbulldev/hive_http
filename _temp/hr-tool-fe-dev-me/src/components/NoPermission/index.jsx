import React from 'react';
import { useTranslation } from 'react-i18next';

function NoPermission() {
  const { t } = useTranslation();
  return <h1 className="no-permission">{t('noPermission.message')}</h1>;
}

export default NoPermission;
