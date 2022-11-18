import { Col, Form } from 'antd';
import React, { memo, useCallback, useEffect, useMemo } from 'react';
import queryString from 'query-string';
import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import {
  FormInput,
  LevelSelect,
  PositionSelect,
  UserSelect,
} from '../../../components/Form';
import PrioritySelect from '../../../components/Form/Selects/PrioritySelect';
import { Filter } from '../../../components/Table';

function FilterForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const location = useLocation();

  const defaultFilter = useMemo(() => {
    const obj = queryString.parse(location.search, {
      parseNumbers: true,
    });
    const { month, year } = obj;
    return { month, year };
  }, []);

  const FormContent = useCallback(() => {
    return (
      <>
        <Col span={3}>
          <PositionSelect
            name="position_id"
            label={`${t('request.position')}`}
            mode="multiple"
          />
        </Col>
        <Col span={3}>
          <UserSelect
            name="requestor_id"
            label={t('request.requestor')}
            mode="multiple"
          />
        </Col>
        <Col span={3}>
          <LevelSelect
            label={`${t('request.level')}`}
            name="level_id"
            mode="multiple"
            selectAll
            form={form}
          />
        </Col>
        <Col span={3}>
          <PrioritySelect
            name="priority"
            label={t('request.priority')}
            mode="multiple"
          />
        </Col>
        <Col span={3}>
          <UserSelect
            name="assignee_id"
            label={t('plan.assignee')}
            mode="multiple"
          />
        </Col>
        <Col span={3}>
          <FormInput
            placeholder={t('plan.enter_key')}
            label={t('plan.key')}
            name="keyword"
          />
        </Col>
      </>
    );
  }, []);

  return (
    <Filter
      {...props}
      form={form}
      FormContent={FormContent}
      defaultFilter={defaultFilter}
    />
  );
}

export default memo(FilterForm);
