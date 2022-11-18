import i18n from '../../../translation/i18n';
import moment from 'moment';
import { DATE_TIME_FORMAT } from '../../../constants';
import { CheckCircleFilled, MinusCircleFilled } from '@ant-design/icons';

export const cols = [
  { type: 'id', width: 50, sorter: undefined },
  {
    type: 'title',
    title: i18n.t('emailHistory.receiver'),
    dataIndex: 'fullname',
  },
  {
    type: 'all',
    dataIndex: 'email',
    title: i18n.t('common.email'),
    width: 200,
  },
  {
    type: 'title',
    title: i18n.t('common.title'),
    width: 200,
    sorter: undefined,
  },
  {
    type: 'all',
    title: i18n.t('emailHistory.sendTime'),
    dataIndex: 'datecreate',
    width: 200,
    render(value, record) {
      const delayInSecond = record?.delay * 60 || 0;
      return value
        ? moment.unix(value + delayInSecond).format(DATE_TIME_FORMAT)
        : '';
    },
  },
  {
    type: 'all',
    title: i18n.t('emailHistory.sender'),
    dataIndex: 'author_id',
    width: 150,
  },
  {
    type: 'status',
    dataIndex: 'sent',
    render: value => {
      if (value) {
        return <CheckCircleFilled className="check-icon" />;
      } else {
        return <MinusCircleFilled className="pending-icon" />;
      }
    },
  },
  { type: 'action' },
];
