import { Table, Pagination, message, Button, Modal } from 'antd';
import React, { useState } from 'react';
import TableButton from './tableButton';
import { DEFAULT_STYLE_EXCEL, pageSizeOptions } from '../../../../../constants';
import { useDispatch, useSelector } from 'react-redux';
import {
  changeSorter,
  changeFilter,
  setReloadTable,
} from '../../../commonSlice/userSlice';
import lodash from 'lodash';
import { FullScreen, useFullScreenHandle } from 'react-full-screen';
import { useTranslation } from 'react-i18next';
import { Excel } from 'antd-table-saveas-excel';
import {
  settingUserApi,
  settingUserApi2,
} from '../../../../../api/settingUserApi';

import { USER_URL } from '../../../../../constants/api';
import {
  CheckOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
} from '@ant-design/icons';
import { exportToExcel } from '../../../../../utils/exportToExcel';
import SearchForm from '../searchForm';
/**
 * @author
 * @function TableUser
 **/

const TableUser = props => {
  const [fullscreen, setFullscreen] = useState(false);
  const [selectedRowKeys, setSelectedRowKeys] = useState([]);
  const { t } = useTranslation();
  const { sorter, searchQuery } = useSelector(state => state.user);
  const dispatch = useDispatch();
  const { filter, setFilter, search } = props;
  const changeFilterSort = (filter, sorter) => {
    if (sorter.order === 'ascend') {
      dispatch(changeSorter(sorter.field + '-ASC'));
    } else if (sorter.order === 'descend') {
      dispatch(changeSorter(sorter.field + '-DESC'));
    } else {
      dispatch(changeSorter(''));
    }
    if (filter.status) {
      if (filter.status.length === 2) {
        dispatch(changeFilter(filter.status.join('-')));
      } else {
        dispatch(changeFilter(filter.status.join('')));
      }
    } else {
      dispatch(changeFilter([]));
    }
    props.setCurrent(1); // filter change auto redirect to page 1
  };
  const handleOnchangeFullscreen = state => {
    setFullscreen(state);
  };
  // config message when in fullscreen
  message.config({
    getContainer(node) {
      return node ? node : document.querySelector('.fullscreen-table');
    },
  });

  const delayFilter = lodash.debounce(changeFilterSort, 100);
  const onTableChange = (col, filter, sorter) => {
    setTimeout(() => {
      delayFilter(filter, sorter);
    }, 100);
  };
  const onPageChange = (page, pageSize) => {
    props.setCurrent(page);
    props.setPageSize(pageSize);
  };
  const onSelectChange = selectedRowKeys => {
    setSelectedRowKeys(selectedRowKeys);
  };

  const handleFullscreen = useFullScreenHandle();

  const handleExportClick = () => {
    exportToExcel(
      props.total,
      { page: 1, orderby: sorter, role_id: props.filter, key: searchQuery },
      settingUserApi2,
      exportAsExcel,
    );
  };
  const exportAsExcel = data => {
    const excel = new Excel();

    const fillCol = props.columns.filter(e => e.export && e.show);
    const newcol = JSON.parse(JSON.stringify(fillCol));
    excel.setTBodyStyle(DEFAULT_STYLE_EXCEL).setTHeadStyle(DEFAULT_STYLE_EXCEL);
    excel
      .addSheet('User')
      .addColumns(
        newcol.map(e => {
          if (e.dataIndex === 'status') {
            e.render = value => (value ? t('user.access') : t('user.lock'));
          }
          if (e?.width) {
            delete e.width;
          }
          return e;
        }),
      )
      .addDataSource(data)
      .saveAs('UsersList.xlsx');
  };

  const rowSelection = {
    selectedRowKeys,
    onChange: onSelectChange,
  };
  const confirmBox = async () => {
    confirm({
      title: (
        <div>
          {t('user.confirmMessage')}{' '}
          <span className="danger-text">
            {t('user.delete')} {selectedRowKeys.length}
          </span>{' '}
          {t('user.thisItem')}
        </div>
      ),
      icon: <ExclamationCircleOutlined />,
      getContainer: document.querySelector('.fullscreen-table'),
      content: selectedRowKeys.join(', '),
      okText: t('user.yes'),
      cancelText: t('user.no'),
      onOk: () => {
        deleteMutiple();
      },
    });
  };
  const deleteMutiple = async () => {
    try {
      const res = await settingUserApi.deleteMultiple(
        USER_URL,
        selectedRowKeys,
      );
      if (res.data.status === 'success') {
        message.success(t('user.deleteSuccess'));
        dispatch(setReloadTable());
        setSelectedRowKeys([]);
      } else {
        message.error(res.data.message);
      }
    } catch (e) {
      message.error(e.message);
    }
  };
  return (
    <FullScreen
      handle={handleFullscreen}
      className="fullscreen-table"
      onChange={handleOnchangeFullscreen}
    >
      {fullscreen && (
        <SearchForm
          filter={filter}
          setFilter={setFilter}
          searchQuery={search}
        />
      )}
      <div className="table-container">
        <div className="table__user-button">
          <div className="button__del-select">
            {props.userPermission.delete ? (
              <Button
                className="del-select__button"
                icon={<DeleteOutlined />}
                disabled={selectedRowKeys.length ? false : true}
                onClick={confirmBox}
              >
                {t('user.delSelect')}
              </Button>
            ) : (
              ''
            )}
            {selectedRowKeys.length ? (
              <Button className="count-select" icon={<CheckOutlined />} danger>
                {t('user.selected')}: {selectedRowKeys.length}
              </Button>
            ) : (
              ''
            )}
          </div>
          <TableButton
            handle={handleFullscreen}
            handleFullscreen={handleFullscreen}
            setColumns={props.setColumns}
            columns={props.columns}
            handleExport={handleExportClick}
            fullscreen={fullscreen}
            setFullscreen={setFullscreen}
          />
        </div>

        <Table
          getContainer={trigger => trigger}
          columns={props.columns.filter(e => e.show)}
          dataSource={props.data}
          rowKey="username"
          size="small"
          scroll={{ x: 900 }}
          loading={props.loading}
          onChange={onTableChange}
          pagination={false}
          rowSelection={props.userPermission.delete ? rowSelection : false}
        />
        <Pagination
          className="pagination"
          total={props.total}
          onChange={onPageChange}
          pageSize={props.pageSize}
          showQuickJumper={true}
          size="default"
          current={props.current}
          pageSizeOptions={pageSizeOptions}
          showSizeChanger={true}
        />
      </div>
    </FullScreen>
  );
};
export default React.memo(TableUser);
function confirm(config) {
  Modal.confirm(config);
}
