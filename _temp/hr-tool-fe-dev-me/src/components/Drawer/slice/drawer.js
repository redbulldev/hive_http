import { createSlice } from '@reduxjs/toolkit';
import i18n from '../../../translation/i18n';

export const drawerSlice = createSlice({
  name: 'drawer',
  initialState: {
    isOpened: false,
    modeText: {
      title: '',
      btn: '',
    },
    mode: null,
    initial: {},
  },
  reducers: {
    setIsOpenedDrawer(state, action) {
      state.isOpened = action.payload;
    },
    setModeTextDrawer(state, action) {
      state.modeText = action.payload;
      if (action.payload?.btn?.includes(i18n.t('common.create')))
        state.mode = 'add';
      if (action.payload?.btn?.includes(i18n.t('common.edit')))
        state.mode = 'edit';
    },

    setInitialDrawer(state, action) {
      state.initial = action.payload;
    },
  },
});

export const { setIsOpenedDrawer, setModeTextDrawer, setInitialDrawer } =
  drawerSlice.actions;

export default drawerSlice.reducer;
