import { message } from 'antd';
import { useEffect, useState } from 'react';
import { useSelector } from 'react-redux';
import { settingUserApi, userPermissionApi } from '../../../api/settingUserApi';
import NoPermission from '../../../components/NoPermission';
import Search from './SearchForm';

import { useTranslation } from 'react-i18next';
import { Header, TableMain, useTable } from '../../../components/Table';
import { hasPermission } from '../../../utils/hasPermission';
import '../User/userBody/userbody.scss';
import ModalForm from './components/ModalForm';
import { cols } from './constants';

function Role() {
  const { userInfor } = useSelector(state => state.auth);
  const { t } = useTranslation();
  const [permissionApi, setPermissionApi] = useState([]);

  const fetchPermission = async () => {
    try {
      const res = await userPermissionApi.getAll({
        status: 1,
        limit: 0,
      });
      setPermissionApi(res.data.data);
    } catch (e) {
      message.error(e.message);
    }
  };

  // useEff
  useEffect(() => {
    fetchPermission();
  }, []);

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: settingUserApi.getAll,
    });

  return userInfor.permission.role.view ? (
    <div>
      <Header
        title={t('role.roleTitle')}
        addPermission={userInfor.permission?.role?.add}
      />
      <ModalForm
        permissionApi={permissionApi}
        addApi={settingUserApi.create}
        editApi={settingUserApi.edit}
        setFilter={setFilter}
        fetchData={fetchData}
      />
      <Search setFilter={setFilter} filter={filter} />
      <TableMain
        cols={cols}
        titleLabel={t('role.role')}
        nth
        items={items}
        title={t('role.role')}
        fetchData={fetchData}
        deleteApi={settingUserApi.delete}
        getApi={settingUserApi.getAll}
        deleteManyApi={settingUserApi.multiDelete}
        deletePermission={hasPermission(userInfor, 'role', 'delete')}
        editPermission={hasPermission(userInfor, 'role', 'edit')}
        filter={filter}
        Filter={Search}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="role-templates"
      />
    </div>
  ) : (
    <NoPermission />
  );
}

export default Role;
