import React from 'react';
import Template from './Template';
import { departmentApi } from '../../../api/departmentAPI';
import { useTranslation } from 'react-i18next';

export default function Level() {
  const { t } = useTranslation();

  const api = {
    getAll: departmentApi.getAll,
    add: departmentApi.postDepartment,
    remove: departmentApi.deleteDepartmentById,
    removeMany: departmentApi.deleteDepartment,
    edit: departmentApi.putDepartment2,
  };

  const i18n = {
    // Header
    mainTitle: t('departmentSetting.department'),
    addBtn: t('departmentSetting.createDepartment'),
    // Table
    titleColumn: t('departmentSetting.department'),
    // Form
    formAddTitle: t('departmentSetting.createDepartment'),
    formEditTitle: t('departmentSetting.editDepartment'),
    titlePlaceholder: t('departmentSetting.titlePlaceholder'),
    // Add
    addSuccessfully: t('departmentSetting.createSuccessfully'),
    // Edit
    editSuccessfully: t('departmentSetting.editSuccessfully'),
    // other
    itemExists: t('departmentSetting.departmentExists'),
  };

  const config = {
    // Excel
    excelSheet: 'departmentSettingList',
    excelName: 'departmentSettingList.xlsx',
    // Permission
    permissionStateName: 'department',
  };
  return <Template api={api} i18n={i18n} config={config} />;
}
