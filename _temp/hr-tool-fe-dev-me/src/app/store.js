import { configureStore } from '@reduxjs/toolkit';
import {
  FLUSH,
  PAUSE,
  PERSIST,
  persistReducer,
  persistStore,
  PURGE,
  REGISTER,
  REHYDRATE,
} from 'redux-persist';
import storage from 'redux-persist/lib/storage';
import userSlice from '../pages/Settings/commonSlice/userSlice';
import authentication from '../pages/Auth/reducer/auth';
import dataTable from './dashboard-reducer';
import PositionSlide from '../pages/Settings/Position/reducer';
import dataDetailPlan from '../pages/Plan/reducer/plan-reducer';
import requestReducer from '../pages/Request/requestSlice';
import tableReducer from './tableSlice';
import layoutSlide from '../layouts/layoutSlide';
import drawerSlice from '../components/Drawer/slice/drawer';
import commonSlice from './common';

const persistConfig = {
  key: 'root',
  version: 1,
  storage,
  whitelist: ['userInfor'],
};

const persistedReducer = persistReducer(persistConfig, authentication);

const store = configureStore({
  reducer: {
    common: commonSlice,
    auth: persistedReducer,
    tableDashboard: dataTable,
    user: userSlice,
    position: PositionSlide,
    drawer: drawerSlice,
    layout: layoutSlide,
    detailParams: dataDetailPlan,
    table: tableReducer,
    request: requestReducer,
  },
  middleware: getDefaultMiddleware =>
    getDefaultMiddleware({
      serializableCheck: {
        ignoredActions: [FLUSH, REHYDRATE, PAUSE, PERSIST, PURGE, REGISTER],
      },
    }),
});
export const persistor = persistStore(store);
export default store;
