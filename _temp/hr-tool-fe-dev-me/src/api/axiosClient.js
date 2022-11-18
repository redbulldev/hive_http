import axios from 'axios';
import queryString from 'query-string';
import { getToken } from './Cookie';

const createAxiosClient = (baseUrl, token, type) => {
  const axiosClient = axios.create({
    baseURL: baseUrl || process.env.REACT_APP_BASE_URL,
    headers: {
      'content-type': 'application/json',
    },
    paramsSerializer: params =>
      queryString.stringify(params, { skipNull: true, skipEmptyString: true }),
  });

  // Interceptors
  axiosClient.interceptors.request.use(function (config) {
    config.headers = {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token || getToken('Auth-Token') || undefined}`,
    };
    if (type === 'reminder') {
      config.headers['Prefer'] = 'outlook.body-content-type="text"';
    }
    return config;
  });

  axiosClient.interceptors.response.use(
    function (response) {
      if (response.status === 200) {
        return response;
      }
      return Promise.reject(response);
    },
    function (error) {
      if (error.response.status === 401) window.location.reload(false);
      return Promise.reject(error);
    },
  );

  return axiosClient;
};

export default createAxiosClient();

export { createAxiosClient };
