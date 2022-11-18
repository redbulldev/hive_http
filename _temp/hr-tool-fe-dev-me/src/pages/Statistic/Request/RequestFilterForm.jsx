import { Col, Form } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import FilterForm from '../../../components/Dashboard/FilterForm';
import { LevelSelect, PositionSelect } from '../../../components/Form';

function RequestFilterForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  return (
    <FilterForm form={form} {...props}>
      <Col lg={4} xs={24}>
        <PositionSelect
          form={form}
          name="position_id"
          mode="multiple"
          label={t('statistic.position')}
          selectAll
        />
      </Col>
      <Col lg={4} xs={24}>
        <LevelSelect
          form={form}
          selectAll
          mode="multiple"
          name="level_id"
          label={t('statistic.level')}
        />
      </Col>
    </FilterForm>
  );
}

export default RequestFilterForm;
