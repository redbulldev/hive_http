import { Col, Form } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { FormInput } from '../../../../../components/Form';
import { Filter } from '../../../../../components/Table';

export default function BasicTableFilter(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const FormContent = () => {
    return (
      <>
        <Col span={6}>
          <FormInput
            label="Từ khóa"
            placeholder="Nhập từ khóa"
            name="keyword"
            allowClear
          />
        </Col>
      </>
    );
  };

  return (
    <Filter {...props} form={form} FormContent={FormContent} hasClean={false} />
  );
}
