import i18n from '../translation/i18n';

export const requiredFields = ['todate'];

export const statusLabel = [
  {
    value: 2,
    label: i18n.t(`review.success`),
  },
  {
    value: 0,
    label: i18n.t(`review.fail`),
  },
  {
    value: 3,
    label: i18n.t(`probation.extend`),
  },
  {
    value: 1,
    label: i18n.t(`probation.inprogressing`),
  },
];
