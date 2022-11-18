import { memo, useState } from 'react';
import { FullScreen, useFullScreenHandle } from 'react-full-screen';
import { useDispatch, useSelector } from 'react-redux';
import { setIsFullscreenMode } from '../../requestSlice';
import SearchForm from '../SearchForm';
import ContentRequestTable from './components/TableContent';

function RequestTable({
  listParams,
  onChangeListParams,
  onChangeTitleForm,
  onChangeTitleButtonForm,
}) {
  const dispatch = useDispatch();
  const [selectedRowKeys, setSelectedRowKeys] = useState([]);
  const handleFullScreen = useFullScreenHandle();
  const isFullscreenMode = useSelector(state => state.request.isFullscreenMode);

  const handleChangeFullscreen = state => {
    dispatch(setIsFullscreenMode(state));
  };

  return (
    <div className="request__table">
      <FullScreen
        handle={handleFullScreen}
        className="request__fullscreen"
        onChange={handleChangeFullscreen}
      >
        {isFullscreenMode && (
          <SearchForm
            listParams={listParams}
            onChangeListParams={onChangeListParams}
            className="request-desktop"
          />
        )}
        <ContentRequestTable />
      </FullScreen>
    </div>
  );
}

export default memo(RequestTable);
