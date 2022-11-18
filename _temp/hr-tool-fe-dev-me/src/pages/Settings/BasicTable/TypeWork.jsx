import React from 'react';
import Template from './Template';
import { useTranslation } from 'react-i18next';
import typeWorkApi from '../../../api/typeworkApi';

export default function TypeWork() {
  const { t } = useTranslation();

  const api = {
    getAll: typeWorkApi.getAll,
    add: typeWorkApi.create,
    remove: typeWorkApi.delete,
    removeMany: typeWorkApi.deleteMany,
    edit: typeWorkApi.edit,
  };

  const i18n = {
    // Header
    mainTitle: t('typework.mainTitle'),
    addBtn: t('typework.createNew'),
    // Table
    titleColumn: t('typework.titleColumn'),
    // Form
    formAddTitle: t('typework.createFormTitle'),
    formEditTitle: t('typework.editFormTitle'),
    titlePlaceholder: t('typework.titlePlaceholder'),
    // Add
    addSuccessfully: t('typework.createSuccessfully'),
    // Edit
    editSuccessfully: t('typework.editSuccessfully'),
    // other
    itemExists: t('typework.itemExists'),
  };

  const config = {
    // Excel
    excelSheet: 'typeWorkList',
    excelName: 'typeWorkList.xlsx',
    // Permission
    permissionStateName: 'type_work',
  };
  return <Template api={api} i18n={i18n} config={config} />;
}
