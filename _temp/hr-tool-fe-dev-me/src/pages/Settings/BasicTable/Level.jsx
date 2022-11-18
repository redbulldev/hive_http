import React from 'react';
import Template from './Template';
import levelApi from '../../../api/levelApi';
import { useTranslation } from 'react-i18next';

export default function Level() {
  const { t } = useTranslation();

  const api = {
    getAll: levelApi.getLevel,
    add: levelApi.postLevel,
    remove: levelApi.deleteLevel,
    removeMany: levelApi.deleteMany,
    edit: levelApi.updateLevel,
  };

  const i18n = {
    // Header
    mainTitle: t('level.title'),
    addBtn: t('level.create-level'),
    // Table
    titleColumn: t('level.level'),
    // Form
    formAddTitle: t('level.create-level'),
    formEditTitle: t('level.levelTitleEdit'),
    titlePlaceholder: t('level.titlePlaceholder'),
    // Add
    addSuccessfully: t('level.createSuccessfully'),
    // Edit
    editSuccessfully: t('level.editSuccessfully'),
    // other
    itemExists: t('level.itemExists'),
  };

  const config = {
    // Excel
    excelSheet: 'levelList',
    excelName: 'levelList.xlsx',
    // Permission
    permissionStateName: 'level',
  };
  return <Template api={api} i18n={i18n} config={config} />;
}
