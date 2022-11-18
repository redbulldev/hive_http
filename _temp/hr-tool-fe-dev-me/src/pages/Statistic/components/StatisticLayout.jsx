import React, { isValidElement, useMemo } from 'react';

import { FilterOutlined } from '@ant-design/icons';
import { Drawer } from 'antd';
import { omit } from 'lodash';
import moment from 'moment';
import { default as queryString } from 'query-string';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { createDashboardColumns } from '../../../components/Dashboard/common';
import NoPermission from '../../../components/NoPermission';
import { TableMain, useTable } from '../../../components/Table';
import { DEFAULT_PAGENUMBER } from '../../../constants/requestPage';
import { changeDataStatistic } from '../../../utils/changeDataStatistic';
import Charts from '../components/Charts';
import Summary from '../components/Summary';

function StatisticLayout({
  filterForm: FilterForm,
  getApi,
  title = 'request',
}) {
  const {
    filter,
    setFilter,
    outSideItems,
    items,
    loadingTable,
    fetchData,
    totalRecord,
  } = useTable({
    getApi,
    defaultFilter: {
      from: moment().startOf('month').format('YYYY-MM-DD'),
      to: moment().endOf('month').format('YYYY-MM-DD'),
    },
  });

  const navigate = useNavigate();
  const cols = createDashboardColumns(undefined, title === 'request');
  const { userInfor } = useSelector(state => state.auth);
  const { t } = useTranslation();
  const [filterDrawer, setFilterDrawer] = useState(false);
  const checkRoleView = role => {
    if (!userInfor.permission || !userInfor.permission[role]) return false;
    return userInfor.permission[role]?.view;
  };
  const summary = outSideItems?.summary ?? {};

  const onDoubleClickRow = record => {
    const query = queryString.stringify({ request_id: record.id });
    navigate(`/cv?${query}`);
  };

  const data = summary ? changeDataStatistic(summary, items) : {};

  const filterProps = useMemo(() => {
    return {
      filter: {
        ...filter,
        date: [moment(filter.from), moment(filter.to)],
      },
      setFilter: values => {
        const newValue = omit(values, ['date']);
        setFilter({
          ...newValue,
          from: values.date
            ? moment(values.date[0]).format('YYYY-MM-DD')
            : moment().startOf('month').format('YYYY-MM-DD'),
          to: values.date
            ? moment(values.date[1]).format('YYYY-MM-DD')
            : moment().endOf('month').format('YYYY-MM-DD'),
          page: DEFAULT_PAGENUMBER,
        });

        if (filterDrawer) {
          setFilterDrawer(false);
        }
      },
    };
  }, [filter, filterDrawer]);

  const clonedFilterForm = useMemo(() => {
    if (FilterForm) {
      return <FilterForm {...filterProps} />;
    } else return null;
  }, [filterProps, FilterForm]);

  return (
    <div>
      {checkRoleView('dashboard') ? (
        <main className="statistic">
          <header className="statistic__header">
            <h1 className="statistic__title">{`${t('statistic.statistic')} ${t(
              `statistic.${title}`,
            )}`}</h1>
            <FilterOutlined
              style={{ fontSize: 22 }}
              onClick={() => setFilterDrawer(true)}
              className="mobile-filter-btn"
            />
          </header>
          {/* Filter for responsive */}
          <Drawer
            title="Bộ lọc"
            placement="right"
            onClose={() => setFilterDrawer(false)}
            visible={filterDrawer}
            width="90%"
            getContainer={() => document.querySelector('.table-fullscreen')}
          >
            {clonedFilterForm}
          </Drawer>

          <div className="statistic__body">
            {clonedFilterForm}
            <Summary summary={summary} isCv={title === 'cv'} />
            <Charts data={outSideItems} />

            {/* <Request /> */}
            <TableMain
              removeDeleteMany
              cols={cols}
              callback={createDashboardColumns}
              dataExport={data}
              items={data}
              title={t('statistic.statistic')}
              fetchData={fetchData}
              {...filterProps}
              Filter={FilterForm}
              totalRecord={totalRecord}
              loadingTable={loadingTable}
              excelName="dashboard-templates"
              scroll={{
                x: '100vw',
                y: 450,
              }}
              disablePagination
              onRow={record => {
                return {
                  onDoubleClick: _ => onDoubleClickRow(record),
                };
              }}
            />
          </div>
        </main>
      ) : (
        <NoPermission />
      )}
    </div>
  );
}

export default StatisticLayout;
