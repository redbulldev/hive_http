import { LogoutOutlined } from '@ant-design/icons';
import { Layout } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { Link, useNavigate } from 'react-router-dom';
import { removeToken } from '../../api/Cookie';
import logo from '../../assets/images/header/logo.svg';
import UserIcon from '../../assets/images/header/Setting.svg';
import Bars from '../../assets/images/header/bars.svg';
import { setIsSidebarOpened } from '../../layouts/layoutSlide';

function Header() {
  const { Header } = Layout;
  const { userInfor } = useSelector(state => state.auth);
  const isSidebarOpened = useSelector(state => state.layout.isSidebarOpened);
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const navi = useNavigate();
  const handleLogout = () => {
    removeToken('Auth-Token');
    removeToken('Refresh-Token');
    navi('/login');
  };

  return (
    <Header className="header">
      <button
        onClick={() => dispatch(setIsSidebarOpened(!isSidebarOpened))}
        className="header__bars"
      >
        <img src={Bars} alt="bars" />
      </button>

      <div className="header__logo">
        <Link to="/">
          <img src={logo} alt="logo" />
          <span>{t('header.hrtool')}</span>
        </Link>
      </div>

      <div className="header__add">
        <div className="header__add--user user">
          <a className="user__info" href="#!">
            <img src={UserIcon} alt="UserIcon" />
            <span>{userInfor.username}</span>
          </a>
        </div>
        <div className="header__add--actions">
          <a href="#!" onClick={handleLogout}>
            <LogoutOutlined />
            <span className="header__logout-text"> {t('header.logout')}</span>
          </a>
        </div>
      </div>
    </Header>
  );
}

export default Header;
