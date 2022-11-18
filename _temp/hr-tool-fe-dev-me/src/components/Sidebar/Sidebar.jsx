import { Layout, Menu } from 'antd';
import React, { useEffect, useState, useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { Link, useLocation } from 'react-router-dom';
import { useDispatch } from 'react-redux';
import { DEFAULT_SELECTED_MENU_SIDEBAR } from '../../constants/languagePage';
import { LIST_ROUTES } from '../../constants/listRoute';
import { setIsSidebarOpened } from '../../layouts/layoutSlide';

const { SubMenu } = Menu;
const { Sider } = Layout;

function Sidebar(props) {
  const { t } = useTranslation();
  const { pathname } = useLocation();
  const dispatch = useDispatch();

  const [selectedKey, setSelectedKey] = useState('');
  const { userInfor } = useSelector(state => state.auth);

  useEffect(() => {
    if (pathname.startsWith('/plan')) {
      setSelectedKey(`/plan`);
    } else if (pathname.startsWith('/cv')) {
      setSelectedKey(`/cv`);
    } else if (pathname.startsWith('/email-history')) {
      setSelectedKey(`/email-history`);
    } else if (pathname.startsWith('/setting/email')) {
      setSelectedKey('/setting/email');
    } else if (pathname.startsWith('/setting/release')) {
      setSelectedKey('/setting/release');
    } else {
      setSelectedKey(pathname);
    }
  }, [pathname]);

  const defaultOpenKeys = useMemo(() => pathname.split('/'), []);

  const closeSidebar = e => {
    if (e.target === document.querySelector('.sidebar')) {
      dispatch(setIsSidebarOpened(false));
    }
  };

  return (
    <Sider className="sidebar" onClick={closeSidebar}>
      {userInfor.permission && (
        <Menu
          mode="inline"
          className="sidebar__menu"
          defaultOpenKeys={defaultOpenKeys}
          selectedKeys={[
            pathname.length > 1 ? selectedKey : DEFAULT_SELECTED_MENU_SIDEBAR,
          ]}
        >
          {LIST_ROUTES.map(route => {
            const { path, icon, child, title, role } = route;
            let checkRole = false;
            if (
              (role && userInfor.permission[role]?.menu) ||
              role === undefined
            ) {
              checkRole = true;
            }
            if (Array.isArray(role)) {
              role.map(e => {
                if (userInfor.permission[e]?.menu) {
                  checkRole = true;
                }
                return e;
              });
            }

            if (checkRole)
              return child.length ? (
                <SubMenu
                  className="sidebar__subMenu"
                  key={title}
                  icon={icon}
                  title={t(`sidebar.${title}`)}
                >
                  {child.map(item => {
                    if (
                      !item.role ||
                      (item.role && userInfor.permission[item.role]?.menu)
                    ) {
                      return (
                        <Menu.Item
                          icon={item.icon}
                          key={item.path}
                          onClick={() => dispatch(setIsSidebarOpened(false))}
                        >
                          <Link to={item.path}>
                            {t(`sidebar.${item.title}`)}
                          </Link>
                        </Menu.Item>
                      );
                    } else return null;
                  })}
                </SubMenu>
              ) : (
                <Menu.Item
                  icon={icon}
                  key={path}
                  onClick={() => dispatch(setIsSidebarOpened(false))}
                >
                  <Link to={path}>{t(`sidebar.${title}`)}</Link>
                </Menu.Item>
              );
            else return null;
          })}
        </Menu>
      )}
    </Sider>
  );
}

export default Sidebar;
