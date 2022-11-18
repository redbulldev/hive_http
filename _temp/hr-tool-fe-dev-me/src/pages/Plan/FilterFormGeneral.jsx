import { Col, DatePicker, Form } from 'antd';
import FormItem from 'antd/lib/form/FormItem';
import 'moment/locale/vi';
import React, { memo, useCallback, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { locales } from '../../constants/index';
import { Filter } from '../../components/Table';
import queryString from 'query-string';
import moment from 'moment';
import { useLocation } from 'react-router';

function FilterFormGeneral(props) {
  const { filter } = props;
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const { RangePicker } = DatePicker;
  const monthFormat = 'MM/YYYY';
  const location = useLocation();
  useEffect(() => {
    const queryParams = queryString.parse(location.search);
    form.setFieldsValue({
      group:
        filter.from && filter.to
          ? [moment(queryParams['from']), moment(queryParams['to'])]
          : null,
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [location, filter]);

  const FormContent = useCallback(() => {
    return (
      <>
        <Col lg={6} xs={24}>
          <FormItem
            name="group"
            label={t('plan.period')}
            labelCol={{ span: 24 }}
          >
            <RangePicker
              picker="month"
              format={monthFormat}
              placeholder={[t('plan.from'), t('plan.to')]}
              locale={locales.vi}
              style={{ width: '100%' }}
              getPopupContainer={trigger => trigger.parentNode}
            />
          </FormItem>
        </Col>
      </>
    );
  }, []);

  return (
    <Filter {...props} form={form} FormContent={FormContent} hasClean={false} />
  );
}

export default memo(FilterFormGeneral);
