import axiosClient from './axiosClient';
import { API_LEVEL } from '../constants/level';
const levelApi = {
  getLevel(params) {
    return axiosClient.get(API_LEVEL, { params });
  },
  postLevel(data) {
    return axiosClient.post(API_LEVEL, data);
  },
  deleteLevel(id) {
    return axiosClient.delete(`${API_LEVEL}/${id}`);
  },
  updateLevel(id, data) {
    return axiosClient.put(`${API_LEVEL}/${id}`, data);
  },
  getLevelById(id) {
    return axiosClient.get(`${API_LEVEL}/${id}`);
  },
  deleteMany(arr) {
    return axiosClient.delete(`${API_LEVEL}`, { data: arr });
  },
};

export default levelApi;
