import React from 'react';
import { useTranslation } from 'react-i18next';
import { GeneralSelect } from '../';
import { CV_STATUS_DEFAULT_EXTENDED } from '../../../constants';

export default function CvStatusSelect({ ...rest }) {
  const { t } = useTranslation();
  const status = CV_STATUS_DEFAULT_EXTENDED.map(item => ({
    ...item,
    title: t(`cv.${item.title}`),
  }));
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      fetchedItems={status}
    />
  );
}
