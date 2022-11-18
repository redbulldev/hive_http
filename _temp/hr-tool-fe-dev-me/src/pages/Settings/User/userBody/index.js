import {
  CheckCircleFilled,
  CloseCircleFilled,
  EllipsisOutlined,
  ExclamationCircleOutlined,
} from '@ant-design/icons';
import { Form, message, Modal, Popover } from 'antd';
import queryString from 'query-string';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useLocation, useNavigate } from 'react-router-dom';
import {
  settingUserApi,
  settingUserApi2,
} from '../../../../api/settingUserApi';
import del from '../../../../assets/images/tableIcon/del.svg';
import edit from '../../../../assets/images/tableIcon/edit.svg';
import { ROLE_URL, USER_URL } from '../../../../constants/api';
import {
  changeEdit,
  changeSearchQuery,
  changeSorter,
  changeVisibleDrawer,
  setReloadTable,
} from '../../commonSlice/userSlice';
import { UserDrawer } from '../userModal';
import { messageAction } from './constant';
import SearchForm from './searchForm';
import './userbody.scss';
import TableUser from './userTable';

/**
 * @author
 * @function UserBody
 **/

const UserBody = props => {
  const navigate = useNavigate();
  const location = useLocation();
  const searchParam = queryString.parse(location.search, {
    parseNumbers: true,
  });
  const { searchQuery, visibleDrawer, reloadTable, sorter, status } =
    useSelector(state => state.user);
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const [total, setTotal] = useState(0);
  const [pageSize, setPageSize] = useState(10);
  const [current, setCurrent] = useState(1);
  const [filter, setFilter] = useState(searchParam.filter || '');
  const [dataTable, setDataTable] = useState([]);
  const [loadingTable, setLoadingTable] = useState(true);
  const [roleActiveApi, setRoleActiveApi] = useState([]);
  // table
  const DEFAULT_COLUMN = [
    {
      title: t('user.username'),
      dataIndex: 'username',
      key: 'username',
      width: '15%',
      sorter: true,
      showSorterTooltip: {
        title: t('language.titleChangeSorter'),
      },
      show: true,
      export: true,
      render: username => {
        return <div className="text-truncate">{username}</div>;
      },
    },
    {
      title: t('user.fullname'),
      dataIndex: 'fullname',
      key: 'fullname',
      width: '22%',
      sorter: true,
      showSorterTooltip: {
        title: t('language.titleChangeSorter'),
      },
      show: true,
      export: true,
      render: fullname => {
        return <div className="text-truncate">{fullname}</div>;
      },
    },
    {
      title: t('user.role_id'),
      key: 'role_title',
      dataIndex: 'role_title',
      width: '25%',
      sorter: true,
      show: true,
      export: true,
      render: role => {
        return <div className="text-truncate">{role}</div>;
      },
    },
    {
      title: t('user.email'),
      dataIndex: 'email',
      key: 'email',
      sorter: true,
      show: true,
      showSorterTooltip: {
        title: t('language.titleChangeSorter'),
      },
      export: true,
      render: email => {
        return <div className="text-truncate">{email}</div>;
      },
    },
    {
      title: t('user.statusLabelModal'),
      dataIndex: 'status',
      key: 'status',
      align: 'center',
      width: '8%',
      render: status => {
        if (status) {
          return <CheckCircleFilled className="check-icon" />;
        } else {
          return <CloseCircleFilled className="close-icon" />;
        }
      },
      filters: [
        {
          text: t('language.active'),
          value: 1,
        },
        {
          text: t('language.locking'),
          value: 0,
        },
      ],
      show: true,
      export: true,
    },
    {
      title: t('user.actionF'),
      align: 'center',
      key: 'action',
      width: '10%',
      show: true,
      render: (text, record) => {
        return (
          <Popover
            zIndex={10}
            getPopupContainer={parent => parent.parentNode}
            content={
              <Content
                permission={props.userPermission}
                record={record}
                deleteUser={deleteUser}
                t={t}
                showDrawerEdit={showDrawerEdit}
              />
            }
            trigger="hover"
            placement="bottom"
          >
            <EllipsisOutlined
              style={{
                fontSize: '25px',
                cursor: 'pointer',
                color: 'rgba(0, 0, 0, 0,5)',
              }}
            />
          </Popover>
        );
      },
    },
  ];
  const [form] = Form.useForm();
  const [columns, setColumn] = useState(DEFAULT_COLUMN);

  const showDrawerEdit = record => {
    const role_id =
      record.role_title && record.role_id
        ? roleActiveApi?.find(item => item.id === record.role_id)?.id
        : null;
    dispatch(changeEdit(true));
    form.setFieldsValue({
      id: record.username,
      role_id: role_id,
      ...record,
    });
    dispatch(changeVisibleDrawer(true));
  };

  const fetchData = async () => {
    setLoadingTable(true);
    try {
      const data = await settingUserApi2.getAll({
        key: searchQuery,
        limit: pageSize,
        page: current,
        orderby: sorter,
        status: status,
        role_id: filter,
      });
      if (data.data.status === 'success') {
        setDataTable(data.data.data);
        setTotal(data.data.total);
        setLoadingTable(false);
      } else if (data.data.status !== 'success') {
        setLoadingTable(false);
      }
    } catch (e) {
      setLoadingTable(false);
      message.error(e.message);
    }
  };

  const deleteUser = async record => {
    try {
      const res = await settingUserApi2.delete(record.username);
      if (res.data.status === 'success') {
        dispatch(setReloadTable());
        message.success(t(`user.${messageAction.deleteSuccess}`));
      } else {
        message.warn(t(`user.${messageAction.deleteFail}`));
      }
    } catch (e) {
      message.error(t(`user.${messageAction.deleteFail}`));
    }
  };

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

  //useEff
  useEffect(() => {
    dispatch(changeSearchQuery(searchParam.key || ''));
    dispatch(changeSorter(searchParam.sorter || ''));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);
  useEffect(() => {
    let unmount = false;
    if (!unmount) {
      setLoadingTable(true);
      fetchData();
    }
    return () => {
      unmount = true;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [searchQuery, reloadTable, sorter, status, current, pageSize, filter]);
  useEffect(() => {
    const newParams = {
      key: searchQuery,
      sorter,
      status,
      current,
      pageSize,
      filter,
    };

    navigate({
      pathname: location.pathname,
      search: queryString.stringify(newParams, {
        skipNull: true,
        skipEmptyString: true,
      }),
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [searchQuery, sorter, current, status, pageSize, filter]);
  return (
    <div className="user-setting__body">
      <SearchForm
        roleApi={roleActiveApi}
        filterOption={t('user.role_id')}
        searchQuery={searchQuery}
        filter={filter}
        setFilter={setFilter}
      />
      <TableUser
        data={dataTable}
        columns={columns}
        setTotal={setTotal}
        setCurrent={setCurrent}
        setPageSize={setPageSize}
        pageSize={pageSize}
        userPermission={props.userPermission}
        current={current}
        total={total}
        loading={loadingTable}
        setColumns={setColumn}
        search={searchQuery}
        filter={filter}
        setFilter={setFilter}
      />
      <UserDrawer
        visible={visibleDrawer}
        roleApi={roleActiveApi}
        setFilter={setFilter}
        form={form}
      />
    </div>
  );
};
function confirm(config) {
  Modal.confirm(config);
}
// props.deleteUser(props.record);
const Content = props => (
  <div className="user__action">
    {props.permission.edit ? (
      <span
        onClick={e => {
          props.showDrawerEdit(props.record);
        }}
        className="pointer btn"
      >
        <img src={edit} alt="icon" className="pr-10" />
        {props.t(`user.${messageAction.edit}`)}
      </span>
    ) : (
      ''
    )}
    {props.permission.delete ? (
      <span
        className="pointer btn"
        onClick={() => {
          confirm({
            title: (
              <div>
                {props.t('user.confirmMessage')}{' '}
                <span className="danger-text">{props.t('user.delete')}</span>{' '}
                {props.t('user.thisItem')}
              </div>
            ),
            icon: <ExclamationCircleOutlined />,
            content: props.record.username,
            getContainer: document.querySelector('.fullscreen-table'),
            okText: props.t('user.yes'),
            cancelText: props.t('user.no'),
            onOk: () => {
              props.deleteUser(props.record);
            },
          });
        }}
      >
        <img src={del} alt="icon" className="pr-10" />
        {props.t(`user.${messageAction.delete}`)}
      </span>
    ) : (
      ''
    )}
  </div>
);

export default React.memo(UserBody);
