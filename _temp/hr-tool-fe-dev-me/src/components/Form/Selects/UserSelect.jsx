import React from 'react';
import { useSelector } from 'react-redux';
import { GeneralSelect } from '../';
import { settingUserApi2 } from '../../../api/settingUserApi';
import { currentUserSelector } from '../../../global/selectors';

export default function UserSelect({ withFullName, ...rest }) {
  const username = useSelector(currentUserSelector);

  const propsToSend = {};
  if (withFullName) {
    propsToSend.renderContent = item => {
      const { fullname, username } = item;
      const arr = [username];
      if (fullname) {
        arr.push(fullname);
      }
      return arr.join(' - ');
    };
  } else {
    propsToSend.contentKey = 'username';
  }
  return (
    <GeneralSelect
      {...rest}
      valueKey="username"
      itemsFirst={[username]}
      {...propsToSend}
      api={settingUserApi2.getAll}
    />
  );
}
