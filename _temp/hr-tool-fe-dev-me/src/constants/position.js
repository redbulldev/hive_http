import i18n from '../translation/i18n';

export const LIMIT_DEFAULT = 0;
export const createPositionColumns = (isExported = false) => {
  const cols = [
    {
      type: 'id',
      sorter: undefined,
    },

    {
      title: i18n.t('position.position'),
      dataIndex: 'title',
      type: 'title',
    },
    {
      title: i18n.t('position.department'),
      dataIndex: 'parent_title',
      type: 'allWithoutSort',
      render: (parent_title, record) => {
        return !isExported ? (
          <p
            className="position__table-parent_title"
            style={{ marginBottom: 0 }}
          >
            {parent_title}
          </p>
        ) : (
          parent_title
        );
      },
    },
    {
      title: i18n.t('position.description'),
      dataIndex: 'description',
      type: 'allWithoutSort',
      render: description => {
        return !isExported ? (
          <p className="position__table-description">{description} </p>
        ) : (
          description
        );
      },
    },

    {
      title: i18n.t('position.manager'),
      dataIndex: 'manager_id',
      type: 'allWithoutSort',
    },
    {
      title: i18n.t('position.requestor'),
      dataIndex: 'requestor',
      type: 'allWithoutSort',
      render: requestor =>
        !isExported ? (
          <div>{requestor ? JSON.parse(requestor)?.join(',') : '-'}</div>
        ) : requestor ? (
          JSON.parse(requestor)?.join(',')
        ) : (
          '-'
        ),
    },
    {
      type: 'status',
    },
    {
      type: 'action',
    },
  ];
  const exportCols = isExported
    ? isExported?.map((col, i) => {
        return { ...col, ...cols[i] };
      })
    : [];
  return isExported ? exportCols : cols;
};
