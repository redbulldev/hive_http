import { ClearOutlined, SearchOutlined } from '@ant-design/icons';
import { Button, Form, Row } from 'antd';
import React, { memo, useEffect } from 'react';
import { useSelector } from 'react-redux';
import { DEFAULT_FILTER } from '../../../constants';
import { changeFilter } from '../../../utils/changeFilterStatistic';
import './style.scss';

export default memo(function Filter({
  form,
  FormContent,
  setFilter,
  filter,
  submit,
  defaultFilter,
  initialValues = () => undefined,
  hasClean = true,
  children,
}) {
  const { isFullscreen } = useSelector(state => state.common);

  useEffect(() => {
    form?.setFieldsValue({
      ...(isFullscreen ? changeFilter(filter) : filter),
      ...initialValues(filter),
    });
  }, [filter, form]);

  const handleSearch = values => {
    if (values.date) {
    }
    setFilter({ ...filter, ...values });
  };

  const Cleaner = () => {
    return (
      <div
        className="cleaner"
        onClick={() => {
          form.resetFields();
          const newFilter = {
            ...DEFAULT_FILTER,
            ...defaultFilter,
            orderby: filter.orderby,
          };
          if (filter.orderby) newFilter.orderby = filter.orderby;
          setFilter(newFilter);
        }}
      >
        <ClearOutlined
          style={{
            cursor: 'pointer',
            fontSize: 16,
            margin: 'auto',
          }}
        />
      </div>
    );
  };

  return (
    <div className="table-filter">
      <Form form={form} onFinish={submit || handleSearch} layout="vertical">
        <Row className="field-row" gutter={[10, 10]}>
          {FormContent && <FormContent />}
          {children}
          {hasClean && <Cleaner />}
          <Button
            type="primary"
            htmlType="submit"
            icon={<SearchOutlined />}
          ></Button>
        </Row>
      </Form>
    </div>
  );
});
