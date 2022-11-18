import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { settingUserApi2 } from '../../../api/settingUserApi';
import NoPermission from '../../../components/NoPermission';
import { Header, TableMain, useTable } from '../../../components/Table';
import UserFilter from './components/UserFilter';
import UserForm from './components/UserForm';
import { cols } from './constants';

function UserSetting({ permission }) {
  const { t } = useTranslation();
  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: settingUserApi2.getAll,
      nthBy: 'username',
    });

  const title = t('user.pageHeaderTitle');
  return (
    <>
      <UserForm
        addApi={settingUserApi2.add}
        editApi={settingUserApi2.edit}
        fetchData={fetchData}
        setFilter={setFilter}
      />
      <Header title={title} addPermission={permission.add} />
      <UserFilter filter={filter} setFilter={setFilter} />
      <TableMain
        cols={cols}
        nth
        nthBy="username"
        titleLabel={title}
        items={items}
        title={title}
        idKey="username"
        fetchData={fetchData}
        deleteApi={settingUserApi2.delete}
        getApi={settingUserApi2.getAll}
        deleteManyApi={settingUserApi2.deleteMany}
        deletePermission={permission.delete}
        editPermission={permission.edit}
        filter={filter}
        Filter={UserFilter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="user-list"
      />
    </>
  );
}

export default function UserSettingIndex(props) {
  const permission = useSelector(
    state => state.auth.userInfor.permission.users,
  );

  return permission.view ? (
    <UserSetting {...props} permission={permission} />
  ) : (
    <NoPermission />
  );
}
