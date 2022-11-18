import { Col, Row } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import HRReviewApi from '../../../api/HRReviewApi';
import { requiredFields, statusLabel } from '../../../constants/probation';
import {
  FORMAT_DATEPICKER,
  FORMAT_POST_DATEPICKER,
} from '../../../constants/toInterview';
import moment from 'moment';
import { FormDatePicker } from '../../../components/Form';
import OnBoardCV from '../../../api/onBoard';
import probationApi from '../../../api/thuViec';

function Probation(props) {
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
      api: probationApi.getById,
      submitApi: probationApi.post,
      requiredFields: requiredFields,
      submitProps: {
        reasonName: 'notes',
      },
      fillData: values => {
        values.todate = values.todate
          ? moment(values?.todate, FORMAT_POST_DATEPICKER)
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
        if (values.todate) {
          values.todate = values.todate.format(FORMAT_POST_DATEPICKER);
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
            name="todate"
            label={t('probation.day')}
            disabled={isFailed}
            format={FORMAT_DATEPICKER}
            required
          />
          <Status disabled={false} statusLabel={statusLabel} />
          <Reason name="notes" label={t('review.review')} />
        </Col>
        <Col span={12}></Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(Probation);
