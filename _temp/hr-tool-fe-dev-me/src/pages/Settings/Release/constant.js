import moment from 'moment';
import { DATE_FORMAT } from '../../../constants';

export const breadcrumbNameMap = {
  '/setting/release': 'Phiên bản',
  '/setting/release/config': 'Cấu hình',
};

export const requiredFields = ['version', 'conent'];

export const cols = [
  {
    type: 'all',
    title: 'Phiên bản',
    width: '25%',
    dataIndex: 'version',
  },
  {
    type: 'all',
    title: 'Ngày phát hành',
    dataIndex: 'daterelease',
    render: value => {
      return value ? moment(value).format(DATE_FORMAT) : '';
    },
  },
  { type: 'action', width: '10%' },
];
