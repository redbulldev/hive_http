import React, { memo } from 'react';
import { GeneralSelect } from '../';
import levelApi from '../../../api/levelApi';
import { settingRoleApi } from '../../../api/settingUserApi';

export default memo(function RoleSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={settingRoleApi.getAll}
    />
  );
});
