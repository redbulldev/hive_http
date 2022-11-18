import axiosClient from './axiosClient';
import { EMAIL_HISTORY_URL } from '../constants/api';
const emailHistoryApi = {
  getAll(params) {
    return axiosClient.get(EMAIL_HISTORY_URL, { params });
  },
  getById(id) {
    return axiosClient.get(`${EMAIL_HISTORY_URL}/${id}`);
  },
};

export default emailHistoryApi;
