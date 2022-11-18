import queryString from 'query-string';
import { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useLocation, useNavigate } from 'react-router-dom';
import { setTableResponse } from '../../../app/tableSlice';
import { DEFAULT_FILTER } from '../../../constants';

export default function useTable({
  getApi,
  apiFilter,
  nthBy = 'id',
  defaultFilter,
}) {
  const location = useLocation();
  const navigate = useNavigate();
  const dispatch = useDispatch();

  const [items, setItems] = useState();
  const [loadingTable, setLoadingTable] = useState(false);
  const [totalRecord, setTotalRecord] = useState(0);
  const [reloadTable, setReloadTable] = useState(false);
  const [outSideItems, setOutSideItems] = useState();

  const [filter, setFilter] = useState(() => {
    const searchParam = queryString.parse(location.search, {
      parseNumbers: true,
    });
    if (Object.keys(searchParam).length === 0) {
      return { ...DEFAULT_FILTER, ...defaultFilter } || DEFAULT_FILTER;
    }
    return searchParam || DEFAULT_FILTER;
  });

  const calculateNth = (index, total) => {
    const curPage = filter.page ?? DEFAULT_FILTER.page;
    const numberPerPage = filter.limit ?? DEFAULT_FILTER.limit;
    return (curPage - 1) * numberPerPage + index + 1;
  };

  const fetchDataForTable = () => {
    setLoadingTable(true);
    const newFilter = {};
    const oriFilter = JSON.parse(JSON.stringify(filter));
    for (let key in oriFilter) {
      let value = oriFilter[key];
      if (typeof value === 'string') {
        value = value.trim();
      } else if (Array.isArray(value)) {
        value = value.join('-');
      }
      newFilter[key] = value;
    }

    getApi({ ...newFilter, ...apiFilter })
      .then(res => {
        dispatch(setTableResponse(res.data));
        const items = res.data.data.map((item, index) => ({
          ...item,
          nth: calculateNth(index, res.data.total),
        }));
        setTotalRecord(res.data.total);
        setItems(items);
        setOutSideItems(res.data);

        setLoadingTable(false);
        if (res.data.totalpage < newFilter.page)
          setFilter({ ...newFilter, page: res.data.totalpage });
      })
      .catch(e => {
        console.log(e);
        setLoadingTable(false);
      });
  };

  const fetchData = () => {
    setReloadTable(pre => !pre);
  };

  useEffect(() => {
    fetchDataForTable();
    navigate({
      pathname: location.pathname,
      search: queryString.stringify(filter, {
        skipNull: true,
        skipEmptyString: true,
      }),
    });
  }, [filter, reloadTable]);
  // eslint-disable-next-line react-hooks/exhaustive-deps

  return {
    items,
    outSideItems,
    filter,
    setFilter,
    loadingTable,
    fetchData,
    totalRecord,
  };
}
