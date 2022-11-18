import i18n from '../translation/i18n';

export const CONDITION_NAME_FROM_BACKEND = 'To Interview';
export const DATE_FORMAT_BACKEND = 'YYYY/MM/DD';
export const MAX_SALARY_LENGTH = 11;
export const requiredFields = [];
export const statusLabel = [
  {
    value: 2,
    label: i18n.t(`hrinterview.successStatus`),
  },
  {
    value: 0,
    label: i18n.t(`hrinterview.failStatus`),
  },
  {
    value: 1,
    label: i18n.t(`hrinterview.pendingStatus`),
  },
  {
    value: 3,
    label: i18n.t(`hrinterview.absentStatus`),
  },
];
