import { IMPORT_URL } from '../constants/api';
import axiosClient from './axiosClient';

const importApi = {
  postFile(file) {
    let formData = new FormData();
    formData.append('file', file);
    const url = IMPORT_URL;
    return axiosClient.post(url, formData);
  },
};
export default importApi;
