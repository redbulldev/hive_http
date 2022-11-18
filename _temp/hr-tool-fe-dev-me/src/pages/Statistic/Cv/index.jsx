import React from 'react';
import dashboardApi from '../../../api/dashboardApi';
import StatisticLayout from '../components/StatisticLayout';
import CvFilterForm from './CvFilterForm';

function CvStatistic() {
  return (
    <StatisticLayout
      getApi={dashboardApi.getAll}
      filterForm={CvFilterForm}
      title="cv"
    />
  );
}

export default CvStatistic;
