import { Button } from 'antd';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router';
import planApi from '../../api/planApi';
import NoPermission from '../../components/NoPermission';
import { TableMain, useTable } from '../../components/Table';
import { createPlanColumns } from '../../constants/planPage';
import LayoutBreadcrumb from '../Request/components/LayoutBreadcrumb';
import FilterFormGeneral from './FilterFormGeneral';
import moment from 'moment';
import React, { useEffect } from 'react';

function Plan() {
  const { t } = useTranslation();
  const navi = useNavigate();
  const { userInfor } = useSelector(state => state.auth);
  const checkRoleView = role => {
    if (!userInfor.permission || !userInfor.permission[role]) return false;
    return userInfor.permission[role]?.view;
  };
  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: planApi.getAll,
    });
  const handleFilter = value => {
    const newVal = {};
    if (value.group) {
      newVal['from'] = moment(value.group[0]).format('YYYY-MM');
      newVal['to'] = moment(value.group[1]).format('YYYY-MM');
    } else {
      newVal['from'] = '';
      newVal['to'] = '';
    }
    setFilter(prev => ({ ...prev, ...newVal, page: 1 }));
  };

  const Filter = () => (
    <FilterFormGeneral filter={filter} setFilter={handleFilter} />
  );
  const handleClickDetail = item => {
    const { month, year } = item;
    navi(`/plan/detail?month=${month}&year=${year}`);
  };

  const cols = [
    ...createPlanColumns(),
    {
      title: t('plan.action'),
      dataIndex: 'action',
      type: 'action',
      fixed: 'right',
      render(_, item) {
        return (
          <Button type="primary" onClick={() => handleClickDetail(item)}>
            {t('plan.detail')}
          </Button>
        );
      },
    },
  ];
  const breadcrumbNameMap = {
    '/plan': t('sidebar.plan'),
  };
  return (
    <>
      {checkRoleView('plan') ? (
        <main className="plan">
          <LayoutBreadcrumb
            breadcrumbNameMap={breadcrumbNameMap}
            component={
              <>
                <Filter />
                <TableMain
                  callback={createPlanColumns}
                  cols={cols}
                  removeDeleteMany
                  nth
                  titleLabel={t('request.request')}
                  items={items}
                  title={t('request.request')}
                  fetchData={fetchData}
                  deleteApi={planApi.delete}
                  getApi={planApi.getAll}
                  deleteManyApi={planApi.multiDelete}
                  deletePermission={() => {}}
                  editPermission={() => {}}
                  filter={filter}
                  Filter={Filter}
                  setFilter={setFilter}
                  totalRecord={totalRecord}
                  loadingTable={loadingTable}
                  excelName="general-plan"
                  onRow={record => ({
                    onDoubleClick() {
                      navi(
                        `/plan/detail?month=${record.month}&year=${record.year}`,
                      );
                    },
                  })}
                />
              </>
            }
          />
        </main>
      ) : (
        <NoPermission />
      )}
    </>
  );
}

export default Plan;
