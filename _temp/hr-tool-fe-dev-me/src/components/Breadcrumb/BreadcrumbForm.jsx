import { SaveOutlined } from '@ant-design/icons';
import { Form, message } from 'antd';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useNavigate, useParams } from 'react-router-dom';
import LayoutBreadcrumb from '../../pages/Request/components/LayoutBreadcrumb';
import { SubmitBtn } from '../Form';
import { CancelWhiteButton } from '../../components/Buttons';

export default function BreadcrumbForm({
  breadcrumbNameMap,
  onFinish,
  extra = ['submit', 'cancel'],
  form,
  requiredFields,
  cancelTitle,
  getApi,
  addApi,
  editApi,
  fillData,
  submitCondition,
  FormContent,
  catchCallback,
}) {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { id } = useParams();

  const [submitLoading, setSubmitLoading] = useState(false);

  const handleBack = () => {
    navigate(-1);
  };

  const definedButtons = {
    submit: (
      <SubmitBtn
        form={form}
        loading={submitLoading}
        requiredFields={requiredFields}
        icon={<SaveOutlined />}
        condition={submitCondition}
      >
        {t('common.save')}
      </SubmitBtn>
    ),
    cancel: (
      <CancelWhiteButton
        onClick={handleBack}
        style={{ textTransform: 'uppercase' }}
      >
        {cancelTitle || t('common.cancel')}
      </CancelWhiteButton>
    ),
  };

  extra = extra.map(item => {
    if (typeof item === 'string') return definedButtons[item];
    return item;
  });

  // Get data
  useEffect(async () => {
    if (id) {
      try {
        const res = await getApi(id);
        fillData(res.data.data);
      } catch (e) {
        console.log('e :', e);
      }
    } else {
      fillData({});
    }
  }, []);

  const onFinishDefault = async values => {
    if (!id && addApi) {
      await addApi(values);
    }
    if (editApi && id) {
      await editApi(id, values);
    }
  };

  const onFinishForm = async values => {
    setSubmitLoading(true);
    try {
      for (let key in values) {
        if (typeof values[key] === 'string') values[key] = values[key].trim();
      }
      const finish = onFinish || onFinishDefault;
      await finish(values);
      setSubmitLoading(false);

      let text = '';
      if (breadcrumbNameMap) {
        for (let key in breadcrumbNameMap) {
          const value = breadcrumbNameMap[key];
          if (id) {
            if (/[0-9]/.test(key)) {
              text = value;
            }
          } else {
            if (!/[0-9]/.test(key)) {
              text = value;
            }
          }
        }
      }
      message.success(`${text} ${t('common.success')}`);
      handleBack();
    } catch (e) {
      setSubmitLoading(false);
      if (!e.status) message.error(t('typework.networkError'));
      if (catchCallback) catchCallback(e);
      console.log(e);
    }
  };

  const onFieldsChange = () => {
    // console.log(form.getFieldsValue());
  };

  return (
    <Form
      form={form}
      layout="vertical"
      onFinish={onFinishForm}
      className="standard-form"
      onFieldsChange={onFieldsChange}
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbNameMap}
        extra={extra}
        component={<FormContent />}
      />
    </Form>
  );
}
