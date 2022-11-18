import { Button, Col, Input, Row, Form, message } from 'antd';
import bg from '../../assets/images/login/bg.png';
import logo from '../../assets/images/login/logoHivetech.png';
import { useDispatch } from 'react-redux';
import { Link, Navigate, useNavigate } from 'react-router-dom';
import { setLogin } from './reducer/auth';
import {
  getRefreshToken,
  setCookieRefreshToken,
  setToken,
} from '../../api/Cookie';
import { postLogin, postRefeshToken } from '../../api/userApi';
import { useTranslation } from 'react-i18next';
import moment from 'moment';
import { REFRESH_TOKEN_TIME_UNIT } from '../../constants/auth';
import { useState } from 'react';

const Login = () => {
  message.config({
    getContainer() {
      return document.body;
    },
  });
  const token = getRefreshToken('Refresh-Token');
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const navi = useNavigate();

  const [loadingLogin, setLoadingLogin] = useState(false);

  const sendRequest = async () => {
    const refreshToken = getRefreshToken('Refresh-Token');
    if (refreshToken) {
      try {
        const res = await postRefeshToken({ refresh_token: refreshToken });
        dispatch(setLogin(res.data.data));
        setToken(res.data.data.access_token);
        setCookieRefreshToken(res.data.data.refresh_token);
        setTimeout(async () => {
          await sendRequest();
        }, res.data.data.expires_in * REFRESH_TOKEN_TIME_UNIT);
      } catch {
        navi('/login');
      }
    } else {
      navi('/login');
    }
  };

  const handleLogin = async value => {
    setLoadingLogin(true);
    try {
      const data = {
        username: value.username,
        password: value.password,
      };
      const res = await postLogin(data);
      setLoadingLogin(false);
      dispatch(setLogin(res.data.data));
      setToken(res.data.data.access_token);
      setCookieRefreshToken(res.data.data.refresh_token);
      setTimeout(async () => {
        await sendRequest();
      }, res.data.data.expires_in * REFRESH_TOKEN_TIME_UNIT);
      message.success(t('login.success'));
      const triedLink = localStorage.getItem('triedLink');
      if (triedLink) navi(triedLink);
      else navi('/');
    } catch (res) {
      setLoadingLogin(false);
      if (res.data.message.includes('block'))
        message.error(t('login.lockedAccount'));
      else if (res.data.message.includes('credentials'))
        message.error(t('login.loginFailed'));
      else if (res.data.message.includes('permission'))
        message.error(t('login.noPermission'));
      else message.error(res.data.message);
      console.log('CATCH error', res);
    }
  };
  const redirect = () => {
    if (!token) {
      return (
        <div className="login ">
          <Row justify="space-around" align="middle">
            <Col lg={9} xl={9} className="login--left">
              <img src={bg} alt="" />
            </Col>
            <Col sm={14} md={12} lg={10} xl={7}>
              <div className="login--right">
                <div className="login--right__header">
                  <img src={logo} alt="" />
                  <p>{t('login.header')}</p>
                </div>
                <div className="login--right__content">
                  <Form
                    name="basic"
                    initialValues={{ remember: true }}
                    autoComplete="off"
                    onFinish={handleLogin}
                  >
                    <Form.Item
                      label={
                        <span className="field--required">
                          {t('login.username')} (<span>*</span>)
                        </span>
                      }
                      style={{ marginBottom: '12px!important' }}
                      name="username"
                      rules={[
                        {
                          required: true,
                          message: t('login.message-error.username'),
                        },
                      ]}
                    >
                      <Input />
                    </Form.Item>
                    <Form.Item
                      label={
                        <span className="field--required">
                          {t('login.password')} (<span>*</span>)
                        </span>
                      }
                      name="password"
                      rules={[
                        {
                          required: true,
                          message: t('login.message-error.password'),
                        },
                      ]}
                    >
                      <Input.Password />
                    </Form.Item>
                    <div style={{ textAlign: 'end', margin: '16px 0' }}>
                      <Link
                        to="/forgotpassword"
                        style={{ color: 'rgba(0, 0, 0, 0.85)' }}
                      >
                        {t('login.forgot-password')}
                      </Link>
                    </div>
                    <Form.Item>
                      <Button
                        htmlType="submit"
                        type="danger"
                        loading={loadingLogin}
                      >
                        {t('login.title')}
                      </Button>
                    </Form.Item>
                  </Form>
                </div>
              </div>
            </Col>
          </Row>
        </div>
      );
    }
    return <Navigate to="/" />;
  };
  return <>{redirect()}</>;
};
export default Login;
