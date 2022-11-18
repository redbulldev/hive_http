import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import {
  DATE_FORMAT_BACKEND,
  requiredFields,
  statusLabel,
} from '../../../constants/hrInterview';
import { Col, DatePicker, Form, Row } from 'antd';
import { DATE_FORMAT } from '../../../constants';
import moment from 'moment';
import hrInterviewApi from '../../../api/hrInterviewApi';

function HrInterview(props) {
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
      api: hrInterviewApi.getByID,
      submitApi: hrInterviewApi.post,
      requiredFields: requiredFields,
      fillData: item => {
        return {
          onboard: item?.onboard
            ? moment(item?.onboard, DATE_FORMAT_BACKEND)
            : undefined,
        };
      },
      submitObject: values => {
        if (values.onboard) {
          values.onboard = values.onboard.format(DATE_FORMAT_BACKEND);
        }
        return values;
      },
    });
  }, []);

  // Begin: Money Handle
  const convertToNumberFormat = s => (s + '').replace(/\./g, '');

  const convertToVietNamMoneyFormat = str => {
    str += '';
    const len = str.length;
    let newStr = '';
    for (let i = len - 1; i >= 0; i--) {
      if ((i - len) % 3 === 0 && i !== 0) newStr = `.${str[i]}` + newStr;
      else newStr = str[i] + newStr;
    }
    return newStr;
  };

  const convertToMoneyFormat = s => {
    if (/[0-9]{1} $/.test(s)) return s;
    s = convertToNumberFormat(s.replace(/ /g, ''));
    if (/^\d+$/.test(s)) return convertToVietNamMoneyFormat(s);
    if (/^\d+-\d+$/.test(s)) {
      const moneys = s.split('-');
      return (
        convertToVietNamMoneyFormat(moneys[0]) +
        ' - ' +
        convertToVietNamMoneyFormat(moneys[1])
      );
    }

    return false;
  };

  const onChangeSalary = (e, field) => {
    let newStr = e.target.value;
    let str = convertToMoneyFormat(newStr);
    if (str) newStr = str;
    form.setFieldsValue({
      [field]: newStr,
    });
  };
  // End: Money Handle
  const disabledDate = dateToChoose => {
    return dateToChoose < moment().subtract(1, 'day').endOf('day');
  };
  return (
    <Frame title={t(`hrinterview.title`)}>
      <Row gutter={20}>
        <Col span={12}>
          <FormTextArea
            name="notes"
            label={t(`hrinterview.general`)}
            rows={5}
          />
          <FormTextArea
            name="experience"
            label={t(`hrinterview.skill`)}
            rows={5}
          />
          <FormInput
            name="linkrecord"
            label={t(`hrinterview.record`)}
            placeholder={t('tointerview.linkPlaceholder')}
          />
        </Col>
        <Col span={12}>
          <FormInput
            name="salary_now"
            label={t('hrinterview.currentSalary')}
            onChange={e => onChangeSalary(e, 'salary_now')}
            placeholder={t('hrinterview.currentSalaryPlaceholder')}
          />
          <FormInput
            name="salary_want"
            label={t('hrinterview.wishSalary')}
            onChange={e => onChangeSalary(e, 'salary_want')}
            placeholder={t('hrinterview.currentSalaryPlaceholder')}
          />
          <Form.Item
            name="onboard"
            label={t('hrinterview.onboard')}
            rules={[
              {
                validator(_, value) {
                  if (!value) return Promise.resolve();
                  if (
                    moment().isBefore(value) ||
                    moment().format(DATE_FORMAT) === value.format(DATE_FORMAT)
                  )
                    return Promise.resolve();
                  else
                    return Promise.reject(
                      new Error(t('hrinterview.futureTime')),
                    );
                },
              },
            ]}
          >
            <DatePicker
              disabled={isFailed}
              format={DATE_FORMAT}
              disabledDate={disabledDate}
              style={{ width: '100%' }}
              placeholder={t('hrinterview.onboardPlaceholder')}
            />
          </Form.Item>
          <Status step={step} status={status} statusLabel={statusLabel} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(HrInterview);
