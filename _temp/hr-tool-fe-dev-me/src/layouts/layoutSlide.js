import { createSlice } from '@reduxjs/toolkit';
import scss from '../assets/scss/_sidebar.scss';

scss.sidebar = Number(scss.sidebar.slice(0, -2));

function isMobileWidth() {
  return window.innerWidth <= scss.sidebar;
}

export const layoutSlide = createSlice({
  name: 'layout',
  initialState: {
    isSidebarOpened: false,
  },
  reducers: {
    setIsSidebarOpened(state, action) {
      if (isMobileWidth()) {
        const s = action.payload;
        const body = document.body;
        const sidebar = document.querySelector('.sidebar');
        state.isSidebarOpened = s;
        if (s) {
          body.classList.add('cantScroll');
          sidebar.classList.add('show');
        } else {
          body.classList.remove('cantScroll');
          sidebar.classList.remove('show');
        }
      }
    },
  },
});

export const { setIsSidebarOpened } = layoutSlide.actions;

export default layoutSlide.reducer;
