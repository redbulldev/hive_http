import { createSlice } from '@reduxjs/toolkit';

const dataDetailPlan = createSlice({
  name: 'plan',
  initialState: {
    listParams: {},
    listLevel: [],
    listRequest: [],
    listUser: [],
    totalRecord: 0,
    totalRecordGeneral: 0,
    typework: [],
    defaultValueForm: {},
    timeParams: {},
  },
  reducers: {
    setListParams: (state, action) => {
      state.listParams = action.payload;
    },
    setListLevel: (state, action) => {
      state.listLevel = action.payload;
    },
    setListRequest: (state, action) => {
      state.listRequest = action.payload;
    },
    setListUser: (state, action) => {
      state.listUser = action.payload;
    },
    setTotalRecord: (state, action) => {
      state.totalRecord = action.payload;
    },
    setTotalRecordGeneral: (state, action) => {
      state.totalRecordGeneral = action.payload;
    },
    setTypework: (state, action) => {
      state.typework = action.payload;
    },
    setDefaultForm: (state, action) => {
      state.defaultValueForm = action.payload;
    },
  },
});
const { reducer, actions } = dataDetailPlan;
export const {
  setListParams,
  setListLevel,
  setDefaultForm,
  setListRequest,
  setListUser,
  setTotalRecord,
  setTotalRecordGeneral,
  setTypework,
} = actions;
export default reducer;
