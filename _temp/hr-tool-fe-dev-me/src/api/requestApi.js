import { REQUEST_URL } from '../constants/api';
import axiosClient from './axiosClient';

const requestApi = {
  getAll(params) {
    const url = REQUEST_URL;
    return axiosClient.get(url, { params });
  },
  getById(requestId) {
    const url = REQUEST_URL + `/${requestId}`;
    return axiosClient.get(url);
  },
  create(data) {
    const url = REQUEST_URL;
    return axiosClient.post(url, data);
  },
  edit(requestId, data) {
    const url = REQUEST_URL + `/${requestId}`;
    return axiosClient.put(url, data);
  },
  delete(requestId) {
    const url = REQUEST_URL + `/${requestId}`;
    return axiosClient.delete(url);
  },
  multiDelete(listRequestIds) {
    const url = REQUEST_URL;
    return axiosClient.delete(url, { data: listRequestIds });
  },
  getRequestToAddCv(data) {
    const url = REQUEST_URL;
    return axiosClient.get(url, {
      params: { ...data, cv: 'add', status: 2 },
    });
  },
};
export default requestApi;
