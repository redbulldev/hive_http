import React from 'react';
import Template from './Template';
import sourceApi from '../../../api/sourceApi';
import { useTranslation } from 'react-i18next';

export default function Source() {
  const { t } = useTranslation();

  const api = {
    getAll: sourceApi.getAll,
    add: sourceApi.post,
    remove: sourceApi.delete,
    removeMany: sourceApi.deleteMany,
    edit: sourceApi.put,
  };

  const i18n = {
    // Header
    mainTitle: t('source.title'),
    addBtn: t('source.titleCreate'),
    // Table
    titleColumn: t('source.titleColumn'),
    // Form
    formAddTitle: t('source.createTitle'),
    formEditTitle: t('source.editTitle'),
    titlePlaceholder: t('source.titlePlaceholder'),
    // Add
    addSuccessfully: t('source.createSuccessfully'),
    // Edit
    editSuccessfully: t('source.editSuccessfully'),
    // other
    itemExists: t('source.itemExists'),
  };

  const config = {
    // Excel
    excelSheet: 'sourceList',
    excelName: 'sourceList.xlsx',
    // Permission
    permissionStateName: 'source',
  };
  return <Template api={api} i18n={i18n} config={config} />;
}
