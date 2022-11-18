import i18n from '../translation/i18n';

export const ROLE_COLUMNS = [
  {
    title: '#',
    dataIndex: 'id',
    width: '5%',
    type: 'id',
    sorter: undefined,
  },
  {
    title: i18n.t('role.roleTitle'),
    dataIndex: 'title',
    width: '70%',
    type: 'title',
  },

  {
    type: 'status',
    width: '10%',
  },
];
