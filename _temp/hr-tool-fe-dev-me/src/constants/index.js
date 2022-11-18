import { CheckCircleFilled, CloseCircleFilled } from '@ant-design/icons';
import enUS from 'antd/lib/locale/en_US';
import viVN from 'antd/lib/locale/vi_VN';
import i18n from '../translation/i18n';

export const pageSizeOptions = ['10', '20', '50'];
export const PAGE_SIZE_OPTIONS = ['10', '20', '50'];
export const DEFAULT_FILTER = { page: 1, limit: 10, keyword: '' };
export const GET_FULL_LIST_PARAMS = {
  limit: 0,
  status: 1,
};
export const CONFIG_PAGINATION = {
  showQuickJumper: false,
  showSizeChanger: true,
  defaultCurrent: 1,
  pageSize: DEFAULT_FILTER.limit,
  total: 0,
  pageSizeOptions: PAGE_SIZE_OPTIONS,
};

export const DATE_FORMAT = 'DD/MM/YYYY';
export const DEFAULT_DATE_PICK_RANGE = '17-11-1999';
export const BACKEND_ONBOARD_CV_DETAIL_FORMAT = 'YYYY MM DD';
export const BACKEND_DATE_PLAN_FORMAT = 'YYYY-MM-DD';
export const DATE_BACKEND = 'YYYY-MM-DD';
export const DATE_TIME_FORMAT = 'HH:mm - DD/MM/YYYY';
export const DATE_FORMAT_ONBOARD = 'DD/MM/YYYY';

export const LINK_MAX_LENGTH = 2100;
export const RECENTLY_SELECT_LENGTH_MAX = 5;

export const FULL_DATA = { month: 2, year: 2022 };

export const LIST_STATUS = [
  {
    id: 1,
    value: 0,
    title: 'locking',
    icon: <CloseCircleFilled style={{ fontSize: '16px', color: '#F94144' }} />,
  },
  {
    id: 2,
    value: 1,
    title: 'active',
    icon: <CheckCircleFilled style={{ fontSize: '16px', color: '#78BE20' }} />,
  },
];
export const DEFAULT_STATUS = 1;

export const CV_STATUS_DEFAULT = [
  { title: 'Failed', color: '#c20000', id: 0 },
  { title: 'Pending', color: '#a5b802', id: 1 },
  { title: 'Pass', color: '#0ea800', id: 2 },
];

export const CV_STATUS_DEFAULT_EXTENDED = [
  ...CV_STATUS_DEFAULT,
  { title: 'Others', color: '#000861', id: 3 },
];

export const CV_STATUS_DEFAULT_VALUE = {
  FAILED: 0,
  PENDING: 1,
  PASS: 2,
  OTHER: 3,
};

export const CV_STATUS_INTERVIEW = [
  ...CV_STATUS_DEFAULT,
  { title: 'Absent', color: '#000861' },
];

export const CV_STATUS_ON_BOARD = [
  { title: 'NotToWork', color: '#c20000' },
  { title: 'Pending', color: '#a5b802' },
  { title: 'WentWork', color: '#0ea800' },
  { title: 'Postpone', color: '#b72073' },
];

export const CV_STATUS_THU_VIEC = [
  { title: 'Failed', color: '#c20000' },
  { title: 'Inprogress', color: '#a5b802' },
  { title: 'Pass', color: '#0ea800' },
  { title: 'Extend', color: '#000861' },
];

export const CV_STEP = [
  { title: 'STEP_NEW', color: '#f56c42', status: CV_STATUS_DEFAULT, id: 0 },
  {
    title: 'STEP_HR_REVIEW',
    color: '#f5a142',
    status: CV_STATUS_DEFAULT,
    id: 1,
  },

  {
    title: 'STEP_NHAN_TUONG_1',
    color: '#8aa127',
    status: CV_STATUS_DEFAULT,
    id: 2,
  },
  {
    title: 'STEP_CV_REVIEW',
    color: '#f5d742',
    status: CV_STATUS_DEFAULT,
    id: 3,
  },
  {
    title: 'STEP_TO_INTERVIEW',
    color: '#99cf25',
    status: CV_STATUS_DEFAULT,
    id: 4,
  },
  {
    title: 'STEP_INTERVIEW',
    color: '#23b01e',
    status: CV_STATUS_INTERVIEW,
    id: 5,
  },
  {
    title: 'STEP_NHAN_TUONG_2',
    color: '#1eb0a1',
    status: CV_STATUS_DEFAULT,
    id: 6,
  },
  {
    title: 'STEP_PRE_OFFER',
    color: '#1e7fb0',
    status: CV_STATUS_DEFAULT,
    id: 7,
  },
  { title: 'STEP_OFFER', color: '#1e4cb0', status: CV_STATUS_DEFAULT, id: 8 },
  {
    title: 'STEP_ON_BOARD',
    color: '#421eb0',
    status: CV_STATUS_ON_BOARD,
    id: 9,
  },
  {
    title: 'STEP_THU_VIEC',
    color: '#b01eab',
    status: CV_STATUS_THU_VIEC,
    id: 10,
  },
];
export const DEFAULT_PARAMS = { page: 1, limit: 10 };
export const LIST_ASSESSMENT = [
  {
    id: 1,
    value: 0,
    title: i18n.t('plan.easy'),
  },
  {
    id: 2,
    value: 1,
    title: i18n.t('plan.medium'),
  },
  {
    id: 3,
    value: 2,
    title: i18n.t('plan.hard'),
  },
  {
    id: 4,
    value: 3,
    title: i18n.t('plan.very_hard'),
  },
];
export const MONTH_FORMAT = 'MM/YYYY';
export const DAY_FORMAT = 'DD/MM/YYYY';

export const LABELSRADAR = [
  i18n.t('plan.total'),
  i18n.t('plan.pass_review'),
  i18n.t('plan.pass_interview'),
  i18n.t('plan.onboard'),
];
export const DEFAULT_STYLE_EXCEL = {
  fontName: 'Times New Roman',
  h: 'left',
  v: 'left',
};
// add locales over here
export const locales = {
  en: enUS,
  vi: viVN,
};
export const DEFAULT_LIMIT_RECORDS_EXPORT = 500;
export const CUSTOM_FIELDS = [
  {
    key: i18n.t('updateCv.fullname'),
    value: '<fullname>',
  },
  {
    key: i18n.t('updateCv.position'),
    value: '<position_title>',
  },
  {
    key: i18n.t('updateCv.level'),
    value: '<level_title>',
  },
  {
    key: i18n.t('updateCv.mobile'),
    value: '<mobile> ',
  },
  {
    key: i18n.t('updateCv.age'),
    value: '<age> ',
  },
  {
    key: i18n.t('updateCv.interviewer'),
    value: '<interviewer> ',
  },
  {
    key: i18n.t('updateCv.appoint_date'),
    value: '<appoint_date> ',
  },
  {
    key: i18n.t('updateCv.appoint_link'),
    value: '<appoint_link> ',
  },
  {
    key: i18n.t('updateCv.appoint_place'),
    value: '<appoint_place> ',
  },
  {
    key: i18n.t('updateCv.offer_level_title'),
    value: '<offer_level_title> ',
  },
  {
    key: i18n.t('updateCv.offer_onboard'),
    value: '<offer_onboard> ',
  },
  {
    key: i18n.t('updateCv.onboard_onboard'),
    value: '<onboard_onboard> ',
  },
];
