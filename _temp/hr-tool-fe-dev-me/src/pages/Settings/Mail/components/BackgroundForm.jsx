import { Col, Form, message, Row } from 'antd';
import React, { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useParams } from 'react-router-dom';
import emailTemplatesApi from '../../../../api/emailTemplatesApi';
import BreadcrumbForm from '../../../../components/Breadcrumb/BreadcrumbForm';
import {
  CvStatusSelect,
  CvStepSelect,
  DelaySelect,
  FormEditor,
  FormInput,
  FormRadio,
  GeneralSelect,
  TagInput,
} from '../../../../components/Form';
import { CV_STEP, DEFAULT_STATUS } from '../../../../constants';
import { isAutoRadio, requiredFields, status } from '../constants';
import NoPermission from '../../../../components/NoPermission';
import { checkEmailsValidator } from '../../../../utils/validation';

export default function BackgroundForm() {
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const { id } = useParams();

  const permission = useSelector(
    state => state.auth.userInfor.permission.email,
  );

  const fillData = values => {
    form.setFieldsValue({
      ...values,
      status: values.status ?? DEFAULT_STATUS,
      isauto: values.isauto ?? DEFAULT_STATUS,
      cc: values.cc ? JSON.parse(values.cc) : undefined,
    });
  };

  const submitCondition = () => {
    const g = form.getFieldValue;

    const required = g('isauto')
      ? requiredFields
      : requiredFields.filter(item => item !== 'delay');

    const requireCondition = required.some(
      field =>
        [undefined, null, ''].includes(g(field)) || g(field)?.length === 0,
    );

    return requireCondition;
  };

  const onChangeIsAuto = () => {
    const condition = form.getFieldValue('isauto');
    if (!condition)
      form.setFields([{ name: 'delay', errors: [], value: null }]);
  };

  const breadcrumbNameMap = {
    '/setting/email': t('emailTemplate.mainTitle'),
    [id ? '/setting/email/edit/' + id : '/setting/email/create']: id
      ? t('emailTemplate.edit')
      : t('emailTemplate.addTitle'),
  };

  const catchCallback = e => {
    const msg = e?.data?.message;
    if (msg?.includes(t('emailTemplate.existStepAndStatusBackend'))) {
      message.error(t('emailTemplate.existStepAndStatusMessage'));
    }
  };

  const Content = useCallback(() => {
    return (
      <Row justify="space-between">
        <Col span={9}>
          <FormInput
            label={t('common.title')}
            placeholder={t('emailTemplate.titlePlaceholder')}
            name="title"
            min={3}
            required
          />
          <CvStepSelect label={t('cv.step')} name="cv_step" required />
          <CvStatusSelect label={t('cv.status')} name="cv_status" required />

          <FormInput
            name="cc"
            label={t('emailTemplate.cc')}
            placeholder={t('emailTemplate.ccPlaceholder')}
            existMessage={t('emailTemplate.existEmailWhenTyping')}
            tag
            rules={[checkEmailsValidator]}
          />
          <FormRadio
            label={t('emailTemplate.isAuto')}
            name="isauto"
            items={isAutoRadio}
            onChange={onChangeIsAuto}
          />
          <Form.Item noStyle shouldUpdate>
            {() => {
              const condition = form.getFieldValue('isauto');
              return (
                <DelaySelect
                  name="delay"
                  label={t('updateCv.send_later_minutes')}
                  required={condition}
                  disabled={!condition}
                />
              );
            }}
          </Form.Item>

          <FormRadio label={t('common.status')} name="status" items={status} />
        </Col>
        <Col span={13}>
          <FormEditor
            label={t('common.content')}
            name="content"
            required
            form={form}
            className="editor"
            hasSuggestions
            height={350}
          />
          <div style={{ width: '100%', color: 'rgba(0,0,0,0.4)' }}>
            <div>{t('emailTemplate.guide1')} </div>
            <div>{t('emailTemplate.guide2')} </div>
            <div>{t('emailTemplate.guide3')} </div>
          </div>
        </Col>
      </Row>
    );
  }, []);

  return (id ? permission.edit : permission.add) ? (
    <BreadcrumbForm
      form={form}
      addApi={emailTemplatesApi.add}
      editApi={emailTemplatesApi.edit}
      getApi={emailTemplatesApi.getById}
      fillData={fillData}
      FormContent={Content}
      catchCallback={catchCallback}
      submitCondition={submitCondition}
      breadcrumbNameMap={breadcrumbNameMap}
    />
  ) : (
    <NoPermission />
  );
}
