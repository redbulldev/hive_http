import { Tooltip } from 'antd';
import moment from 'moment';
import { DAY_FORMAT } from '.';
import i18n from '../translation/i18n';
import { onboardOffer, onboardRequest } from '../utils/caculatePercent';
import { DEFAULT_PRIORITY, LIST_ASSESSMENT } from './requestPage';
export const createPlanColumns = (isExported = false) => {
  const cols = [
    {
      title: i18n.t('plan.month_year'),
      dataIndex: 'month_year',
      render(_, item) {
        return !isExported ? (
          <span>{`${item?.month}/${item?.year}`}</span>
        ) : (
          `${item?.month}/${item?.year}`
        );
      },
      fixed: 'left',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.number_of_request'),
      dataIndex: 'target',
      type: 'all',
    },
    {
      title: i18n.t('plan.number_of_cv'),
      dataIndex: 'total_cv',
      type: 'all',
    },
    {
      title: i18n.t('plan.number_of_cv_joined_interview'),
      dataIndex: 'interview_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.number_of_cv_passed_interview'),
      dataIndex: 'pass_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.number_of_uv_offered'),
      dataIndex: 'offer_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.offer_successfully'),
      dataIndex: 'offer_success',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.number_of_uv_worked'),
      dataIndex: 'onboard_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.ratio_offer_request'),
      dataIndex: 'ratio_offer_request',
      type: 'allWithoutSort',

      render(text, item) {
        return !isExported ? (
          <span>
            {Number(item?.offer_success) && Number(item?.target)
              ? `${
                  onboardRequest(item?.offer_success, item?.target)
                    ? onboardRequest(item?.offer_success, item?.target)
                    : 0
                }%`
              : ''}
          </span>
        ) : Number(item?.offer_success) && Number(item?.target) ? (
          `${
            onboardRequest(item?.offer_success, item?.target)
              ? onboardRequest(item?.offer_success, item?.target)
              : 0
          }%`
        ) : (
          ''
        );
      },
    },
    {
      title: i18n.t('plan.ratio_onboard_request'),
      dataIndex: 'ratio_onboard_request',
      type: 'allWithoutSort',

      render(text, item) {
        return !isExported ? (
          <span>
            {Number(item?.onboard_cv) && Number(item?.target)
              ? `${
                  onboardRequest(item?.onboard_cv, item?.target)
                    ? onboardRequest(item?.onboard_cv, item?.target)
                    : 0
                }%`
              : ''}
          </span>
        ) : Number(item?.onboard_cv) && Number(item?.target) ? (
          `${
            onboardRequest(item?.onboard_cv, item?.target)
              ? onboardRequest(item?.onboard_cv, item?.target)
              : 0
          }%`
        ) : (
          ''
        );
      },
    },
    {
      title: i18n.t('plan.ratio_onboard_offer'),
      dataIndex: 'ratio_onboard_offer',
      type: 'allWithoutSort',

      render(text, item) {
        return !isExported ? (
          <span>
            {Number(item?.onboard_cv) && Number(item?.offer_success)
              ? `${
                  onboardOffer(item?.onboard_cv, item?.offer_success)
                    ? onboardOffer(item?.onboard_cv, item?.offer_success)
                    : 0
                }%`
              : ''}
          </span>
        ) : Number(item?.onboard_cv) && Number(item?.offer_success) ? (
          `${
            onboardOffer(item?.onboard_cv, item?.offer_success)
              ? onboardOffer(item?.onboard_cv, item?.offer_success)
              : 0
          }%`
        ) : (
          ''
        );
      },
    },
    {
      title: i18n.t('plan.number_of_failed_people'),
      dataIndex: 'fail_job',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.rest'),
      dataIndex: 'rest',
      type: 'allWithoutSort',
      render(_, item) {
        const value = item?.target - item?.onboard_cv;
        return !isExported ? (
          <span>{value > 0 ? value : '0'}</span>
        ) : value > 0 ? (
          value
        ) : (
          '0'
        );
      },
    },
  ];
  const exportCols = isExported
    ? isExported?.map((col, i) => {
        return { ...col, ...cols[i] };
      })
    : [];
  return isExported ? exportCols : cols;
};
export const createPlanDetailColumn = (isExported = false) => {
  const cols = [
    {
      type: 'id',
      fixed: 'left',
      sorter: undefined,
    },
    {
      title: i18n.t('plan.department'),
      dataIndex: 'department_title',
      width: 120,
      align: 'center',

      fixed: 'left',
      type: 'allWithoutSort',
      render(text) {
        const value = text ? text : '-';
        return !isExported ? <span>{value}</span> : value;
      },
    },
    {
      title: i18n.t('plan.position'),
      dataIndex: 'position_title',
      fixed: 'left',
      type: 'allWithoutSort',
      align: 'center',

      render(text) {
        const value = text ? text : '-';
        return !isExported ? (
          <Tooltip
            placement="top"
            title={value}
            color="black"
            getContainer={triggerNode => triggerNode.parentNode}
          >
            {value}
          </Tooltip>
        ) : (
          value
        );
      },
    },
    {
      title: i18n.t('plan.requestor'),
      dataIndex: 'requestor_id',
      type: 'allWithoutSort',
      width: 150,
      align: 'center',

      render(text) {
        const value = text ? text : '-';
        return !isExported ? <span>{value}</span> : value;
      },
    },
    {
      title: i18n.t('request.status'),
      dataIndex: 'status',
      align: 'center',

      render: (_, row) => {
        return !isExported ? (
          <span>
            {row?.onboard_cv < row?.target
              ? 'Ongoing'
              : row?.onboard_cv === row?.target
              ? 'Done'
              : 'Cancel'}
          </span>
        ) : row?.onboard_cv < row?.target ? (
          'Ongoing'
        ) : row?.onboard_cv === row?.target ? (
          'Done'
        ) : (
          'Cancel'
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.request_date'),
      dataIndex: 'datecreate',
      width: 180,
      align: 'center',

      render(_) {
        const value = moment(_ * 1000).format(DAY_FORMAT);
        return !isExported && value ? <span>{value}</span> : value ? value : '';
      },
      type: 'all',
    },

    {
      title: i18n.t('plan.language'),
      dataIndex: 'languages',
      width: 200,
      align: 'center',

      render(text) {
        const value = text ? JSON.parse(text).join(',') : '-';
        return !isExported ? (
          <Tooltip
            placement="top"
            title={value}
            color="black"
            getContainer={triggerNode => triggerNode.parentNode}
          >
            {value}
          </Tooltip>
        ) : (
          value
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.employment_type'),
      dataIndex: 'typework_title',
      type: 'allWithoutSort',
      width: 200,
      align: 'center',

      render(text) {
        return !isExported ? (
          <span>{text ? text : '-'}</span>
        ) : text ? (
          text
        ) : (
          '-'
        );
      },
    },
    {
      title: i18n.t('plan.level'),
      dataIndex: 'levels',
      align: 'center',
      width: 300,
      render: _ => {
        const parsed = _
          ? JSON.parse(_)
              .map(item => item.title)
              .join(', ')
          : '';
        return !isExported ? (
          <Tooltip
            placement="bottom"
            title={parsed}
            color="black"
            getContainer={triggerNode => triggerNode.parentNode}
          >
            {parsed}
          </Tooltip>
        ) : _ ? (
          parsed
        ) : (
          '-'
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.amount'),
      dataIndex: 'target',
      width: 120,
      align: 'center',

      type: 'all',
      render(text) {
        const value = text ? text : '-';
        return !isExported ? <span>{value}</span> : value;
      },
    },
    {
      title: i18n.t('plan.recruited'),
      dataIndex: 'onboard_cv',
      width: 120,
      align: 'center',

      show: true,
      type: 'allWithoutSort',
      render(text) {
        const value = ![undefined, null].includes(text) ? text : '-';
        return !isExported ? <span>{value}</span> : value;
      },
    },
    {
      title: i18n.t('plan.priority'),
      dataIndex: 'priority',

      render(text) {
        const value =
          text === null ? i18n.t('plan.medium') : DEFAULT_PRIORITY[text]?.title;
        return !isExported ? <span>{value}</span> : value;
      },
      align: 'center',

      type: 'all',
    },
    {
      title: i18n.t('plan.assessment'),
      dataIndex: 'assessment',

      render(_) {
        const value = LIST_ASSESSMENT[_]?.title || '-';
        return !isExported ? <span>{value}</span> : value;
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('plan.assignee'),
      dataIndex: 'assignee_id',
      type: 'allWithoutSort',
      width: 200,
      align: 'center',

      render(text) {
        const value = text ? text : '-';
        return !isExported ? <span>{value}</span> : value;
      },
    },
    {
      title: i18n.t('plan.recruitment_chanel'),
      dataIndex: 'sources',
      width: 200,
      align: 'center',
      render(text) {
        const value = text ? JSON.parse(text)?.join(',') : '-';
        return !isExported ? (
          <Tooltip
            placement="top"
            title={value}
            color="black"
            getContainer={triggerNode => triggerNode.parentNode}
          >
            {value}
          </Tooltip>
        ) : (
          value
        );
      },
      type: 'allWithoutSort',
    },
  ];
  const exportCols = isExported
    ? isExported?.map((col, i) => {
        return { ...col, ...cols[i] };
      })
    : [];

  return isExported ? exportCols : cols;
};
