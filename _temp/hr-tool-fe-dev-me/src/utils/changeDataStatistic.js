import i18n from '../translation/i18n';
import { offerRequest, onboardOffer, onboardRequest } from './caculatePercent';

export function changeDataStatistic(summary, sources) {
  const { offer_success, onboard_cv, target } = summary;

  const newSumary = { ...summary };

  newSumary['ratio_offer_request'] = `${
    offerRequest(offer_success, target) || 0
  }%`;
  newSumary['ratio_onboard_request'] = `${
    onboardRequest(onboard_cv, target) || 0
  }%`;
  newSumary['ratio_onboard_offer'] = `${
    onboardOffer(onboard_cv, offer_success) || 0
  }%`;
  newSumary['department_title'] = i18n.t('statistic.total');
  newSumary['positions_title'] = '';
  newSumary['levels'] = '';
  newSumary['priority'] = '';
  newSumary['month_year'] = '';
  newSumary['employees'] = '';
  newSumary['rest'] = `${target - onboard_cv}` || 0;
  return sources ? [...sources, newSumary] : [];
}
