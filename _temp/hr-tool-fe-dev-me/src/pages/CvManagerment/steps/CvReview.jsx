import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import CVReviewApi from '../../../api/CVReviewApi';
import { requiredFields } from '../../../constants/cvReview';
import withStepFrame from './withStepFrame';

function CvReview(props) {
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
      api: CVReviewApi.getById,
      submitApi: CVReviewApi.post,
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

export default withStepFrame(CvReview);
