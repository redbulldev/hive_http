import { Col, DatePicker, Form, Row, Select } from 'antd';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import toInterviewApi from '../../../api/toInterviewApi';
import { requiredFields } from '../../../constants/toInterview';
import withStepFrame from './withStepFrame';
import moment from 'moment';
import { UserSelect } from '../../../components/Form';
import { DATE_TIME_FORMAT } from '../../../constants';

const { Option } = Select;

function PreInterview(props) {
  const {
    form,
    Frame,
    FormInput,
    FormTextArea,
    Reason,
    Status,
    isFailed,
    setConfig,
    step,
    status,
    cv,
  } = props;
  const { t } = useTranslation();

  useEffect(() => {
    setConfig({
      api: toInterviewApi.getById,
      submitApi: toInterviewApi.post,
      requiredFields: requiredFields,
      fillData: item => {
        return {
          appoint_date: item?.appoint_date
            ? moment.unix(item?.appoint_date)
            : undefined,
          interviewer_id: item?.interviewer_id || undefined,
        };
      },
      catchFetchData: () => {
        form.setFieldsValue({
          interviewer_id: cv?.interviewer_id,
        });
      },
      submitObject: values => {
        if (values.appoint_date) {
          values.appoint_date = values.appoint_date.unix();
        }
        return values;
      },
    });
  }, []);

  return (
    <Frame>
      <Row gutter={20}>
        <Col span={12}>
          <UserSelect
            name="interviewer_id"
            label={t('updateCv.interviewer')}
            disabled={isFailed}
          />
          <Form.Item
            name="appoint_type"
            label={t('tointerview.method')}
            style={{ width: '100%' }}
          >
            <Select
              disabled={isFailed}
              allowClear
              placeholder={t('tointerview.methodPlaceholder')}
            >
              <Option value={1}>{t('tointerview.online')} </Option>
              <Option value={0}>{t('tointerview.offline')} </Option>
            </Select>
          </Form.Item>
          <Form.Item label={t('tointerview.time')} name="appoint_date">
            <DatePicker
              disabled={isFailed}
              allowClear={true}
              dropdownClassName="time"
              style={{ width: '100%' }}
              format={DATE_TIME_FORMAT}
              minuteStep={15}
              showTime={{ format: 'HH:mm' }}
              showNow={false}
            />
          </Form.Item>
          <FormInput
            name="appoint_place"
            label={t('tointerview.location')}
            placeholder={t('tointerview.locationPlaceholder')}
            min={3}
          />
        </Col>
        <Col span={12}>
          <FormInput
            name="appoint_link"
            label={t('tointerview.appointLink')}
            placeholder={t('updateCv.checklistPlaceholder')}
            max={2100}
          />
          <Status step={step} status={status} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(PreInterview);
