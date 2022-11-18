import { PlusCircleFilled } from '@ant-design/icons';
import { Button } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { TableMain, useTable } from '../../../components/Table';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import { breadcrumbNameMap, cols } from './constant';
import releaseApi from '../../../api/releaseApi';
import ReleaseForm from './components/ReleaseForm';
import { useDispatch } from 'react-redux';
import {
  setIsOpenedDrawer,
  setModeTextDrawer,
} from '../../../components/Drawer/slice/drawer';
import './styles.scss';

const Table = () => {
  const { t } = useTranslation();

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: releaseApi.getAll,
    });
  return (
    <>
      <ReleaseForm
        addApi={releaseApi.add}
        editApi={releaseApi.edit}
        fetchData={fetchData}
        setFilter={setFilter}
      />
      <TableMain
        cols={cols}
        items={items}
        fetchData={fetchData}
        deleteApi={releaseApi.delete}
        getApi={releaseApi.getAll}
        title={'Phiên bản'}
        filter={filter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="version-list"
      />
    </>
  );
};

export default function ReleaseConfig() {
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const handleAdd = () => {
    dispatch(setIsOpenedDrawer(true));
    dispatch(
      setModeTextDrawer({
        btn: `${t('common.create')}`,
        title: `${t('common.create')} phiên bản`,
      }),
    );
  };
  return (
    <LayoutBreadcrumb
      breadcrumbNameMap={breadcrumbNameMap}
      className={'release-config'}
      extra={[
        <Button type="primary" icon={<PlusCircleFilled />} onClick={handleAdd}>
          TẠO PHIÊN BẢN
        </Button>,
      ]}
      component={<Table />}
    />
  );
}
