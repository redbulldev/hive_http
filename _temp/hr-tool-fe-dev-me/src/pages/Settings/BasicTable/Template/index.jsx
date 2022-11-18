import { useSelector } from 'react-redux';
import NoPermission from '../../../../components/NoPermission';
import { Header, TableMain, useTable } from '../../../../components/Table';
import BasicTableFilter from './component/BasicTableFilter';
import ModalForm from './component/ModalForm';
import { cols } from './constants';

function BasicTable({ api, i18n, config, permission }) {
  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: api.getAll,
    });
  const Filter = () => (
    <BasicTableFilter filter={filter} setFilter={setFilter} />
  );

  const title = i18n.mainTitle;
  return (
    <>
      <ModalForm
        addApi={api.add}
        editApi={api.edit}
        fetchData={fetchData}
        i18n={i18n}
        setFilter={setFilter}
      />
      <Header title={title} addPermission={permission.add} />
      <Filter />
      <TableMain
        cols={cols}
        nth
        titleLabel={title}
        items={items}
        title={title}
        fetchData={fetchData}
        deleteApi={api.remove}
        getApi={api.getAll}
        deleteManyApi={api.removeMany}
        deletePermission={permission.delete}
        editPermission={permission.edit}
        filter={filter}
        Filter={Filter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName={config.excelSheet}
      />
    </>
  );
}

export default function BasicTableIndex(props) {
  const permission = useSelector(
    state => state.auth.userInfor.permission[props.config.permissionStateName],
  );
  return permission.view ? (
    <BasicTable {...props} permission={permission} />
  ) : (
    <NoPermission />
  );
}
