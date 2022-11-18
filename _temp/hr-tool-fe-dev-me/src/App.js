import { LoadingOutlined } from '@ant-design/icons';
import { Spin, Statistic } from 'antd';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { Route, Routes, useLocation, useNavigate } from 'react-router-dom';
import {
  getRefreshToken,
  removeToken,
  setCookieRefreshToken,
  setToken,
} from './api/Cookie';
import { postRefeshToken } from './api/userApi';
import './App.css';
import logo from './assets/images/header/logo.svg';
import './assets/scss/app.scss';
import { REFRESH_TOKEN_TIME_UNIT } from './constants/auth';
import MainLayout from './layouts';
import ForgotPassword from './pages/Auth/ForgotPassword';
import Login from './pages/Auth/Login';
import { setLogin } from './pages/Auth/reducer/auth';
import CvManagerment from './pages/CvManagerment';
import ModalSendEmail from './pages/CvManagerment/component/ModalSendEmail';
import NewAdd from './pages/CvManagerment/component/NewAdd';
import UpdateCv from './pages/CvManagerment/component/UpdateCv';
import DetailCV from './pages/CvManagerment/component/Detail';
import NotFound from './pages/NotFound';
import Plan from './pages/Plan';
import PlanDetail from './pages/Plan/detail';
import EditPlanForm from './pages/Plan/detail/EditPlan';
import Request from './pages/Request';
import AddRequest from './pages/Request/components/AddRequest';
import DetailRequest from './pages/Request/components/DetailRequest';
import EditRequest from './pages/Request/components/EditRequest';
import Department from './pages/Settings/BasicTable/Department';
import Language from './pages/Settings/BasicTable/Language';
import Level from './pages/Settings/BasicTable/Level';
import Source from './pages/Settings/BasicTable/Source';
import Template from './pages/Settings/BasicTable/Template';
import TypeWork from './pages/Settings/BasicTable/TypeWork';
import Common from './pages/Settings/Common';
import EmailHistory from './pages/Settings/EmailHistory';
import EmailHistoryDetail from './pages/Settings/EmailHistory/components/EmailHistoryDetail';
import Mail from './pages/Settings/Mail';
import BackgroundForm from './pages/Settings/Mail/components/BackgroundForm';
import Position from './pages/Settings/Position/Position';
import Role from './pages/Settings/Role';
import User from './pages/Settings/User';
import RequestStatistic from './pages/Statistic/Request';
import CvStatistic from './pages/Statistic/Cv';
import ReleaseView from './pages/Settings/Release/ReleaseView';
import ReleaseConfig from './pages/Settings/Release/ReleaseConfig';
import Reminder from './pages/Reminder';
import { skipLogin } from './global/constants';

function App() {
  const [loading, setLoading] = useState(true);
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const location = useLocation();
  const navi = useNavigate();
  const { userInfor } = useSelector(state => state.auth);

  const handleNavigateToLogin = () => {
    setLoading(false);
    if (location.pathname !== '/login') {
      localStorage.setItem('triedLink', location.pathname);
    }
    if (!skipLogin) {
      navi('/login');
    }
  };

  const sendRequest = async () => {
    const refreshToken = getRefreshToken('Refresh-Token');
    if (refreshToken) {
      try {
        const res = await postRefeshToken({ refresh_token: refreshToken });
        dispatch(setLogin(res.data.data));
        setToken(res.data.data.access_token);
        setCookieRefreshToken(res.data.data.refresh_token);
        setTimeout(() => {
          setLoading(false);
        }, 300);
        setTimeout(async () => {
          await sendRequest();
        }, res.data.data.expires_in * REFRESH_TOKEN_TIME_UNIT);
      } catch (e) {
        console.log('e :', e);
        const msg = e?.data?.message;
        if (msg) {
          if (msg.includes('permission')) {
            removeToken('Auth-Token');
            removeToken('Refresh-Token');
          }
        }
        handleNavigateToLogin();
      }
    } else {
      handleNavigateToLogin();
    }
  };

  useEffect(() => {
    localStorage.removeItem('triedLink');
    sendRequest();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const checkPerm = role => {
    if (!userInfor.permission || !userInfor.permission[role]) return false;
    return userInfor.permission[role]?.menu;
  };

  const antIcon = <LoadingOutlined style={{ fontSize: 35 }} spin />;
  return (
    <>
      {loading && (
        <div className="box-loading">
          <div className="load">
            <img src={logo} alt="logo" />
            <h1>{t('header.hrtool')}</h1>
            <Spin spinning={loading} indicator={antIcon}></Spin>
          </div>
        </div>
      )}
      {!loading && (
        <Routes>
          <Route path="/" element={<MainLayout />}>
            {/* Default page */}
            {checkPerm('dashboard') && <Route index element={<Statistic />} />}
            {/* Statistic */}
            {checkPerm('dashboard') && (
              <Route path="statistic">
                <Route path="request" element={<RequestStatistic />} />
                <Route path="cv" element={<CvStatistic />} />
              </Route>
            )}
            {/* Request */}
            {checkPerm('request') && (
              <Route path="request" element={<Request />} />
            )}
            {checkPerm('request') && (
              <Route path="request/add" element={<AddRequest />} />
            )}
            {checkPerm('request') && (
              <Route path="request/edit/:id" element={<EditRequest />} />
            )}
            {checkPerm('request') && (
              <Route path="request/detail/:id" element={<DetailRequest />} />
            )}
            {/* Plan */}
            {checkPerm('plan') && <Route path="plan" element={<Plan />} />}
            {checkPerm('plan') && (
              <Route path="plan/detail" element={<PlanDetail />} />
            )}
            {checkPerm('plan') && (
              <Route path="plan/detail/edit" element={<EditPlanForm />} />
            )}
            {/* Email history */}
            {checkPerm('email_history') && (
              <Route path="email-history" element={<EmailHistory />} />
            )}
            {checkPerm('email_history') && (
              <Route
                path="email-history/:id"
                element={<EmailHistoryDetail />}
              />
            )}
            {/* Reminder */}
            <Route path="reminder" element={<Reminder />} />
            {/* Cv */}
            {checkPerm('cv') && <Route path="cv" element={<CvManagerment />} />}
            {checkPerm('cv') && <Route path="cv/:id" element={<DetailCV />} />}
            {checkPerm('cv') && <Route path="cv/add" element={<NewAdd />} />}
            {checkPerm('cv') && (
              <Route path="cv/:id/update" element={<UpdateCv />} />
            )}
            {checkPerm('cv') && (
              <Route path="cv/:id/sendEmail" element={<ModalSendEmail />} />
            )}
            {/* 404 */}
            <Route path="*" element={<NotFound />} />
          </Route>
          {/* Setting */}
          <Route path="setting" element={<MainLayout />}>
            {checkPerm('general') && (
              <Route path="common" element={<Common />} />
            )}
            {checkPerm('positions') && (
              <Route path="position" element={<Position />} />
            )}
            {checkPerm('department') && (
              <Route path="department" element={<Department />} />
            )}
            {checkPerm('level') && <Route path="level" element={<Level />} />}
            {checkPerm('language') && (
              <Route path="language" element={<Language />} />
            )}
            {checkPerm('source') && (
              <Route path="source" element={<Source />} />
            )}
            {checkPerm('type_work') && (
              <Route path="type-work" element={<TypeWork />} />
            )}

            {checkPerm('email') && <Route path="email" element={<Mail />} />}
            {checkPerm('email') && (
              <Route path="email/create" element={<BackgroundForm />} />
            )}
            {checkPerm('email') && (
              <Route path="email/edit/:id" element={<BackgroundForm />} />
            )}
            <Route path="release" element={<ReleaseView />} />
            <Route path="release/config" element={<ReleaseConfig />} />

            {<Route path="template" element={<Template />} />}
            {checkPerm('users') && <Route path="user" element={<User />} />}
            {checkPerm('role') && <Route path="role" element={<Role />} />}
            {checkPerm('general') && <Route index element={<Common />} />}
            <Route path="*" element={<NotFound />} />
          </Route>
          {/* Login */}
          <Route path="/login" element={<Login />} />
          <Route path="/forgotpassword" element={<ForgotPassword />} />
        </Routes>
      )}
    </>
  );
}

export default App;
