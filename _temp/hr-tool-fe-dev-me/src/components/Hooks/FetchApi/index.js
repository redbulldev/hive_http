import { settingUserApi2 } from '../../../api/settingUserApi';
import levelApi from '../../../api/levelApi';
import useFetchList from './useFetchList';
import releaseApi from '../../../api/releaseApi';

export const useFetchUser = () => useFetchList({ api: settingUserApi2.getAll });
export const useFetchLevel = () => useFetchList({ api: levelApi.getLevel });
export const useFetchRelease = () => useFetchList({ api: releaseApi.getAll });
export { useFetchList };
export { default as useGetDetail } from './useGetDetail';
