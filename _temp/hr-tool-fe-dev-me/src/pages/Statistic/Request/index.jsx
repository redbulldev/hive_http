import dashboardApi from '../../../api/dashboardApi';
import { createDashboardColumns } from '../../../components/Dashboard/common';
import StatisticLayout from '../components/StatisticLayout';
import RequestFilterForm from './RequestFilterForm';

function RequestStatistic() {
  const cols = createDashboardColumns();

  return (
    <StatisticLayout
      filterForm={RequestFilterForm}
      getApi={dashboardApi.getAll}
    />
  );
}

export default RequestStatistic;
