import React, { useEffect, useState } from 'react';
import { GET_FULL_LIST_PARAMS } from '../../../constants';

export default function useGetDetail({ api, value }) {
  const [detail, setDetail] = useState({});

  useEffect(async () => {
    if (value) {
      try {
        const response = await api(value);
        const data = response.data.data;
        setDetail(data);
      } catch (e) {
        console.log('e :', e);
      }
    }
  }, [value]);

  return { detail, setDetail };
}
