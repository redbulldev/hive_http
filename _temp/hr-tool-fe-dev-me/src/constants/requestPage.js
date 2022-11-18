import i18n from '../translation/i18n';
import moment from 'moment';
import { Tooltip } from 'antd';
export const CREATE_TITLE_FORM = i18n.t('request.createRequest');
export const EDIT_TITLE_FORM = i18n.t('request.titleEditForm');
export const DETAIL_TITLE_FORM = i18n.t('request.titleDetailForm');
export const DEFAULT_PAGESIZE = 10;
export const DEFAULT_PAGENUMBER = 1;
export const DATE_FORMAT_DAY_CREATED = 'DD/MM/YYYY';
export const DATE_FORMAT_DEADLINE = 'DD/MM/YYYY';
export const MONTH_FORMAT = 'MM/YYYY';
export const DEADLINE_FORMATED = 'YYYY-MM';
export const DEADLINE_DEFAULT = 'YYYY-MM-DD';
export const DEFAULT_AUTHOR_LEVEL = 'namng';
export const DEFAULT_TYPEWORK = [20]; // 20 === id of 'Fulltime' from db
export const DEFAULT_MAX_LENGTH_DESCRIPTION = 5000;
export const LIST_ASSESSMENT = [
  {
    id: 1,
    value: 0,
    title: i18n.t('request.easyAssessment'),
  },
  {
    id: 2,
    value: 1,
    title: i18n.t('request.mediumAssessment'),
  },
  {
    id: 3,
    value: 2,
    title: i18n.t('request.hardAssessment'),
  },
  {
    id: 4,
    value: 3,
    title: i18n.t('request.veryHardAssessment'),
  },
];
export const DEFAULT_PRIORITY = [
  {
    value: 0,
    title: i18n.t('request.low'),
  },
  {
    value: 1,
    title: i18n.t('request.medium'),
  },
  {
    value: 2,
    title: i18n.t('request.mediumPLus'),
  },
  {
    value: 3,
    title: i18n.t('request.hight'),
  },
];
export const LIST_REQUEST_STATUS = [
  {
    id: 1,
    value: 0,
    color: '#1890ff',
    text: i18n.t('request.newStatus'),
  },
  {
    id: 2,
    value: 1,
    color: '#FF4D4F',
    text: i18n.t('request.rejectStatus'),
  },
  {
    id: 3,
    value: 2,
    color: '#15a512d9',
    text: i18n.t('request.approveStatus'),
  },
  {
    id: 4,
    value: 3,
    color: 'red',
    text: 'Đã dừng',
  },
];
export const PARAMS_GET_ALL = {
  status: 1,
  limit: 0,
};
export const MAX_QUANTITY_TARGET = 99;
export const QUANTITY_RULES = [
  () => ({
    validator(_, value) {
      if (value < 1) {
        return Promise.reject(i18n.t('request.quantityMustGreaterZero'));
      }
      if (value > MAX_QUANTITY_TARGET) {
        return Promise.reject(
          i18n.t('request.quantityMax', { maxQuantity: MAX_QUANTITY_TARGET }),
        );
      }
      return Promise.resolve();
    },
  }),
];
export const DEADLINE_RULES = [
  {
    required: true,
    message: i18n.t('request.deadlineIsRequire'),
  },
];
export const LEVEL_RULES = [
  {
    required: true,
    message: i18n.t('request.levelIsRequire'),
  },
];
export const YEAR_MONTH_FORMATED = 'YYYY MM';
export const filterOption = (input, option) => {
  input = input.trim().toLowerCase();
  return option.children?.toLowerCase()?.includes(input);
};
export const checkStringNotAllow = event => {
  if (!/[0-9]/.test(event.key)) {
    event.preventDefault();
  }
};
export const createRequestColumns = (isExported = false) => {
  const cols = [
    {
      type: 'id',
      sorter: undefined,
    },
    {
      dataIndex: 'department_title',
      type: 'title',
      title: i18n.t('request.department'),
      sorter: undefined,
    },
    {
      title: i18n.t('request.position'),
      dataIndex: 'position_title',
      type: 'title',
      sorter: undefined,
    },
    {
      title: i18n.t('request.dateCreated'),
      dataIndex: 'datecreate',
      type: 'all',
      render: datecreate => {
        return moment(datecreate * 1000).format(DATE_FORMAT_DAY_CREATED);
      },
    },
    {
      title: i18n.t('request.requestor'),
      dataIndex: 'requestor_id',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('request.totalCv'),
      dataIndex: 'target',
      type: 'all',
    },
    {
      title: i18n.t('request.level'),
      dataIndex: 'levels',
      type: 'title',
      sorter: undefined,
      width: 160,
      render: level => {
        const levels = level
          ? JSON.parse(level)
              .map(item => item.title)
              .join(', ')
          : '-';
        return !isExported ? (
          <Tooltip placement="top" title={levels}>
            <div className="text-truncate">{levels}</div>
          </Tooltip>
        ) : (
          levels
        );
      },
    },
    {
      title: i18n.t('request.language'),
      dataIndex: 'languages',
      width: '8%',
      type: 'title',
      sorter: undefined,
      render: languages => {
        const transformedLang = languages && JSON.parse(languages).join(', ');
        return !isExported
          ? (languages && (
              <div className="text-truncate">{transformedLang}</div>
            )) ||
              '-'
          : transformedLang || '-';
      },
    },
    {
      title: i18n.t('request.priority'),
      dataIndex: 'priority',
      render: priority => {
        return DEFAULT_PRIORITY[priority]?.title ?? '-';
      },
      type: 'all',
    },
    {
      title: i18n.t('request.rate'),
      dataIndex: 'assessment',
      render: assessment => {
        return LIST_ASSESSMENT[assessment]?.title ?? '-';
      },
      type: 'all',
    },
    {
      title: i18n.t('request.status'),
      dataIndex: 'status',
      width: '10%',

      render: status => {
        const { color, text } =
          LIST_REQUEST_STATUS.find(item => item.value === status) || {};
        return !isExported ? <span style={{ color }}>{text}</span> : text || '';
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('request.deadline'),
      dataIndex: 'deadline',
      width: '8%',
      render: (_, record) => {
        return record.deadline
          ? moment(record.deadline).format(DATE_FORMAT_DEADLINE)
          : '';
      },
      type: 'all',
    },
  ];

  const exportCols = isExported
    ? isExported?.map((col, i) => {
        return { ...col, ...cols[i] };
      })
    : [];

  return isExported ? exportCols : cols;
};
