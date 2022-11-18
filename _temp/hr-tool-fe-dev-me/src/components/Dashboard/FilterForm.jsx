import { Col } from 'antd';
import 'moment/locale/vi';
import React, { memo } from 'react';
import { useTranslation } from 'react-i18next';
import { changeFilter } from '../../utils/changeFilterStatistic';

import { RangePicker } from '../Form';
import { Filter } from '../Table';

function FilterForm({ children, form, ...props }) {
  const { t } = useTranslation();

  return (
    <Filter {...props} form={form} changeFilter={changeFilter}>
      <Col lg={4} xs={24}>
        <RangePicker name="date" label={t('statistic.time')} />
      </Col>
      {children}
    </Filter>
  );
}
export default memo(FilterForm);
