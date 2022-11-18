import {
  PLAN_URL,
  REQUEST_URL,
  USER_URL,
  TYPE_WORK_URL,
  SOURCE_API,
} from '../constants/api';
import axiosClient from './axiosClient';
const planApi = {
  getAll(params) {
    const url = PLAN_URL;
    return axiosClient.get(url, { params });
  },
  getDataTablePlan(params) {
    const url = PLAN_URL;
    return axiosClient.get(url, { params });
  },
  getTableDetail(params) {
    const url = PLAN_URL;
    return axiosClient.get(url, { params });
  },
  getUserFilter(params) {
    const url = USER_URL;
    return axiosClient.get(url, { params });
  },
  getTypework(params) {
    const url = TYPE_WORK_URL;
    return axiosClient.get(url, { params });
  },
  fixPlan(id, params) {
    const url = `${PLAN_URL}/${id}`;
    return axiosClient.put(url, params);
  },
  getDataSource(params) {
    const url = SOURCE_API;
    return axiosClient.get(url, { params });
  },
};
export default planApi;
