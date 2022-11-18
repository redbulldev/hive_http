import { Table } from 'antd';
import React, { memo, useState } from 'react';

import { useTranslation } from 'react-i18next';
import { CONFIG_PAGINATION } from '../../../constants';

import { useDispatch, useSelector } from 'react-redux';
import UtilitiesBars from './components/UtilitiesBars';
import './style.scss';

import { FullScreen, useFullScreenHandle } from 'react-full-screen';
import { useActionColumn, useTableCols } from '../';
import { setIsFullscreen } from '../../../app/common';
import { onChangeTable } from '../constants';

const TableMain = ({
  removeDeleteMany = true,
  disableCheckbox = false,
  cols,
  nth,
  nthBy,
  items,
  titleLabel,
  idKey,
  filter,
  totalRecord,
  Filter,
  handleFilter,
  loadingTable,
  setFilter,
  fetchData,
  callback,
  buttons,
  actionButtons,
  deleteApi,
  deletePermission,
  deleteManyApi,
  deleteContentKey,
  getApi,
  editPermission,
  editNavigate,
  excelName,
  title,
  scroll,
  onRow,
  dataExport,
  disablePagination,
  disableHeader = false,
}) => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  // Delete many
  const [selects, setSelects] = useState([]);
  const rowSelection = {
    selectedRowKeys: selects,
    onChange: values => setSelects(values),
    getCheckboxProps: disableCheckbox ? disableCheckbox : () => {},
  };
  // Actions have delete and edit
  const { ActionContent } = useActionColumn({
    editPermission,
    deletePermission,
    deleteApi,
    fetchData,
    selects,
    setSelects,
    deleteContentKey,
    title,
    idKey,
    actionButtons,
    editNavigate,
  });

  // Columns
  const { columns, setColumns } = useTableCols({
    cols,
    nth,
    titleLabel,
    filter,
    ActionContent,
  });
  // Fullscreen
  const { isFullscreen } = useSelector(state => state.common);
  const handleFullScreen = useFullScreenHandle();
  const handleOnchangeFullscreen = state => {
    dispatch(setIsFullscreen(state));
  };

  return (
    <div className="box-list table-main">
      <div className="box-shadow">
        <FullScreen
          handle={handleFullScreen}
          className="table-fullscreen"
          onChange={handleOnchangeFullscreen}
        >
          {isFullscreen && (
            <Filter
              filter={filter}
              setFilter={setFilter}
              submit={handleFilter}
            />
          )}
          {!disableHeader && (
            <UtilitiesBars
              selects={selects}
              removeDeleteMany={removeDeleteMany}
              deleteContentKey={deleteContentKey}
              nth={nth}
              dataExport={dataExport}
              filter={{ ...filter, limit: totalRecord }}
              columns={columns}
              setColumns={setColumns}
              callback={callback}
              idKey={idKey}
              nthBy={nthBy}
              handleFullScreen={handleFullScreen}
              fetchData={fetchData}
              totalRecord={totalRecord}
              setSelects={setSelects}
              items={items}
              getApi={getApi}
              buttons={buttons}
              deleteManyApi={deleteManyApi}
              deletePermission={deletePermission}
              excelName={excelName}
            />
          )}
          <Table
            getPopupContainer={node => node.parentNode}
            dataSource={items}
            columns={columns.filter(e => e.show)}
            loading={loadingTable}
            onChange={onChangeTable(filter, setFilter, setSelects, nth, nthBy)}
            rowSelection={
              !removeDeleteMany && deletePermission ? rowSelection : undefined
            }
            rowKey={idKey || 'id'}
            locale={{
              triggerDesc: t('typework.triggerDesc'),
              triggerAsc: t('typework.triggerAsc'),
              cancelSort: t('typework.cancelSort'),
            }}
            pagination={
              !disablePagination
                ? {
                    ...CONFIG_PAGINATION,
                    pageSize: filter.limit,
                    total: totalRecord,
                    current: filter.page,
                  }
                : false
            }
            scroll={scroll || { x: 'max-content' }}
            onRow={onRow}
          />
        </FullScreen>
      </div>
    </div>
  );
};

export default memo(TableMain);
