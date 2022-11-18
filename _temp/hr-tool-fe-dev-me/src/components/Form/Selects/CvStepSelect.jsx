import React from 'react';
import { useTranslation } from 'react-i18next';
import { GeneralSelect } from '../';
import { CV_STEP } from '../../../constants';

export default function CvStepSelect({ ...rest }) {
  const { t } = useTranslation();
  const steps = CV_STEP.map(item => ({
    ...item,
    title: t(`cv.${item.title}`),
  }));
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      fetchedItems={steps}
    />
  );
}
