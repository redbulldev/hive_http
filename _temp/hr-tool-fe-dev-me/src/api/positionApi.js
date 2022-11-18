import { POSITION_URL } from '../constants/api';
import axiosClient from './axiosClient';
export const positionApi = {
  getPositionByPage(params) {
    return axiosClient.get(POSITION_URL, { params });
  },
  getAll(params) {
    return axiosClient.get(POSITION_URL, { params });
  },
  getAllPosition(params) {
    return axiosClient.get(POSITION_URL, { params });
  },
  getPositionById(id) {
    const url = `${POSITION_URL}/${id}`;
    return axiosClient.get(url);
  },
  postPosition(data) {
    return axiosClient.post(POSITION_URL, data);
  },
  edit(id, data) {
    const url = `${POSITION_URL}/${id}`;
    return axiosClient.put(url, data);
  },
  putPosition(data) {
    const url = `${POSITION_URL}/${data.id}`;
    return axiosClient.put(url, data);
  },
  delete(id) {
    const url = `${POSITION_URL}/${id}`;
    return axiosClient.delete(url);
  },
  multiDelete(data) {
    let requestBody = {
      data: data,
    };
    return axiosClient.delete(POSITION_URL, requestBody);
  },
};
