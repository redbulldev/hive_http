import { Col, Form, Select } from 'antd';
import React, { memo, useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import {
  FormInput,
  LevelSelect,
  PositionSelect,
  UserSelect,
} from '../../../components/Form';
import PrioritySelect from '../../../components/Form/Selects/PrioritySelect';
import {
  filterOption,
  LIST_REQUEST_STATUS,
} from '../../../constants/requestPage';
import { Filter } from '../../../components/Table';

const { Option } = Select;

function SearchForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const FormContent = useCallback(() => {
    return (
      <>
        <Col className="searchForm__position" md={6} xl={3}>
          <PositionSelect
            label={t('request.position')}
            name="position_id"
            mode="multiple"
          />
        </Col>
        <Col className="searchForm__requestor" md={6} xl={3}>
          <UserSelect
            label={t('request.requestor')}
            name="requestor_id"
            mode="multiple"
          />
        </Col>
        <Col className="searchForm__level" md={6} xl={3}>
          <LevelSelect
            label={t('request.level')}
            name="level_id"
            mode="multiple"
          />
        </Col>
        <Col className="searchForm__priority" md={6} xl={3}>
          <PrioritySelect
            label={t('request.priority')}
            name="priority"
            mode="multiple"
          />
        </Col>
        <Col className="searchForm__status" md={6} xl={3}>
          <Form.Item name="status" label={t('request.status')}>
            <Select
              mode="multiple"
              className="request__select--input"
              showArrow
              getPopupContainer={trigger => trigger.parentNode}
              maxTagCount="responsive"
              placeholder={t('request.placeholderForRequestForm')}
              filterOption={filterOption}
            >
              {LIST_REQUEST_STATUS.map(req => (
                <Option key={req.id} value={req.value}>
                  {req.text}
                </Option>
              ))}
            </Select>
          </Form.Item>
        </Col>
        <Col className="searchForm__searchInput" md={6} xl={3}>
          <FormInput
            label={t('request.search')}
            name="keyword"
            placeholder={t('request.requestSearchPlaceholder')}
          />
        </Col>
      </>
    );
  }, []);

  return <Filter {...props} form={form} FormContent={FormContent} />;
}

export default memo(SearchForm);
