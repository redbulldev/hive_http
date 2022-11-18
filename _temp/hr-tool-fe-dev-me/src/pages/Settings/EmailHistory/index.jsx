import React from 'react';
import { useSelector } from 'react-redux';
import NoPermission from '../../../components/NoPermission';
import emailHistoryApi from '../../../api/emailHistoryApi';
import { Header, TableMain, useTable } from '../../../components/Table';
import EmailHistoryFilter from './components/filter';
import { cols } from './constants';
import { Button } from '../../../components/Buttons';
import { InfoCircleOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

function EmailHistory({ permission }) {
  const navigate = useNavigate();
  const { t } = useTranslation();

  const title = t('emailHistory.mainTitle');

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: emailHistoryApi.getAll,
    });

  const toDetail = id => {
    if (id) {
      navigate('/email-history/' + id);
    }
  };

  const actionButtons = [
    {
      component(record) {
        return (
          <Button
            icon={<InfoCircleOutlined />}
            onClick={() => toDetail(record?.id)}
          >
            {t('common.detail')}
          </Button>
        );
      },
    },
  ];

  const renderExcel = cols => {
    return cols.map(col => {
      if (col.dataIndex === 'sent') {
        col.render = value =>
          value ? t('emailHistory.sent') : t('emailHistory.pending');
      }
      return col;
    });
  };

  return (
    <>
      <Header title={title} buttons={[]} />
      <EmailHistoryFilter filter={filter} setFilter={setFilter} />
      <TableMain
        cols={cols}
        nth
        titleLabel={title}
        items={items}
        title={title}
        actionButtons={actionButtons}
        fetchData={fetchData}
        getApi={emailHistoryApi.getAll}
        filter={filter}
        Filter={EmailHistoryFilter}
        setFilter={setFilter}
        callback={renderExcel}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="email-history"
        onRow={record => ({
          onDoubleClick() {
            toDetail(record?.id);
          },
        })}
      />
    </>
  );
}

function EmailHistoryIndex(props) {
  const permission = useSelector(
    state => state.auth.userInfor.permission['email_history'],
  );
  return permission.view ? (
    <EmailHistory {...props} permission={permission} />
  ) : (
    <NoPermission />
  );
}

export default EmailHistoryIndex;
