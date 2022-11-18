import { createSlice } from '@reduxjs/toolkit';

const requestSlice = createSlice({
  name: 'request',
  initialState: {
    visibleFilterDrawer: false,
    totalRecords: 0,
    isReloadTable: false,
    isFullscreenMode: false,
    requestFormInfo: {},
    listPosition: [],
    listLevel: [],
    listTypeWork: [],
    listLanguages: [],
    listRequestor: [],
    listDeadlineDay: {},
    listUsername: [],
  },
  reducers: {
    changeVisibleFilterDrawer(state, action) {
      state.visibleFilterDrawer = action.payload;
    },
    setReloadTable(state) {
      state.isReloadTable = !state.isReloadTable;
    },
    setListPosition(state, action) {
      state.listPosition = action.payload;
    },
    setListLevel(state, action) {
      state.listLevel = action.payload;
    },
    setListTypeWork(state, action) {
      state.listTypeWork = action.payload;
    },
    setListLanguages(state, action) {
      state.listLanguages = action.payload;
    },
    setTotalRecords(state, action) {
      state.totalRecords = action.payload;
    },
    setListRequestor(state, action) {
      state.listRequestor = action.payload;
    },
    setRequestFormInfo(state, action) {
      state.requestFormInfo = action.payload;
    },
    setListDeadlineDay(state, action) {
      state.listDeadlineDay = action.payload;
    },
    setIsFullscreenMode(state, action) {
      state.isFullscreenMode = action.payload;
    },
    setListUsername(state, action) {
      state.listUsername = action.payload;
    },
  },
});
const { reducer, actions } = requestSlice;

export const {
  changeVisibleFilterDrawer,
  setReloadTable,
  setListPosition,
  setListLevel,
  setListTypeWork,
  setListLanguages,
  setTotalRecords,
  setListRequestor,
  setRequestFormInfo,
  setListDeadlineDay,
  setIsFullscreenMode,
  setListUsername,
} = actions;
export default reducer;
