import moment from 'moment';
export function getPreviousMonth(time) {
  const newTime = new Date(time);
  newTime.setDate(1);

  return moment(newTime).subtract(1, 'months');
}
export function getEndOfPreviousMonth(time) {}
export function getTwoPreviousMonth(time) {
  const newTime = new Date(time);
  newTime.setDate(1);
  return moment(newTime).subtract(2, 'months');
}
