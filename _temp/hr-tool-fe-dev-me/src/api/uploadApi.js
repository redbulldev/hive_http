import { UPLOAD_URL } from '../constants/api';
import axiosClient from './axiosClientToUpload';

const uploadApi = {
  post(file) {
    const formData = new FormData();
    formData.append('data', file);
    return axiosClient.post(UPLOAD_URL, formData);
  },
  postMutiple: file => {
    const formData = new FormData();
    for (let i in file) {
      formData.append(i, file[i]);
    }
    return axiosClient.post(UPLOAD_URL, formData);
  },
};

export default uploadApi;
