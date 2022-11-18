import { CV_URL, PERMISSION_URL, ROLE_URL, USERS_URL } from '../constants/api';
import axiosClient from './axiosClient';

export const settingUserApi = {
  getAll(params) {
    const url = ROLE_URL;
    return axiosClient.get(url, { params });
  },
  getById(id) {
    const url = ROLE_URL + `/${id}`;
    return axiosClient.get(url);
  },
  create(data) {
    const url = ROLE_URL;
    return axiosClient.post(url, data);
  },
  edit(id, data) {
    const url = ROLE_URL + `/${id}`;
    return axiosClient.put(url, data);
  },
  delete(id) {
    const url = ROLE_URL + `/${id}`;
    return axiosClient.delete(url);
  },
  multiDelete(data) {
    const url = ROLE_URL;
    return axiosClient.delete(url, { data });
  },
};
export const userPermissionApi = {
  getAll(params) {
    const url = PERMISSION_URL;
    return axiosClient.get(url, { params });
  },
};
export const settingUserApi2 = {
  getById: id => {
    return axiosClient.get(`${USERS_URL}/${id}`);
  },
  getAll: params => {
    return axiosClient.get(USERS_URL, { params });
  },
  edit: (id, data) => {
    const byId = `${USERS_URL}/${id}`;
    return axiosClient.put(byId, data);
  },
  add: data => {
    return axiosClient.post(USERS_URL, data);
  },
  delete: id => {
    const byId = `${USERS_URL}/${id}`;
    return axiosClient.delete(byId);
  },
  deleteMany: data => {
    const byId = `${USERS_URL}`;
    return axiosClient.delete(byId, { data: data });
  },
};

export const settingRoleApi = {
  getAll: params => {
    return axiosClient.get(ROLE_URL, { params });
  },
};
export const settingCVApi = {
  getAll: params => {
    return axiosClient.get(CV_URL, { params });
  },
};
