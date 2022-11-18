import axios from 'axios';
import { getToken } from './Cookie';

const axiosInstance = axios.create({
  baseURL: process.env.REACT_APP_BASE_URL,
  headers: { 'Content-Type': 'multipart/form-data' },
});
axiosInstance.interceptors.request.use(function (config) {
  config.headers = {
    Authorization: getToken('Auth-Token')
      ? `Bearer ${getToken('Auth-Token')}`
      : undefined,
  };
  return config;
});
export default axiosInstance;
