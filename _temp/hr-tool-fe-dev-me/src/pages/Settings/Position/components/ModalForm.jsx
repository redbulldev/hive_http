import React, { useCallback } from 'react';
import { Form, message } from 'antd';
import { useSelector } from 'react-redux';
import { useTranslation } from 'react-i18next';
import { DEFAULT_STATUS } from '../../../../constants';
import GeneralDrawer from '../../../../components/Drawer/GeneralDrawer';
import DepartmentSelect from '../../../../components/Form/Selects/DepartmentSelect';
import { FormInput, FormRadio, UserSelect } from '../../../../components/Form';
import { requiredFields } from '../constants';
import { status } from '../../Mail/constants';

export default function ModalForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { initial, mode } = useSelector(state => state.drawer);

  const fillData = () => {
    form.resetFields();
    const init = mode === 'add' ? {} : initial;
    form.setFieldsValue({
      ...init,
      requestor: JSON.parse(init.requestor || '[]'),
      status: initial.status ?? DEFAULT_STATUS,
    });
  };

  const catchCallback = error => {
    if (error.data.message) {
      const msg = error.data.message;
      if (msg.includes('exists')) {
        message.error(t('position.positionExists'));
      } else {
        message.error(msg);
      }
    }
  };

  const Content = useCallback(() => {
    return (
      <>
        <DepartmentSelect
          label={t('position.department')}
          name="parent_id"
          required
        />
        <FormInput
          name="title"
          label={t('position.position')}
          required
          max={200}
          min={3}
          placeholder={t('position.titlePlaceholder')}
        />
        <UserSelect label={t('position.manager')} name="manager_id" required />
        <UserSelect
          label={t('position.requestor')}
          name="requestor"
          required
          mode="multiple"
        />
        <FormInput
          label={t('position.description')}
          name="description"
          textArea
        />
        <FormRadio label={t('common.status')} name="status" items={status} />
      </>
    );
  }, []);

  return (
    <GeneralDrawer
      {...props}
      fillData={fillData}
      form={form}
      FormContent={Content}
      catchCallback={catchCallback}
      className="position-content"
      fullscreenClassName="table-fullscreen"
      requiredFields={requiredFields}
      modal
    />
  );
}
