import { useMsal } from '@azure/msal-react';
import { useState } from 'react';
import { useEffect } from 'react';
import { createAxiosClient } from '../../api/axiosClient';
import { loginRequest } from './authConfig';

const useCallMsApi = ({ type, params } = {}) => {
  const { instance, accounts } = useMsal();
  const [token, setToken] = useState('');
  const [isTokenReady, setIsTokenReady] = useState(false);

  useEffect(async () => {
    const response = await instance.acquireTokenSilent({
      ...loginRequest,
      account: accounts[0],
    });
    setToken(response.accessToken);
    setIsTokenReady(true);
  }, []);

  const axiosWithToken = () => {
    return createAxiosClient(
      'https://graph.microsoft.com/v1.0/me/',
      token,
      'reminder',
    );
  };

  const whichCase = type => {
    if (token) {
      switch (type) {
        case 'getAll':
          return () => axiosWithToken().get('calendarview', { params });
        case 'edit':
          return data => {
            const id = data.id;
            delete data.id;
            return axiosWithToken().patch('events/' + id, data);
          };
      }
    }
    return null;
  };

  return { api: whichCase(type), isTokenReady };
};

export { useCallMsApi };
