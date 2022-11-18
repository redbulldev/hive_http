import moment from 'moment';
import { BACKEND_ONBOARD_CV_DETAIL_FORMAT } from '../../../constants';

export const preProcessCv = cv => {
  const newCv = { ...cv };
  const { images } = newCv;

  // Images
  newCv.images = images && JSON.parse(images).map(item => ({ show: item }));

  // Age
  const birthday =
    cv.birthday && moment(cv.birthday, 'YYYY-MM-DD').format('YYYY');
  newCv.age = birthday && +moment().format('YYYY') - +birthday;

  // Onboard
  newCv.momentOnboard =
    cv?.onboard && moment(cv?.onboard, BACKEND_ONBOARD_CV_DETAIL_FORMAT);

  return newCv;
};
