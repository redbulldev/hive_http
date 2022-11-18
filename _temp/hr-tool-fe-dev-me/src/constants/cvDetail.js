import genderless from '../assets/images/cvManagement/genderless.svg';
import mars from '../assets/images/cvManagement/mars.svg';
import venus from '../assets/images/cvManagement/venus.svg';
import i18n from '../translation/i18n';

export const stepCantChangeBefore = 9;

export const GENDER = [
  { text: 'updateCv.female', icon: venus },
  { text: 'updateCv.male', icon: mars },
  { text: 'updateCv.otherGender', icon: genderless },
];
export const DELAY_OPTIONS = [
  {
    value: 30,
    title: i18n.t('updateCv.delay_parthour'),
  },
  {
    value: 60,
    title: i18n.t('updateCv.delay_anhour'),
  },
  {
    value: 120,
    title: i18n.t('updateCv.delay_twohour'),
  },
  {
    value: 720,
    title: i18n.t('updateCv.delay_partday'),
  },
  {
    value: 1440,
    title: i18n.t('updateCv.delay_aday'),
  },
  {
    value: 4320,
    title: i18n.t('updateCv.delay_threedays'),
  },
  {
    value: 7200,
    title: i18n.t('updateCv.delay_fivedays'),
  },
  {
    value: 10080,
    title: i18n.t('updateCv.delay_aweek'),
  },
];
