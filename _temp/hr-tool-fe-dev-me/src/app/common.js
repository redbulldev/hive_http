import { createSlice } from '@reduxjs/toolkit';

export const commonSlice = createSlice({
  name: 'common',
  initialState: {
    isFullscreen: false,
  },
  reducers: {
    setIsFullscreen(state, action) {
      state.isFullscreen = action.payload;
    },
  },
});

export const { setIsFullscreen } = commonSlice.actions;

export default commonSlice.reducer;
