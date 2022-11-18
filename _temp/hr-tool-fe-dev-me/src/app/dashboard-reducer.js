import { createSlice } from '@reduxjs/toolkit';

const dataTable = createSlice({
  name: 'dashboard',
  initialState: {
    listData: [],
    listFieldFilter: [],
    listDataSummary: {},
    listDataDepartment: [],
    totalRecord: 0,
    listPosition: [],
    listLevel: [],
  },
  reducers: {
    setListData: (state, action) => {
      state.listData = action.payload;
    },
    setListFieldFilter: (state, action) => {
      state.listFieldFilter = action.payload;
    },

    setListDataSummary: (state, action) => {
      state.listDataSummary = action.payload;
    },
    setListDataDepartment: (state, action) => {
      state.listDataDepartment = action.payload;
    },
    setTotalRecord: (state, action) => {
      state.totalRecord = action.payload;
    },
    setListPosition: (state, action) => {
      state.listPosition = action.payload;
    },
    setListLevel: (state, action) => {
      state.listLevel = action.payload;
    },
  },
});
const { reducer, actions } = dataTable;
export const {
  setListData,
  setListFieldFilter,
  setListDataSummary,
  setListDataDepartment,
  setTotalRecord,
  setListPosition,
  setListLevel,
} = actions;
export default reducer;
