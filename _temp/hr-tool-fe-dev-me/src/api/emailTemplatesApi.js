import axiosClient from './axiosClient';
import { EMAIL_TEMPLATES_URL } from '../constants/api';
const emailTemplatesApi = {
  getAll(params) {
    return axiosClient.get(EMAIL_TEMPLATES_URL, { params });
  },
  getById(id) {
    return axiosClient.get(`${EMAIL_TEMPLATES_URL}/${id}`);
  },
  add(data) {
    return axiosClient.post(EMAIL_TEMPLATES_URL, data);
  },
  delete(id) {
    return axiosClient.delete(`${EMAIL_TEMPLATES_URL}/${id}`);
  },
  edit(id, data) {
    return axiosClient.put(`${EMAIL_TEMPLATES_URL}/${id}`, data);
  },
  getById(id) {
    return axiosClient.get(`${EMAIL_TEMPLATES_URL}/${id}`);
  },
  deleteMany(arr) {
    return axiosClient.delete(`${EMAIL_TEMPLATES_URL}`, { data: arr });
  },
};

export default emailTemplatesApi;
