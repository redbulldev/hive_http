import { useEffect, useState } from 'react';
import requestApi from '../../../api/requestApi';

export default function useFetchDetailRequest(id) {
  const [detailRequest, setDetailRequest] = useState({});
  const fetchDetailRequest = async () => {
    try {
      const resp = await requestApi.getById(id);
      setDetailRequest(resp.data.data);
    } catch (error) {
      console.log('error :', error);
    }
  };
  useEffect(() => {
    fetchDetailRequest();
  }, []);
  return { detailRequest };
}
