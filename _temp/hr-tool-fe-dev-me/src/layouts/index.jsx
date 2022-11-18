import { Layout } from 'antd';
import React from 'react';
import { Outlet, Navigate } from 'react-router-dom';
import Header from '../components/Header/Header';
import Sidebar from '../components/Sidebar/Sidebar';
import { getToken } from '../api/Cookie';
import { skipLogin } from '../global/constants';

const { Content } = Layout;

function MainLayout(props) {
  const token = getToken('Auth-Token');

  const redirect = () => {
    if (token || skipLogin) {
      return (
        <Layout>
          <Header />
          <Content>
            <Layout className="site-layout-background">
              <Sidebar />
              <Content className="main__content">
                <Outlet />
              </Content>
            </Layout>
          </Content>
        </Layout>
      );
    }
    return <Navigate to="/login" />;
  };
  return <>{redirect()}</>;
}

export default MainLayout;
