import axios from 'axios';
import queryString from 'query-string';
import { getToken } from './Cookie';

const axiosClient = axios.create({
  baseURL: process.env.REACT_APP_BASE_URL,
  headers: {
    'Content-Type': 'multipart/form-data',
  },
  paramsSerializer: params =>
    queryString.stringify(params, { skipNull: true, skipEmptyString: true }),
});

// Interceptors
axiosClient.interceptors.request.use(function (config) {
  config.headers = {
    'Content-Type': 'multipart/form-data',
    Authorization: getToken('Auth-Token')
      ? `Bearer ${getToken('Auth-Token')}`
      : undefined,
  };
  return config;
});

axiosClient.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response.status === 401) window.location.reload(false);
    return Promise.reject(error);
  },
);

export default axiosClient;
