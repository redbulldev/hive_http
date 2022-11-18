import i18n from '../translation/i18n';

export const requiredFields = ['onboard'];

export const statusLabel = [
  {
    value: 2,
    label: i18n.t(`onboard.WentWork`),
  },
  {
    value: 0,
    label: i18n.t(`onboard.NotToWork`),
  },
  {
    value: 1,
    label: i18n.t(`onboard.pending`),
  },
  {
    value: 3,
    label: i18n.t(`onboard.Postpone`),
  },
];
