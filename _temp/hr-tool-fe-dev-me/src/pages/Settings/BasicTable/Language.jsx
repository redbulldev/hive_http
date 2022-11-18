import React from 'react';
import Template from './Template';
import languageApi from '../../../api/languageApi';
import { useTranslation } from 'react-i18next';

export default function Level() {
  const { t } = useTranslation();

  const api = {
    getAll: languageApi.getAll,
    add: languageApi.create,
    remove: languageApi.delete,
    removeMany: languageApi.multiDelete,
    edit: languageApi.edit,
  };

  const i18n = {
    // Header
    mainTitle: t('language.language'),
    addBtn: t('language.createLang'),
    // Table
    titleColumn: t('language.language'),
    // Form
    formAddTitle: t('language.createLang2'),
    formEditTitle: t('language.titleEditLang'),
    titlePlaceholder: t('language.placeholderTitle'),
    // Add
    addSuccessfully: t('language.fetchCreateLangSuccess'),
    // Edit
    editSuccessfully: t('language.fetchEditLanguageSuccess'),
    // other
    itemExists: t('language.titleLangExits'),
  };

  const config = {
    // Excel
    excelSheet: 'languageList',
    excelName: 'languageList.xlsx',
    // Permission
    permissionStateName: 'language',
  };
  return <Template api={api} i18n={i18n} config={config} />;
}
