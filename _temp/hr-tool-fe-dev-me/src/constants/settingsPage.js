import i18n from '../translation/i18n';

export const MIN_DAY_REQUEST_RULE = [
  {
    required: true,
    message: i18n.t('settings.daysIsRequired'),
  },
  () => ({
    validator(_, value) {
      if (Number(value) < 0) {
        return Promise.reject(i18n.t('settings.quantityMustGreaterZero'));
      }
      return Promise.resolve();
    },
  }),
];
export const MAX_DAY_REQUEST_RULE = [
  {
    required: true,
    message: i18n.t('settings.daysIsRequired'),
  },
  ({ getFieldValue }) => ({
    validator(_, value) {
      if (Number(value) < 0) {
        return Promise.reject(i18n.t('settings.quantityMustGreaterZero'));
      }
      if (Number(value) <= Number(getFieldValue('daybefore'))) {
        return Promise.reject(i18n.t('settings.dayafterMustGreaterDayBefore'));
      }
      return Promise.resolve();
    },
  }),
];
export const MAX_LENGTH_INPUT_SETTINGS = 4;
