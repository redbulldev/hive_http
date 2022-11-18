import { FilterOutlined, PlusCircleFilled } from '@ant-design/icons';
import { Button, Drawer } from 'antd';
import { memo } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import requestApi from '../../api/requestApi';
import NoPermission from '../../components/NoPermission';
import { useTable } from '../../components/Table';
import { DEFAULT_PAGENUMBER } from '../../constants/requestPage';
import { hasPermission } from '../../utils/hasPermission';
import LayoutBreadcrumb from './components/LayoutBreadcrumb';
import SearchForm from './components/SearchForm';
import ContentRequestTable from './components/Table/components/TableContent';
import { changeVisibleFilterDrawer, setReloadTable } from './requestSlice';

function Request() {
  const { userInfor } = useSelector(state => state.auth);
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const { visibleFilterDrawer } = useSelector(state => state.request);
  const isFullscreenMode = useSelector(state => state.request.isFullscreenMode);
  const navigate = useNavigate();
  const breadcrumbNameMap = {
    '/request': t('sidebar.request'),
    '/request/add': t('request.createRequest'),
  };

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: requestApi.getAll,
    });

  const isReloadTable = useSelector(state => state.request.isReloadTable);

  const handleFilter = (values, type) => {
    setFilter({
      ...values,
      page: DEFAULT_PAGENUMBER,
    });
    dispatch(setReloadTable(!isReloadTable));
    if (type === 'request-mobile') dispatch(changeVisibleFilterDrawer(false));
  };

  return hasPermission(userInfor, 'request', 'view') ? (
    <section className="request">
      <main className="plan">
        <LayoutBreadcrumb
          breadcrumbNameMap={breadcrumbNameMap}
          extra={[
            <Button
              key="1"
              type="primary"
              onClick={() => dispatch(changeVisibleFilterDrawer(true))}
              className="request-filter-btn"
            >
              <FilterOutlined />
              <span className="language__addBtn">bộ lọc</span>
            </Button>,
            hasPermission(userInfor, 'request', 'add') && (
              <Button
                key="2"
                type="primary"
                onClick={() => navigate('/request/add')}
              >
                <PlusCircleFilled />
                <span className="language__addBtn">
                  {t('request.createRequest')}
                </span>
              </Button>
            ),
          ]}
          component={
            <>
              <SearchForm
                setFilter={value => handleFilter(value, 'request-desktop')}
                filter={filter}
              />
              <ContentRequestTable
                items={items}
                filter={filter}
                setFilter={setFilter}
                loadingTable={loadingTable}
                fetchData={fetchData}
                totalRecord={totalRecord}
              />
            </>
          }
        />
      </main>
      {/* Filter for mobile */}
      <Drawer
        title="Bộ lọc"
        placement="right"
        onClose={() => dispatch(changeVisibleFilterDrawer(false))}
        visible={visibleFilterDrawer}
        width="90%"
        className="request-drawer"
        getContainer={
          isFullscreenMode
            ? document.querySelector('.request__fullscreen')
            : document.body
        }
      >
        <SearchForm
          setFilter={value => handleFilter(value, 'request-mobile')}
          filter={filter}
        />
      </Drawer>
    </section>
  ) : (
    <NoPermission />
  );
}

export default memo(Request);
