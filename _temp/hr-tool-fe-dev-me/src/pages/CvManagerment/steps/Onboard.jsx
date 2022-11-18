import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import HRReviewApi from '../../../api/HRReviewApi';
import { requiredFields, statusLabel } from '../../../constants/onboard';
import {
  FORMAT_DATEPICKER,
  FORMAT_POST_DATEPICKER,
} from '../../../constants/toInterview';
import moment from 'moment';
import { FormDatePicker } from '../../../components/Form';
import OnBoardCV from '../../../api/onBoard';

function Onboard(props) {
  const {
    form,
    Frame,
    FormInput,
    FormTextArea,
    Reason,
    Status,
    setConfig,
    isFailed,
    step,
    status,
  } = props;
  const { t } = useTranslation();

  useEffect(() => {
    setConfig({
      api: OnBoardCV.getById,
      submitApi: OnBoardCV.post,
      requiredFields: requiredFields,
      submitProps: {
        reasonName: 'notes',
      },
      fillData: values => {
        values.onboard = values.onboard
          ? moment(values?.onboard, FORMAT_POST_DATEPICKER)
          : undefined;
        return values;
      },
      submitFailedObject: values => {
        return {
          notes: values.notes,
          status: 0,
        };
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
          <Status
            disabled={step === 10 && status === 2}
            statusLabel={statusLabel}
          />
          <Reason name="notes" label={t('review.review')} />
        </Col>
        <Col span={12}></Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(Onboard);
