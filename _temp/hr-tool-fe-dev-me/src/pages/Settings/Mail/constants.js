import { CV_STATUS_DEFAULT_EXTENDED, CV_STEP } from '../../../constants';
import i18n from '../../../translation/i18n';

export const cols = [
  { type: 'id', sorter: false },
  { type: 'title', width: 250 },
  {
    title: i18n.t('cv.step'),
    type: 'allWithoutSort',
    dataIndex: 'cv_step',
    render(value) {
      return i18n.t(`cv.${CV_STEP[value].title}`);
    },
    width: 200,
  },
  {
    type: 'all',
    dataIndex: 'cv_status',
    title: i18n.t('cv.status'),
    width: 150,
    render(value) {
      return i18n.t(`cv.${CV_STATUS_DEFAULT_EXTENDED[value].title}`);
    },
  },
  {
    type: 'status',
    dataIndex: 'isauto',
    title: i18n.t('emailTemplate.isAuto'),
  },
  { type: 'status' },
  {
    type: 'action',
  },
];

export const requiredFields = [
  'title',
  'cv_step',
  'cv_status',
  'delay',
  'content',
];

export const isAutoRadio = [
  {
    label: i18n.t('common.yes'),
    value: 1,
  },
  {
    label: i18n.t('common.no'),
    value: 0,
  },
];

export const status = [
  {
    label: i18n.t('common.locked'),
    value: 0,
  },
  {
    label: i18n.t('emailTemplate.active'),
    value: 1,
  },
];
