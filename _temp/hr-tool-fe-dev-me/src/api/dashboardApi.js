import { DASHBOARD_API, LEVEL_API, POSITION_API } from '../constants/api';
import axiosClient from './axiosClient';

const dashboardApi = {
  getAll(params) {
    const url = DASHBOARD_API;
    return axiosClient.get(url, { params });
  },
  getDataTable(params) {
    const url = DASHBOARD_API;
    return axiosClient.get(url, { params });
  },
  getFilterPositions(params) {
    const url = POSITION_API;

    return axiosClient.get(url, { params });
  },
  getLevelFilter(params) {
    const url = LEVEL_API;

    return axiosClient.get(url, { params });
  },
};
export default dashboardApi;
