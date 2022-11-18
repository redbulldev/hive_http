import i18n from '../../../translation/i18n';

export const cols = [
  {
    type: 'all',
    dataIndex: 'nth',
    title: '#',
    sorter: false,
    width: '2%',
    defaultSortOrder: 'username',
  },
  {
    type: 'title',
    title: i18n.t('user.username'),
    showSorterTooltip: {
      title: i18n.t('language.titleChangeSorter'),
    },
    dataIndex: 'username',
    width: '15%',
  },
  {
    type: 'title',
    dataIndex: 'fullname',
    title: i18n.t('user.fullname'),
    width: '22%',
    showSorterTooltip: {
      title: i18n.t('language.titleChangeSorter'),
    },
  },
  {
    type: 'title',
    title: i18n.t('user.role_id'),
    dataIndex: 'role_title',
  },
  {
    type: 'title',
    dataIndex: 'email',
    title: i18n.t('user.email'),
    showSorterTooltip: {
      title: i18n.t('language.titleChangeSorter'),
    },
    width: '20%',
    sorter: undefined,
  },
  {
    type: 'status',
    width: 90,
  },
  { type: 'action', width: '10%' },
];

export const requiredFields = ['fullname', 'username', 'email', 'role_id'];
