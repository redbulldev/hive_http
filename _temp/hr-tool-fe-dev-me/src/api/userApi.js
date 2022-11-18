import axiosClient from './axiosClient';
import { AUTH_URL, REFRESH_URL } from '../constants/auth';

export function postLogin(data) {
  return axiosClient.post(AUTH_URL, data);
}

export function postRefeshToken(data) {
  return axiosClient.post(REFRESH_URL, data);
}
