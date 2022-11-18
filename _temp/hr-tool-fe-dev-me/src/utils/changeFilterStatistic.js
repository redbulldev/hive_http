import moment from 'moment';
export function changeFilter(filter) {
  if (filter.from && filter.to) {
    filter.date = [
      moment(filter.from, 'YYYY/MM/DD'),
      moment(filter.to, 'YYYY/MM/DD'),
    ];
    delete filter.from;
    delete filter.to;
  }
  console.log('filter', filter);
  return filter;
}
