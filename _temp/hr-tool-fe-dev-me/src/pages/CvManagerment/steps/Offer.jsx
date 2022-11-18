import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import HRReviewApi from '../../../api/HRReviewApi';
import { requiredFields } from '../../../constants/offer';
import OfferCV from '../../../api/offer';
import { FormDatePicker } from '../../../components/Form';
import {
  FORMAT_DATEPICKER,
  FORMAT_POST_DATEPICKER,
} from '../../../constants/toInterview';
import moment from 'moment';

function Offer(props) {
  const {
    form,
    Frame,
    FormInput,
    FormTextArea,
    Reason,
    Status,
    setConfig,
    step,
    isFailed,
    status,
  } = props;
  const { t } = useTranslation();

  useEffect(() => {
    setConfig({
      api: OfferCV.getById,
      submitApi: OfferCV.post,
      requiredFields: requiredFields,
      fillData: values => {
        values.onboard = values.onboard
          ? moment(values?.onboard, FORMAT_POST_DATEPICKER)
          : undefined;
        return values;
      },
      submitObject: values => {
        if (values.onboard) {
          values.onboard = values.onboard.format(FORMAT_POST_DATEPICKER);
        }
        return values;
      },
    });
  }, []);

  return (
    <Frame>
      <Row gutter={20}>
        <Col span={12}>
          <FormDatePicker
            name="onboard"
            label={t('onboard.date')}
            disabled={isFailed}
            format={FORMAT_DATEPICKER}
            required
          />
          <FormTextArea name="content" label={t('offer.content')} rows={9} />
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

export default withStepFrame(Offer);
