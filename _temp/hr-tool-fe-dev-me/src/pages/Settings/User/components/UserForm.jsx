import { Form, message } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import GeneralDrawer from '../../../../components/Drawer/GeneralDrawer';
import { FormInput, RoleSelect } from '../../../../components/Form';
import { DEFAULT_STATUS } from '../../../../constants';
import { rulesValidateEmail } from '../../../../utils/validation';
import Status from '../../components/Status';
import { requiredFields } from '../constants';

export default function UserForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { initial, mode } = useSelector(state => state.drawer);

  const catchCallback = e => {
    const msg = e?.data?.message;
    const more = e?.data?.more;
    if (msg?.includes('exists') || more?.includes('Duplicate')) {
      message.error(t('user.updateWarn'));
    }
  };

  const Content = () => {
    return (
      <>
        <FormInput
          name="fullname"
          label={t('user.fullname')}
          placeholder={t('user.nameplace')}
          min={3}
          required
        />
        <FormInput
          name="username"
          label={t('user.username')}
          placeholder={t('user.usernameplace')}
          min={3}
          disabled={mode === 'edit'}
          required
          rules={[
            {
              validator(_, value) {
                value = value?.trim();
                if (value && value.includes(' '))
                  return Promise.reject(
                    new Error(t('user.noWhitespaceUsername')),
                  );
                return Promise.resolve();
              },
            },
          ]}
        />
        <FormInput
          name="email"
          label={t('user.email')}
          placeholder={t('user.emailplace')}
          rules={rulesValidateEmail()}
          required
        />
        <RoleSelect name="role_id" label={t('user.role_id')} required />
        <Status />
      </>
    );
  };

  const fillData = () => {
    form.resetFields();
    const init = mode === 'add' ? {} : initial;
    form.setFieldsValue({
      ...init,
      status: initial.status ?? DEFAULT_STATUS,
    });
  };

  return (
    <GeneralDrawer
      {...props}
      fillData={fillData}
      form={form}
      idKey="username"
      FormContent={Content}
      catchCallback={catchCallback}
      className="mail-content"
      fullscreenClassName="table-fullscreen"
      requiredFields={requiredFields}
      modal
    />
  );
}
