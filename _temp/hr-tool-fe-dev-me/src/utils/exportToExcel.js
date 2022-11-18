import { message } from 'antd';
import { DEFAULT_LIMIT_RECORDS_EXPORT } from '../constants';
import { DEFAULT_PAGENUMBER } from '../constants/requestPage';
import i18n from '../translation/i18n';

export const exportToExcel = async (
  totalRecords,
  listParams,
  fetchApi,
  handleExportToExcel,
  nth = false,
  nthBy = 'id',
  items,
  pageNumber = DEFAULT_PAGENUMBER,
) => {
  const calculateNth = (index, filter) => {
    const orderby = filter.orderby;
    const curPage = filter.page;
    const numberPerPage = filter.limit;
    // if (orderby?.includes(`${nthBy}-DESC`))
    //   return totalRecords - index - (curPage - 1) * numberPerPage;
    return (curPage - 1) * numberPerPage + index + 1;
  };

  try {
    if (!totalRecords) {
      handleExportToExcel([]);
    } else {
      message.loading({
        content: i18n.t('request.loadingData', {
          pageNumber,
          total: Math.ceil(totalRecords / DEFAULT_LIMIT_RECORDS_EXPORT),
        }),
        key: 'export-request',
        duration: 0,
      });
      const newParams = {
        ...listParams,
        page: pageNumber,
        limit: DEFAULT_LIMIT_RECORDS_EXPORT,
      };
      // call api with full record ( limit = totalRecords )
      let excelItems;
      if (fetchApi.getAll) {
        const response = await fetchApi.getAll(newParams);
        excelItems = nth
          ? response.data.data.map((item, i) => ({
              ...item,
              nth: calculateNth(i, newParams),
            }))
          : response.data.data;
      } else excelItems = items;

      if (excelItems && excelItems.length > 0) {
        handleExportToExcel(excelItems);
        pageNumber++;
        if (
          pageNumber <= Math.ceil(totalRecords / DEFAULT_LIMIT_RECORDS_EXPORT)
        ) {
          exportToExcel(
            totalRecords,
            listParams,
            fetchApi,
            handleExportToExcel,
            nth,
            nthBy,
            pageNumber,
          );
        } else {
          message.success({
            content: i18n.t('request.loaded'),
            key: 'export-request',
            duration: 2,
          });
        }
      }
    }
  } catch (error) {
    message.error(i18n.t('request.failToFetchListRequest'));
  }
};
