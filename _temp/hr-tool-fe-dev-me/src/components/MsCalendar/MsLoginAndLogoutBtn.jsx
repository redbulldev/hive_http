import { LoginOutlined, LogoutOutlined } from '@ant-design/icons';
import { useIsAuthenticated, useMsal } from '@azure/msal-react';
import { Button } from 'antd';
import React from 'react';
import { loginRequest } from './authConfig';

export function MsLoginAndLogoutBtn() {
  const { instance } = useMsal();

  const isAuthenticated = useIsAuthenticated();

  const handleLogin = async () => {
    try {
      await instance.loginPopup(loginRequest);
    } catch (e) {
      console.log('e :', e);
    }
  };

  const handleLogout = async () => {
    try {
      await instance.logoutPopup();
    } catch (e) {
      console.log('e :', e);
    }
  };

  return isAuthenticated ? (
    <Button icon={<LogoutOutlined />} onClick={handleLogout}>
      Đăng xuất
    </Button>
  ) : (
    <Button icon={<LoginOutlined />} onClick={handleLogin}>
      Đăng nhập
    </Button>
  );
}
