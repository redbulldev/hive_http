import axiosClient from './axiosClient';
import { RELEASE_URL } from '../constants/api';
const releaseApi = {
  getAll(params) {
    return axiosClient.get(RELEASE_URL, { params });
  },
  getById(id) {
    return axiosClient.get(`${RELEASE_URL}/${id}`);
  },
  add(data) {
    return axiosClient.post(RELEASE_URL, data);
  },
  delete(id) {
    return axiosClient.delete(`${RELEASE_URL}/${id}`);
  },
  edit(id, data) {
    return axiosClient.put(`${RELEASE_URL}/${id}`, data);
  },
  getById(id) {
    return axiosClient.get(`${RELEASE_URL}/${id}`);
  },
  deleteMany(arr) {
    return axiosClient.delete(`${RELEASE_URL}`, { data: arr });
  },
};

export default releaseApi;
