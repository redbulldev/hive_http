import { Col, Form } from 'antd';
import React, { useCallback } from 'react';
import {
  UserSelect,
  FormInput,
  RangePicker,
} from '../../../../components/Form';
import moment from 'moment';
import { useTranslation } from 'react-i18next';
import { Filter } from '../../../../components/Table';

export default function EmailHistoryFilter(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const handleSearch = values => {
    const dateRange = values.daterange;
    if (dateRange) {
      values.daterange = `${dateRange[0].startOf('day').unix()}-${dateRange[1]
        .endOf('day')
        .unix()}`;
    }
    props.setFilter(prev => ({ ...prev, ...values }));
  };

  const convertDateRangeFromFilter = filter => {
    const dateRange = filter.daterange;
    if (dateRange) {
      let arr = dateRange.split('-');
      arr = arr.map(value => moment.unix(value));
      return { daterange: arr };
    }
    return { daterange: undefined };
  };

  const FormContent = useCallback(() => {
    return (
      <>
        <Col span={6}>
          <RangePicker name="daterange" label={t('common.time')} />
        </Col>
        <Col span={4}>
          <UserSelect
            name="author_id"
            label={t('emailHistory.sender')}
            mode="multiple"
          />
        </Col>
        <Col span={6}>
          <FormInput
            label={t('common.keyword')}
            placeholder={t('common.keywordPlaceholder')}
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
      submit={handleSearch}
      initialValues={convertDateRangeFromFilter}
    />
  );
}
