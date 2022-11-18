import { Tooltip } from 'antd';
import { DEFAULT_PRIORITY } from '../../constants/requestPage';
import i18n from '../../translation/i18n';
import {
  offerRequest,
  onboardOffer,
  onboardRequest,
} from '../../utils/caculatePercent';
export const department = ['Phòng ban 3', 'BOZ', 'Production', 'Sales'];
export const createDashboardColumns = (isExport = false, isRequest = true) => {
  const cols = [
    {
      title: i18n.t('role.department'),
      dataIndex: 'department_title',
      summary: true,
      type: 'allWithoutSort',
      fixed: 'left',
      render: value => {
        return <div className="white-space-nowrap">{value}</div>;
      },
    },
    {
      title: i18n.t('statistic.position'),
      dataIndex: 'positions_title',
      summary: true,
      type: 'allWithoutSort',
      ellipsis: true,
      fixed: 'left',
      render: value => (
        <Tooltip placement="top" title={value}>
          {value}
        </Tooltip>
      ),
    },
    {
      title: i18n.t('statistic.level'),
      dataIndex: 'levels',
      type: 'title',
      fixed: 'left',
      render: _ => {
        const value = _
          ? JSON.parse(_)
              .map(item => item.title)
              .join(', ')
          : '-';
        return !isExport ? (
          <span>
            {_ ? (
              <Tooltip placement="top" title={value}>
                {value.slice(0, 14)}...
              </Tooltip>
            ) : (
              '-'
            )}
          </span>
        ) : _ ? (
          value
        ) : (
          '-'
        );
      },
    },
    {
      title: i18n.t('statistic.number_of_cv'),
      dataIndex: 'total_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.number_of_cv_joined_interview'),
      dataIndex: 'interview_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.number_of_cv_passed_interview'),
      dataIndex: 'pass_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.number_of_uv_offered'),
      dataIndex: 'offer_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.offer_successfully'),
      dataIndex: 'offer_success',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.number_of_uv_worked'),
      dataIndex: 'onboard_cv',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.ratio_offer_request'),
      dataIndex: 'ratio_offer_request',
      render(text, item) {
        return !isExport ? (
          <span>{`${
            offerRequest(item.offer_success, item.target) || 0
          }%`}</span>
        ) : (
          `${offerRequest(item.offer_success, item.target) || 0}%`
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.ratio_onboard_request'),
      dataIndex: 'ratio_onboard_request',
      type: 'allWithoutSort',

      render(_, item) {
        return !isExport ? (
          <span>{`${onboardRequest(item.onboard_cv, item.target) || 0}%`}</span>
        ) : (
          `${onboardRequest(item.onboard_cv, item.target) || 0}%`
        );
      },
    },
    {
      title: i18n.t('statistic.ratio_onboard_offer'),
      dataIndex: 'ratio_onboard_offer',
      render(_, item) {
        return !isExport ? (
          <span>{`${
            onboardOffer(item.onboard_cv, item.offer_success) || 0
          }%`}</span>
        ) : (
          `${onboardOffer(item.onboard_cv, item.offer_success) || 0}%`
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.finish_day'),
      dataIndex: 'month_year',
      render(_, item) {
        return !isExport ? (
          <span>
            {item.month && item.year ? `${item.month}/${item.year}` : ''}
          </span>
        ) : item.month && item.year ? (
          `${item.month}/${item.year}`
        ) : (
          ''
        );
      },
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('statistic.list_of_uv_work'),
      dataIndex: 'employees',
      ellipsis: true,
      render: text => {
        if (!text) return null;
        const arr = JSON.parse(text);
        const value = arr?.length > 0 ? arr.join(', ') : null;
        return !isExport ? (
          <Tooltip placement="top" title={value}>
            {value}
          </Tooltip>
        ) : (
          value
        );
      },
      type: 'allWithoutSort',
    },
  ];
  if (isRequest) {
    cols.splice(
      3,
      0,
      {
        title: i18n.t('statistic.prioritize'),
        dataIndex: 'priority',
        render(text) {
          return !isExport ? (
            <span>
              {text === null
                ? i18n.t('plan.medium')
                : DEFAULT_PRIORITY[text]?.title}
            </span>
          ) : text === null ? (
            i18n.t('plan.medium')
          ) : (
            DEFAULT_PRIORITY[text]?.title
          );
        },
        type: 'allWithoutSort',
      },
      {
        title: i18n.t('statistic.number_of_request'),
        dataIndex: 'target',
        type: 'allWithoutSort',
      },
    );
    cols.splice(cols.length - 1, 0, {
      title: i18n.t('statistic.rest'),
      dataIndex: 'rest',
      type: 'allWithoutSort',

      render(_, row) {
        const minus = row.target - row.onboard_cv;
        const value = !minus || minus < 0 ? 0 : minus;
        return !isExport ? <span>{value}</span> : value;
      },
    });
  }
  const exportCols = isExport
    ? isExport?.map((col, i) => {
        return { ...col, ...cols[i] };
      })
    : [];
  return isExport ? exportCols : cols;
};
