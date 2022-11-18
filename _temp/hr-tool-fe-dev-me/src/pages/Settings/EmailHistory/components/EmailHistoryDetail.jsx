import { Col, Form, Row } from 'antd';
import React, { useCallback, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useParams } from 'react-router-dom';
import emailHistoryApi from '../../../../api/emailHistoryApi';
import BreadcrumbForm from '../../../../components/Breadcrumb/BreadcrumbForm';
import { FormEditor, FormInput } from '../../../../components/Form';
import NoPermission from '../../../../components/NoPermission';

export default function Detail() {
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const { id } = useParams();

  const [detail, setDetail] = useState({});

  const permission = useSelector(
    state => state.auth.userInfor.permission.email_history,
  );

  const fillData = values => {
    setDetail(values);
    const fullName = values.fullname;
    const email = values.email;
    form.setFieldsValue({
      ...values,
      receiver: (fullName ? fullName + ' ' : '') + (email ? `(${email})` : ''),
    });
  };

  const breadcrumbNameMap = {
    '/email-history': t('emailHistory.mainTitle'),
    ['/email-history/' + id]: `${detail.title ? detail.title + ' ' : ''} ${
      detail.fullname || ''
    }`,
  };

  const Content = useCallback(() => {
    return (
      <Row justify="space-between">
        <Col span={9}>
          <FormInput
            name="email_title"
            label={t('emailHistory.template')}
            disabled
            placeholder={t('emailHistory.templatePlaceholder')}
          />
          <FormInput
            name="cc"
            label={t('emailTemplate.cc')}
            tag
            disabled
            placeholder={t('emailHistory.ccPlaceholder')}
          />
          <FormInput
            name="title"
            label={t('common.title')}
            disabled
            placeholder={t('emailHistory.titlePlaceholder')}
          />
          <FormInput
            name="receiver"
            label={t('emailHistory.receiver')}
            disabled
            placeholder={t('emailHistory.infoPlaceholder')}
          />
        </Col>
        <Col span={13}>
          <FormEditor
            label={t('common.content')}
            name="content"
            className="editor"
            height={350}
            disabled
          />
        </Col>
      </Row>
    );
  });

  return permission.view ? (
    <BreadcrumbForm
      form={form}
      getApi={emailHistoryApi.getById}
      fillData={fillData}
      FormContent={Content}
      breadcrumbNameMap={breadcrumbNameMap}
      cancelTitle={t('common.close')}
      extra={['cancel']}
    />
  ) : (
    <NoPermission />
  );
}
