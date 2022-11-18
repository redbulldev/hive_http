import { SETTING_URL } from '../constants/api';
import axiosClient from './axiosClient';

const commonSettingApi = {
  getAll(params) {
    const url = SETTING_URL;
    return axiosClient.get(url, { params });
  },
  getById(settingId) {
    const url = SETTING_URL + `/${settingId}`;
    return axiosClient.get(url);
  },
  create(data) {
    const url = SETTING_URL;
    return axiosClient.post(url, data);
  },
  edit(settingId, data) {
    const url = SETTING_URL + `/${settingId}`;
    return axiosClient.put(url, data);
  },
  delete(settingId) {
    const url = SETTING_URL + `/${settingId}`;
    return axiosClient.delete(url);
  },
};
export default commonSettingApi;
