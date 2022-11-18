import { DEPARTMENT_URL } from '../constants/api';
import axiosClient from './axiosClient';
export const departmentApi = {
  getDepartmentByPage(params) {
    return axiosClient.get(DEPARTMENT_URL, { params });
  },
  getAll(params) {
    return axiosClient.get(DEPARTMENT_URL, { params });
  },
  getDepartmentById(id) {
    const url = `${DEPARTMENT_URL}/${id}`;
    return axiosClient.get(url);
  },
  postDepartment(data) {
    return axiosClient.post(DEPARTMENT_URL, data);
  },
  putDepartment(data) {
    const url = `${DEPARTMENT_URL}/${data.id}`;
    return axiosClient.put(url, data);
  },
  putDepartment2(id, data) {
    const url = `${DEPARTMENT_URL}/${id}`;
    return axiosClient.put(url, data);
  },
  deleteDepartmentById(id) {
    const url = `${DEPARTMENT_URL}/${id}`;
    return axiosClient.delete(url);
  },
  deleteDepartment(data) {
    let requestBody = {
      data: data,
    };
    return axiosClient.delete(DEPARTMENT_URL, requestBody);
  },
};
