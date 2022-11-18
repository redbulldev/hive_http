import { createSlice } from '@reduxjs/toolkit';

export const tableSlice = createSlice({
  name: 'table',
  initialState: {
    tableResponse: null,
  },
  reducers: {
    setTableResponse(state, action) {
      state.tableResponse = action.payload;
    },
  },
});

export const { setTableResponse } = tableSlice.actions;

export default tableSlice.reducer;
