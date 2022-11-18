import React, { useEffect, useState } from 'react';
import { GET_FULL_LIST_PARAMS } from '../../../constants';

export default function useFetchList({ api }) {
  const [items, setItems] = useState([]);

  useEffect(async () => {
    try {
      const response = await api(GET_FULL_LIST_PARAMS);
      const data = response.data.data;
      setItems(data);
    } catch (e) {
      console.log('e :', e);
    }
  }, []);

  return { items, setItems };
}
