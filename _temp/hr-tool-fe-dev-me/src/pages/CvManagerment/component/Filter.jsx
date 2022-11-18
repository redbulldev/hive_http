import { Col, Form, Row, DatePicker } from 'antd';
import React, { memo, useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { Filter as CommonFilter } from '../../../components/Table';
import moment from 'moment';
import {
  getPreviousMonth,
  getTwoPreviousMonth,
} from '../../../utils/getPreviousMonth';
import { DAY_FORMAT } from '../../../constants/statistic';
import {
  CvStatusSelect,
  CvStepSelect,
  FormInput,
  LevelSelect,
  PositionSelect,
  RangePicker,
  SourceSelect,
  UserSelect,
} from '../../../components/Form';
import { useSelector } from 'react-redux';
import { hasPermission } from '../../../utils/hasPermission';
import { useFetchUser } from '../../../components/Hooks/FetchApi';

function Filter({ ...props }) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { userInfor } = useSelector(state => state.auth);

  const { items: users } = useFetchUser();

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
        {/* Date Range */}
        <Col span={4}>
          <RangePicker name="daterange" label={t('cv.dateCreateTitle')} />
        </Col>
        {/* Step */}
        <Col span={4}>
          <CvStepSelect
            label={t('cv.step')}
            name="step"
            mode="multiple"
            index
          />
        </Col>
        {/* Status */}
        <Col span={4}>
          <CvStatusSelect
            label={t('cv.status')}
            name="status"
            mode="multiple"
          />
        </Col>
        {/* Progress */}
        {hasPermission(userInfor, 'users', 'view') && (
          <Col span={4}>
            <UserSelect
              name="progress"
              label={t('cv.progress')}
              mode="multiple"
              fetchedItems={users}
              storageKey="cv_progress"
            />
          </Col>
        )}
        {/* Position */}
        {hasPermission(userInfor, 'positions', 'view') && (
          <Col span={4}>
            <PositionSelect
              label={t('request.position')}
              name="position_id"
              mode="multiple"
              storageKey="cv_positions"
            />
          </Col>
        )}
        {/* Level */}
        {hasPermission(userInfor, 'level', 'view') && (
          <Col span={4}>
            <LevelSelect
              name="level_id"
              mode="multiple"
              label={t('request.level')}
              storageKey="cv_levels"
            />
          </Col>
        )}
        {/* Assignee */}
        {hasPermission(userInfor, 'users', 'view') && (
          <Col span={4}>
            <UserSelect
              name="assignee_id"
              label={t('common.assignee')}
              mode="multiple"
              fetchedItems={users}
              storageKey="cv_assignee"
            />
          </Col>
        )}
        {/* Source */}
        {hasPermission(userInfor, 'source', 'view') && (
          <Col className="searchForm__source" span={4}>
            <SourceSelect
              name="source_id"
              mode="multiple"
              label={t('cv.source')}
              storageKey="cv_sources"
            />
          </Col>
        )}
        {/* Keyword */}
        <Col span={4}>
          <FormInput
            label={t('common.keyword')}
            placeholder={t('common.keywordPlaceholder')}
            name="keyword"
          />
        </Col>
      </>
    );
  }, [users]);
  return (
    <CommonFilter
      {...props}
      form={form}
      FormContent={FormContent}
      submit={handleSearch}
      initialValues={convertDateRangeFromFilter}
    />
  );
}

export default memo(Filter);
