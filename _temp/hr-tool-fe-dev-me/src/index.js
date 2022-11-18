import { PublicClientApplication } from '@azure/msal-browser';
import { MsalProvider } from '@azure/msal-react';
import { ConfigProvider } from 'antd';
import React from 'react';
import ReactDOM from 'react-dom';
import { I18nextProvider } from 'react-i18next';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import { PersistGate } from 'redux-persist/integration/react';
import App from './App';
import store, { persistor } from './app/store';
import { msalConfig } from './components/MsCalendar/authConfig';
import { locales } from './constants';
import i18n from './translation/i18n';

const msalInstance = new PublicClientApplication(msalConfig);

ReactDOM.render(
  <Provider store={store}>
    <MsalProvider instance={msalInstance}>
      <PersistGate loading={null} persistor={persistor}>
        <BrowserRouter>
          <I18nextProvider i18n={i18n}>
            <ConfigProvider locale={locales.vi}>
              <App />
            </ConfigProvider>
          </I18nextProvider>
        </BrowserRouter>
      </PersistGate>
    </MsalProvider>
  </Provider>,
  document.getElementById('root'),
);
