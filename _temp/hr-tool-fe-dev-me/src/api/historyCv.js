import {
  CV_HISTORY,
  EMAIL_HISTORY,
  EMAIL_TEMPLATE,
  SEND_MAIL,
} from '../constants/api';
import axiosClient from './axiosClient';

export function getHistoryCv(params) {
  return axiosClient.get(CV_HISTORY, { params });
}

export function updateHistoryCv(id, data) {
  return axiosClient.put(`${CV_HISTORY} / ${id}`, data);
}
export function getEmailHistory(params) {
  return axiosClient.get(EMAIL_HISTORY, { params });
}
export function getTemplateEmail(params) {
  return axiosClient.get(EMAIL_TEMPLATE, { params });
}
export function sendEmail(data) {
  return axiosClient.post(SEND_MAIL, data);
}
