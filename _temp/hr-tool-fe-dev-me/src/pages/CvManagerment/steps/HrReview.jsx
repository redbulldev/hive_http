import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import HRReviewApi from '../../../api/HRReviewApi';
import { requiredFields } from '../../../constants/hrReview';

function HrReview(props) {
  const {
    form,
    Frame,
    FormInput,
    FormTextArea,
    Reason,
    Status,
    setConfig,
    step,
    status,
  } = props;
  const { t } = useTranslation();

  useEffect(() => {
    setConfig({
      api: HRReviewApi.getById,
      submitApi: HRReviewApi.post,
      requiredFields: requiredFields,
      submitObject: values => {
        return values;
      },
    });
  }, []);

  return (
    <Frame>
      <Row gutter={20}>
        <Col span={12}>
          <FormTextArea name="notes" label={t('review.review')} rows={7} />
        </Col>
        <Col span={12}>
          <Status step={step} status={status} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(HrReview);
