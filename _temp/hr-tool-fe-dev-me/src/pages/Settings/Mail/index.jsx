import React from 'react';
import emailTemplatesApi from '../../../api/emailTemplatesApi';
import { TableMain, useTable } from '../../../components/Table';
import Header from '../../../components/Table/Header';
import { cols } from './constants';
import MailFilter from './components/MailFilter';
import './styles.scss';
import NoPermission from '../../../components/NoPermission';
import { useSelector } from 'react-redux';
import { useTranslation } from 'react-i18next';

function Mail({ permission }) {
  const { t } = useTranslation();

  const title = t('sidebar.email');

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: emailTemplatesApi.getAll,
    });

  const callback = columns => {
    const results = columns.map(col => {
      if (col?.dataIndex === 'isauto') {
        col.render = value => (value ? t('common.yes') : t('common.no'));
      }
      return col;
    });
    return results;
  };

  return (
    <>
      <Header
        title={title}
        addPermission={permission.add}
        addTitle={t('emailTemplate.addTitle')}
        addNavigate="/setting/email/create"
      />
      <MailFilter filter={filter} setFilter={setFilter} />
      <TableMain
        cols={cols}
        nth
        titleLabel={t('common.title')}
        items={items}
        title={title}
        fetchData={fetchData}
        deleteApi={emailTemplatesApi.delete}
        getApi={emailTemplatesApi.getAll}
        deleteManyApi={emailTemplatesApi.deleteMany}
        deletePermission={permission.delete}
        editPermission={permission.edit}
        callback={callback}
        editNavigate="/setting/email/edit/:id"
        filter={filter}
        Filter={MailFilter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="email-templates"
      />
    </>
  );
}

function MailIndex(props) {
  const permission = useSelector(
    state => state.auth.userInfor.permission.email,
  );
  return permission.view ? (
    <Mail {...props} permission={permission} />
  ) : (
    <NoPermission />
  );
}

export default MailIndex;
