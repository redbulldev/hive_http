import { Tooltip } from 'antd';
import i18next from 'i18next';
import moment from 'moment';
import DefaultImg from '../../../assets/images/cvManagement/userDefault.png';
import {
  CV_STATUS_DEFAULT_VALUE,
  CV_STEP,
  DATE_FORMAT_ONBOARD,
  DATE_TIME_FORMAT,
} from '../../../constants';
import i18n from '../../../translation/i18n';
export const FORM_ITEM_ADD_CV = [
  {
    name: 'fullname',
    label: i18next.t('user.fullname'),
    maxlength: 200,
    required: true,
    rules: [
      {
        whitespace: true,
        required: true,
        message: i18next.t('user.validateRequireMessageInput'),
      },
      {
        validator: (_, val) => {
          let message = i18next.t('user.requireFullname');
          val = val.trim();
          if (val && val.length < 3) return Promise.reject(message);
          return Promise.resolve();
        },
      },
    ],
  },
  {
    name: 'position_id',
    label: i18next.t('user.position'),
    rules: [
      { required: true, message: i18next.t('user.validateRequireMessage') },
    ],
  },
  {
    name: 'level_id',
    label: i18next.t('user.level'),
    rules: [
      { required: true, message: i18next.t('user.validateRequireMessage') },
    ],
  },
  {
    name: 'request_id',
    label: 'Yêu cầu',
    required: true,
    rules: [
      { required: true, message: i18next.t('user.validateRequireMessage') },
    ],
  },
  {
    name: 'reviewer_id',
    label: i18next.t('updateCv.reviewer'),
  },
  {
    name: 'interviewer_id',
    label: i18next.t('updateCv.interviewer'),
  },
  {
    name: 'assignee_id',
    label: i18next.t('updateCv.assignee'),
    rules: [
      { required: true, message: i18next.t('user.validateRequireMessage') },
    ],
  },

  {
    name: 'email',
    label: i18next.t('user.email'),
    maxlength: 200,
    className: 'emailRef',

    rules: [
      {
        type: 'email',
        message: i18next.t('user.validateEmail'),
      },
      {
        validator(_, value) {
          value = value?.trim();
          if (value) {
            if (/.@./g.test(value)) {
              const str = value.split('@')[0];
              if (str.length > 64)
                return Promise.reject(
                  new Error('Tên email trước @ không được quá 64 kí tự'),
                );
            }
          }
          return Promise.resolve();
        },
      },
    ],
  },
  {
    name: 'mobile',
    label: i18next.t('user.mobile'),
    maxlength: 15,
    className: 'mobileRef',
    rules: [
      {
        validator: (_, val) => {
          let message = i18next.t('cv.validateMobile');
          let check = false;
          if (
            val?.trim().length > 9 &&
            val?.trim().length < 13 &&
            Number(val)
          ) {
            check = true;
            message = '';
          }
          if (!val) {
            check = true;
            message = '';
          }
          return check ? Promise.resolve(message) : Promise.reject(message);
        },
      },
    ],
  },
  {
    name: 'birthday',
    label: i18next.t('user.birthday'),
    rules: [
      {
        validator: (_, val) => {
          if (val) {
            let message = i18next.t('cv.validateAge');
            let check = false;
            let date = moment(val).format('YYYY-MM-DD');
            if (new Date().getFullYear() - new Date(date).getFullYear() >= 18) {
              check = true;
              message = '';
            }
            if (val == null) {
              check = true;
              message = '';
            }
            return check ? Promise.resolve(message) : Promise.reject(message);
          }
          return Promise.resolve();
        },
      },
    ],
  },

  {
    name: 'source_id',
    label: i18next.t('user.source'),
  },
  {
    name: 'description',
    label: i18next.t('user.description'),
  },
  {
    name: 'checklist',
    label: i18next.t('updateCv.checklist'),
    rules: [],
  },
];
export const REG_VALIDATE_EMAIL =
  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

const stepStatusExport = row => {
  const step = CV_STEP[row.step];
  if (row.status > -1) {
    const status = step.status[row.status];
    return `${i18next.t('cv.' + step.title)} - ${i18next.t(
      'cv.' + status?.title,
    )}`;
  } else {
    return `${i18next.t('cv.' + step.title)}`;
  }
};
const stepStatus = row => {
  const step = CV_STEP[row.step];
  if (row.status > -1) {
    const status = step?.status[row.status];
    return (
      <>
        <b style={{ color: step?.color }}>{i18next.t('cv.' + step?.title)}</b> -{' '}
        <b style={{ color: status?.color }}>
          {i18next.t('cv.' + status?.title)}
        </b>
      </>
    );
  } else {
    return (
      <b style={{ color: step?.color }}>{i18next.t('cv.' + step?.title)}</b>
    );
  }
};

export const getStep = (step, status) => {
  if (
    ![undefined, null].includes(step) &&
    ![undefined, null].includes(status)
  ) {
    const found =
      step === 0
        ? {
            ...CV_STEP[1],
            statusValue: CV_STATUS_DEFAULT_VALUE.PENDING,
            stepValue: 1,
          }
        : { ...CV_STEP[step], statusValue: status, stepValue: step };

    found.stepTitle = i18n.t(`cv.${found.title}`);
    found.stepColor = found.color;

    const foundStatus = found.status[found.statusValue];

    found.statusTitle = i18n.t(`cv.${foundStatus.title}`);
    found.statusColor = foundStatus.color;

    return found;
  }
  return null;
};

export const createCvColumns = (isExported = false) => {
  const cols = [
    {
      type: 'id',
      sorter: false,
    },
    {
      title: '',
      dataIndex: 'images',
      type: 'allWithoutSort',
      export: false,
      render: _ => {
        return (
          <img
            src={JSON.parse(_)?.length ? JSON.parse(_)[0] : DefaultImg}
            style={{ width: '30px', height: '30px', borderRadius: '50%' }}
            alt="avatar"
          />
        );
      },
    },
    {
      title: i18next.t('cv.name'),
      dataIndex: 'fullname',
      type: 'title',
      sorter: false,
      render: fullname =>
        !isExported ? (
          <Tooltip
            placement="top"
            title={fullname || ''}
            className="text-truncate"
          >
            {fullname || '-'}
          </Tooltip>
        ) : (
          fullname || '-'
        ),
    },
    {
      title: i18next.t('cv.position'),
      dataIndex: 'position_title',
      type: 'title',
      sorter: false,
      render: position =>
        !isExported ? (
          <div className="text-truncate">{position || '-'}</div>
        ) : (
          position || '-'
        ),
      width: 150,
    },
    {
      title: i18next.t('cv.level'),
      dataIndex: 'level_title',
      sorter: false,
      type: 'title',
      render: level =>
        !isExported ? (
          <div className="text-truncate">{level || '-'}</div>
        ) : (
          level || '-'
        ),
      width: 150,
    },
    {
      title: i18next.t('cv.status'),
      dataIndex: 'status',
      type: 'all',
      sorter: undefined,
      render: (_, row) =>
        !isExported ? stepStatus(row) : stepStatusExport(row),
    },
    {
      title: i18next.t('cv.appointment'),
      dataIndex: 'appoint_date',
      type: 'all',
      render: value => {
        return value ? moment.unix(value).format(DATE_TIME_FORMAT) : '-';
      },
    },
    {
      title: i18next.t('common.assignee'),
      dataIndex: 'assignee_id',
      sorter: false,
      type: 'all',
    },
    {
      title: i18next.t('cv.lastUpdate'),
      dataIndex: 'datemodified',
      type: 'all',
      render: value => moment.unix(value).format(DATE_TIME_FORMAT),
    },
    {
      title: i18next.t('cv.onboard'),
      dataIndex: 'onboard',
      type: 'all',
      render: value =>
        value ? moment(value).format(DATE_FORMAT_ONBOARD) : '-',
    },
    {
      title: i18next.t('cv.datecreate'),
      dataIndex: 'datecreate',
      sorter: false,
      type: 'all',
      render: value =>
        value ? moment(value * 1000).format(DATE_FORMAT_ONBOARD) : '-',
    },
    {
      title: i18next.t('cv.source_title'),
      dataIndex: 'source_title',
      type: 'allWithoutSort',
    },
  ];
  const exportCols = isExported
    ? isExported?.map((col, i) => {
        delete col.width && delete col.align;
        return { ...col, ...cols[i] };
      })
    : [];

  return isExported ? exportCols : cols;
};
