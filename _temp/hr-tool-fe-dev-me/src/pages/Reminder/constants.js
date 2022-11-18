import moment from 'moment';

export const enDaysLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

export const enFullDaysLabels = [
  'Monday',
  'Tuesday',
  'Wednesday',
  'Thursday',
  'Friday',
  'Saturday',
  'Sunday',
];

export const priorityColors = {
  low: '#8BBB11',
  medium: '#1890FF',
  high: '#FAAD14',
  veryHigh: '#FF4D4F',
};

export const getFirstDateAndLastDateOnThePanel = date => {
  const firstDate = moment(date).startOf('month');
  const lastDate = moment(date).endOf('month');

  const firstDateDay = firstDate.day() || 7;
  firstDate.subtract(firstDateDay - 1, 'days');
  lastDate.add(43 - Number(lastDate.format('DD')) - firstDateDay, 'days');

  return {
    firstDate,
    lastDate,
  };
};

export const MAX_EVENTS_ON_CELL = 3;
