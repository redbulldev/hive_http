import React, { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { TableMain, useTable } from '../../../components/Table';
import { getHistoryCv } from '../../../api/historyCv';
import { cols } from '../constant/historyTable';
import { useTranslation } from 'react-i18next';

export default function HistoryTable({ justReload }) {
  const { id } = useParams();
  const { t } = useTranslation();

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: getHistoryCv,
      apiFilter: { cv_id: id },
    });

  useEffect(() => {
    fetchData();
  }, [justReload]);

  const title = t('cvDetail.history');

  return (
    items?.length > 0 && (
      <TableMain
        cols={cols}
        nth
        disableHeader
        titleLabel={title}
        items={items}
        title={title}
        fetchData={fetchData}
        filter={filter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
      />
    )
  );
}
