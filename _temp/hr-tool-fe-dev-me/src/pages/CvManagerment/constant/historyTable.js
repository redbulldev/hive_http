import { DATE_FORMAT_ONBOARD } from '../../../constants';
import i18n from '../../../translation/i18n';
import moment from 'moment';

export const cols = [
  {
    type: 'id',
    sorter: undefined,
  },
  {
    title: i18n.t('cvDetail.history'),
    type: 'all',
    width: 150,
    dataIndex: 'datecreate',
    sorter: undefined,
    render: e => moment.unix(e).format(DATE_FORMAT_ONBOARD),
  },
  {
    title: i18n.t('cv.description'),
    sorter: undefined,
    type: 'description',
  },
];
