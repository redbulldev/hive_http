import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import PhysiognomyReview2Api from '../../../api/PhysiognomyReview2Api';
import PhysiognomyReviewApi from '../../../api/PhysiognomyReviewApi';
import { requiredFields } from '../../../constants/physiognomy2';
import withStepFrame from './withStepFrame';

function Physiognomy2(props) {
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
      api: PhysiognomyReview2Api.getById,
      submitApi: PhysiognomyReview2Api.post,
      requiredFields: requiredFields,
      submitObject: values => {
        return values;
      },
    });
  }, []);

  const RateInput = ({ ...rest }) => (
    <FormInput {...rest} placeholder={t('physiognomy.ratePlaceholder')} />
  );

  return (
    <Frame>
      <Row gutter={20}>
        <Col span={12}>
          <RateInput name="psychology" label={t('review.psychology')} />
          <RateInput name="ability" label={t('review.ability')} />
          <RateInput name="thinking" label={t('review.thinking')} />
          <RateInput name="communication" label={t('review.communication')} />
          <RateInput name="summary" label={t('review.summary')} />
        </Col>
        <Col span={12}>
          <FormTextArea name="notes" label={t('review.review')} />
          <Status step={step} status={status} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(Physiognomy2);
