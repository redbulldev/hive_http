import React from 'react';
import { useTranslation } from 'react-i18next';
import SubmitBtn from '../../../components/Form/SubmitBtn';

export default function StepSubmitBtn({
  condition = () => false,
  form,
  reasonName,
  ...rest
}) {
  const { t } = useTranslation();
  const g = form.getFieldValue;
  const reason = reasonName || 'reason';
  return (
    <SubmitBtn
      {...rest}
      form={form}
      fieldsToCheck={() => {
        if (g('status') === 0) return 'noCheck';
        return 'normal';
      }}
      condition={() => {
        //Begin: condition when change status to failed
        const reasonCondition =
          g('status') === 0 &&
          (!g(reason) || form.getFieldError(reason).length > 0);
        //End: condition when change status to failed

        const moreCondition = condition();
        return reasonCondition || moreCondition;
      }}
    >
      {t('review.save')}
    </SubmitBtn>
  );
}
