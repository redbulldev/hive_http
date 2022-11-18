import { SearchOutlined } from '@ant-design/icons';
import { Button, Form, Input, message, Select } from 'antd';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import './searchForm.scss';
import queryString from 'query-string';
import { useLocation } from 'react-router-dom';
import { settingUserApi } from '../../../../../api/settingUserApi';
import { ROLE_URL } from '../../../../../constants/api';
import { messageAction } from '../constant';
import { useDispatch } from 'react-redux';
import { changeSearchQuery } from '../../../commonSlice/userSlice';
/**
 * @author
 * @function SearchFormUser
 **/

const SearchForm = props => {
  const { filter, searchQuery, setFilter } = props;
  const [roleActiveApi, setRoleActiveApi] = useState([]);

  const { Option } = Select;
  const { t } = useTranslation();
  const location = useLocation();
  const dispatch = useDispatch();
  const [form] = Form.useForm();
  const filterOption = (input, option) =>
    option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0;
  useEffect(() => {
    const queryParams = queryString.parse(location.search);

    form.setFieldsValue({
      role_id: queryParams.filter
        ? queryParams?.filter.split('-').map(item => +item)
        : [],
      searchQuery: queryParams.key ? queryParams.key : '',
    });

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [location, filter, searchQuery]);
  const fetchRoleActive = async () => {
    try {
      const res = await settingUserApi.getAll(ROLE_URL, {
        status: 1,
        limit: 0,
      });
      setRoleActiveApi(res.data.data);
    } catch (e) {
      message.error(t(`user.${messageAction.installErr}`));
    }
  };
  useEffect(() => {
    fetchRoleActive();

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);
  const onFinishFilter = values => {
    if (values.role_id?.length) {
      setFilter(values.role_id.join('-'));
    } else {
      setFilter('');
    }
    if (values.searchQuery?.length) {
      dispatch(changeSearchQuery(values.searchQuery.trim()));
    } else {
      dispatch(changeSearchQuery(''));
    }
  };
  return (
    <>
      <Form
        name="search"
        onFinish={onFinishFilter}
        autoComplete="off"
        layout="inline"
        className="flex-nowrap d-flex form-mobile"
        form={form}
      >
        <div>
          <span className="search__label">{t('user.role_id')}</span>
          <Form.Item name="role_id" className="search__form-items filter-role">
            <Select
              mode="multiple"
              allowClear
              showArrow
              placeholder={t('user.all')}
              maxTagCount="responsive"
              showSearch
              filterOption={filterOption}
              getPopupContainer={trigger => trigger.parentNode}
            >
              {roleActiveApi &&
                roleActiveApi.map(e => (
                  <Option value={e.id} key={e.id}>
                    {e.title}
                  </Option>
                ))}
              {props.permissionApi &&
                props.permissionApi.map(e => (
                  <Option value={e.alias} key={e.id}>
                    {e.title}
                  </Option>
                ))}
            </Select>
          </Form.Item>
        </div>

        <div>
          <span className="search__label">{t('user.keyword')}</span>
          <Form.Item name="searchQuery" className="search__form-items">
            <Input className="height-40" placeholder={t('user.inputField')} />
          </Form.Item>
        </div>
        <Form.Item className="margin-0 button-form">
          <Button
            className="width-40 height-40"
            type="primary"
            htmlType="submit"
            icon={<SearchOutlined />}
          ></Button>
        </Form.Item>
      </Form>
    </>
  );
};

export default React.memo(SearchForm);
