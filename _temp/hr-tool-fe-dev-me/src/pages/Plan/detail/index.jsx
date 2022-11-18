import {
  EditOutlined,
  EllipsisOutlined,
  ExclamationCircleOutlined,
  PauseOutlined,
} from '@ant-design/icons';
import { Button, message, Modal, Popover } from 'antd';
import { memo, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate, useLocation } from 'react-router';
import planApi from '../../../api/planApi';
import NoPermission from '../../../components/NoPermission';
import { TableMain, useTable } from '../../../components/Table';
import { createPlanDetailColumn } from '../../../constants/planPage';
import { hasPermission } from '../../../utils/hasPermission';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import { setDefaultForm } from '../reducer/plan-reducer';
import FilterForm from './FilterForm';
import qs from 'query-string';
import moment from 'moment';
function PlanDetail() {
  const { t } = useTranslation();
  const location = useLocation();
  const timeParams = qs.parse(location.search);
  const { filter, setFilter, items, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: planApi.getAll,
    });
  const newItems = items?.filter(item => item.status !== 4);
  useEffect(() => {
    setFilter({
      ...filter,
      month: timeParams.month || moment().format('MM'),
      year: timeParams.year || moment().format('YYYY'),
    });
  }, [timeParams.month]);
  const { userInfor } = useSelector(state => state.auth);
  const checkRoleView = role => {
    if (!userInfor.permission || !userInfor.permission[role]) return false;
    return userInfor.permission[role]?.view;
  };
  const handleStopRequest = async (id, item) => {
    try {
      const pass = item.onboard_cv;
      if (!pass) {
        await planApi.fixPlan(id, { status: 3 });
        fetchData();
      } else {
        await planApi.fixPlan(id, { status: 4, target: pass });
        fetchData();
      }

      message.success('Dừng yêu cầu thành công');
    } catch (e) {
      console.log('e :', e);
    }
  };
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const checkRoleEdit = role => {
    if (!userInfor.permission || !userInfor.permission[role]) return false;
    return userInfor.permission[role]?.edit;
  };
  const ContentAction = ({ item }) => {
    // add config to show message in fullscreen mode
    message.config({
      getContainer: node => {
        return node
          ? document.body
          : document.querySelector('.request__fullscreen');
      },
      duration: 2,
    });
    return (
      <div className="plan__action">
        <Button
          icon={<EditOutlined />}
          onClick={() => handleNavigateToPlanEdit(item)}
          disabled={!checkRoleEdit('plan')}
        >
          {t('plan.edit')}
        </Button>
        <Button
          icon={<PauseOutlined />}
          onClick={() =>
            confirm(
              `${item.id} - ${item.department_title} - ${item.position_title}`,
              item.id,
              item,
            )
          }
          disabled={!checkRoleEdit('plan')}
        >
          {t('plan.stop')}
        </Button>
      </div>
    );
  };
  const breadcrumbNameMap = {
    '/plan': t('sidebar.plan'),
    '/plan/detail': `${t('plan.detail')} ${
      timeParams.month || moment().format('MM')
    }/${timeParams.year || moment().format('YYYY')}`,
    'plan/detail/edit': 'Chinh sua ke hoach',
  };
  const confirm = (content, id, item) => {
    Modal.confirm({
      title: 'Bạn chắc chắn chứ?',
      icon: <ExclamationCircleOutlined />,
      content: `${content}`,
      okText: 'Ok',
      cancelText: 'Hủy',
      getContainer: document.querySelector('.full-screen'),
      onOk: () => handleStopRequest(id, item),
    });
  };
  const handleNavigateToPlanEdit = item => {
    navigate(`/plan/detail/edit?month=${item.month}&year=${item.year}`);
    dispatch(setDefaultForm(item));
  };
  const cols = [
    ...createPlanDetailColumn(),
    {
      title: t('plan.action'),
      dataIndex: 'action',
      show: true,
      fixed: 'right',
      align: 'center',
      width: 100,
      render: (_, record) => {
        return (
          <Popover
            zIndex={999}
            content={<ContentAction item={record} />}
            trigger={'hover'}
            placement="bottom"
            getPopupContainer={() =>
              document.querySelector('.table-fullscreen')
            }
          >
            <span className="action-icon">
              <EllipsisOutlined />
            </span>
          </Popover>
        );
      },
      type: 'action',
    },
  ];
  const Filter = () => <FilterForm filter={filter} setFilter={setFilter} />;

  return (
    <>
      {checkRoleView('plan') ? (
        <main className="plan">
          <LayoutBreadcrumb
            breadcrumbNameMap={breadcrumbNameMap}
            component={
              <>
                <FilterForm filter={filter} setFilter={setFilter} />
                <TableMain
                  className="plan__table"
                  removeDeleteMany
                  cols={cols}
                  callback={createPlanDetailColumn}
                  items={newItems}
                  title={t('plan.plan')}
                  fetchData={fetchData}
                  getApi={planApi.getAll}
                  editPermission={hasPermission(userInfor, 'plan', 'edit')}
                  filter={filter}
                  Filter={Filter}
                  setFilter={setFilter}
                  totalRecord={totalRecord}
                  loadingTable={loadingTable}
                  excelName="plan-templates"
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

export default memo(PlanDetail);
