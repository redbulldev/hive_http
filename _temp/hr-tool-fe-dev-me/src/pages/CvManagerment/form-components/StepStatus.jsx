import { Form, Radio } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { stepCantChangeBefore } from '../../../constants/cvDetail';
import { CV_STATUS_DEFAULT_VALUE } from '../../../constants';

export default function StepStatus({
  step,
  status,
  setIsFailed,
  form,
  statusLabel,
  disabled,
}) {
  const { t } = useTranslation();

  const changeCheckFail = e => {
    if (e.target.value !== 0) {
      form.setFields([
        {
          name: 'reason',
          errors: [],
        },
      ]);
      setIsFailed(false);
    } else {
      setIsFailed(true);
    }
  };

  const getDisabled = () => {
    if (disabled === false) return false;
    if (disabled) return disabled;
    return (
      step > stepCantChangeBefore ||
      (step === stepCantChangeBefore &&
        (status === CV_STATUS_DEFAULT_VALUE.PASS ||
          status === CV_STATUS_DEFAULT_VALUE.FAILED))
    );
  };

  return (
    <>
      <h5
        style={{
          fontSize: '14px',
          marginBottom: 2,
          fontWeight: '700',
          color: 'rgba(0, 0, 0, 0,85)',
          lineHeight: '22px',
          textDecoration: 'underline',
        }}
      >
        {t('review.summary')}
      </h5>
      <Form.Item style={{ marginBottom: '10px' }} name="status">
        <Radio.Group
          disabled={getDisabled()}
          onChange={changeCheckFail}
          style={{ textTransform: 'uppercase' }}
        >
          {statusLabel ? (
            statusLabel.map(item => (
              <Radio key={item.value} value={item.value}>
                {item.label}
              </Radio>
            ))
          ) : (
            <>
              <Radio key={2} value={2}>
                {t('review.success')}
              </Radio>
              <Radio key={0} value={0}>
                {t('review.fail')}
              </Radio>
              <Radio key={1} value={1}>
                {t('review.depending')}
              </Radio>
            </>
          )}
        </Radio.Group>
      </Form.Item>
    </>
  );
}
