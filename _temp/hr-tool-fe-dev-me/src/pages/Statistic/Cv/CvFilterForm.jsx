import { Col, Form } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import FilterForm from '../../../components/Dashboard/FilterForm';
import { DepartmentSelect, UserSelect } from '../../../components/Form';

function CvFilterForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  return (
    <FilterForm form={form} {...props}>
      <Col lg={4} xs={24}>
        <UserSelect
          form={form}
          name="assignee_id"
          mode="multiple"
          label={t('common.assignee')}
          selectAll
        />
      </Col>
      <Col lg={4} xs={24}>
        <DepartmentSelect
          form={form}
          selectAll
          mode="multiple"
          name="department_id"
          label={t('statistic.department')}
        />
      </Col>
    </FilterForm>
  );
}

export default CvFilterForm;
