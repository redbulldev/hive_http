export const onChangeTable = (
  filter,
  setFilter,
  setSelects,
  nth = false,
  nthBy = 'id',
) => {
  return function (e, filterParam, sorter) {
    setSelects([]);
    const colName = nth && sorter.field === 'nth' ? nthBy : sorter.field;
    const sorterValue = sorter.order
      ? `${colName}-${sorter.order === 'ascend' ? 'ASC' : 'DESC'}`
      : '';
    const newFilterParam = {};
    for (let key in filterParam) {
      newFilterParam[key] = filterParam[key] ? filterParam[key].join('-') : '';
    }
    setFilter({
      ...filter,
      page: e.current,
      limit: e.pageSize,
      orderby: sorterValue,
      ...newFilterParam,
    });
  };
};
