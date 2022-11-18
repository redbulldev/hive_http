import i18n from '../../../translation/i18n';

export const requiredFields = [
  'fullname',
  'position_id',
  'level_id',
  'request_id',
  'source_id',
];
export const genderRadio = [
  {
    value: 0,
    label: i18n.t('updateCv.female'),
  },
  {
    value: 1,
    label: i18n.t('updateCv.male'),
  },
  {
    value: 2,
    label: i18n.t('updateCv.otherGender'),
  },
];
export const BIRTHDAY_FORMAT_FROM_BACKEND = 'YYYY-MM-DD';
