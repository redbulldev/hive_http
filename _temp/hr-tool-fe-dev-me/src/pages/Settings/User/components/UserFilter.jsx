import { Col, Form } from 'antd';
import React from 'react';
import { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { FormInput, RoleSelect } from '../../../../components/Form';
import { Filter } from '../../../../components/Table';

export default function UserFilter(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const FormContent = useCallback(() => {
    return (
      <>
        <Col span={4}>
          <RoleSelect name="role_id" label={t('role.role')} mode="multiple" />
        </Col>
        <Col span={6}>
          <FormInput
            label="Từ khóa"
            placeholder="Nhập từ khóa"
            name="keyword"
          />
        </Col>
      </>
    );
  }, []);

  return <Filter {...props} form={form} FormContent={FormContent} />;
}
