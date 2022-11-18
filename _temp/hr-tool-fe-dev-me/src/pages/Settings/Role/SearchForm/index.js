import { Col, Form } from 'antd';
import React, { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router';
import '../../User/userBody/searchForm/searchForm.scss';
import { FormInput } from '../../../../components/Form';
import { Filter } from '../../../../components/Table';

function Search(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const FormContent = useCallback(() => {
    return (
      <>
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

  return (
    <Filter {...props} form={form} FormContent={FormContent} hasClean={false} />
  );
}

export default Search;
