import { createSlice } from '@reduxjs/toolkit';
export const PositionSlide = createSlice({
  name: 'position',
  initialState: {
    dataPosition: [],
    department: [],
    manager: [],
    requestor: [],
    visibleFormPosition: false,
    visibleDropdown: false,
    reloadTable: false,
    detailData: {},
    detailDepartment: {},
    totalPosition: 0,
    totalPositionDefault: 0,
    isFullMode: false,
  },
  reducers: {
    getAllPosition: (state, action) => {
      state.dataPosition = action.payload;
    },
    showFormPosition: (state, action) => {
      state.visibleFormPosition = action.payload;
    },
    setReloadTalbe: (state, action) => {
      state.reloadTable = !state.reloadTable;
    },
    setTotalPosition: (state, action) => {
      state.totalPosition = action.payload;
    },
    setTotalDefault: (state, action) => {
      state.totalPositionDefault = action.payload;
    },
    setVisibleDropdown: (state, action) => {
      state.visibleDropdown = action.payload;
    },
    setIsFullScreen: (state, action) => {
      state.isFullMode = action.payload;
    },
    getDetailPosition: (state, action) => {
      state.detailData = action.payload;
    },

    getResultSearch: (state, action) => {
      state.dataPosition = action.payload;
    },
    filterDepartment: (state, action) => {
      const dataDepartment = [
        ...new Set(
          action.payload.map(item => {
            return {
              parent_title: item?.title,
              parent_id: item?.id,
            };
          }),
        ),
      ];
      const data = Array.from(new Set(dataDepartment.map(JSON.stringify))).map(
        JSON.parse,
      );
      const filterNull = data.filter(item => item.parent_title !== null);
      state.department = filterNull;
    },
    filterManager: (state, action) => {
      const dataManager = [
        ...new Set(action.payload.map(item => item.username)),
      ];
      const filterNull = dataManager.filter(item => item !== null);
      state.manager = filterNull;
    },
    filterRequestor: (state, action) => {
      const dataRequestor = [
        ...new Set(action.payload.map(item => item.username)),
      ];
      const filterNull = dataRequestor.filter(item => item !== null);
      state.manager = filterNull;

      state.requestor = filterNull;
    },
  },
});

// Action creators are generated for each case reducer function
export const {
  getAllPosition,
  showFormPosition,
  filterDepartment,
  filterManager,
  filterRequestor,
  setReloadTalbe,
  getResultSearch,
  getDetailPosition,
  setTotalPosition,
  setTotalDefault,
  setVisibleDropdown,
  setIsFullScreen,
} = PositionSlide.actions;

export default PositionSlide.reducer;
