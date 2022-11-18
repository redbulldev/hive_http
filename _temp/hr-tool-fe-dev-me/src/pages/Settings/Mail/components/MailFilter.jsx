import { Col, Form } from 'antd';
import React from 'react';
import { memo } from 'react';
import { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import {
  CvStatusSelect,
  CvStepSelect,
  FormInput,
} from '../../../../components/Form';
import Filter from '../../../../components/Table/Filter';

function MailFilter(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const FormContent = useCallback(() => {
    return (
      <>
        <Col span={4}>
          <CvStepSelect label={t('cv.step')} name="cv_step" mode="multiple" />
        </Col>
        <Col span={4}>
          <CvStatusSelect
            label={t('cv.status')}
            name="cv_status"
            mode="multiple"
          />
        </Col>
        <Col span={6}>
          <FormInput
            label={t('common.keyword')}
            placeholder={t('common.keywordPlaceholder')}
            name="keyword"
          />
        </Col>
      </>
    );
  }, []);
  return <Filter {...props} form={form} FormContent={FormContent} />;
}

export default memo(MailFilter);
