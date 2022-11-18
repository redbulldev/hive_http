import { FormInput } from '../components/Form';
import Status from '../pages/Settings/components/Status';
import i18n from '../translation/i18n';

export const fieldsCreator = titlePlaceholder => [
  {
    component: FormInput,
    props: {
      name: 'title',
      label: i18n.t('typework.titleValue'),
      placeholder: titlePlaceholder,
      required: true,
      max: 200,
      min: 3,
    },
  },
  {
    component: FormInput,
    props: {
      textArea: true,
      name: 'description',
      label: i18n.t('typework.descriptionColumn'),
      max: 5000,
      placeholder: i18n.t('typework.descriptionPlaceholder'),
      rows: 8,
    },
  },
  {
    component: Status,
  },
];
