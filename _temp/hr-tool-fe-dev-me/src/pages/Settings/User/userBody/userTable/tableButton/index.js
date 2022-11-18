import {
  EyeOutlined,
  FullscreenExitOutlined,
  FullscreenOutlined,
  ImportOutlined,
} from '@ant-design/icons';
import { Button, Popover, Space, Tooltip } from 'antd';
import Checkbox from 'antd/lib/checkbox/Checkbox';
import React from 'react';
import { useTranslation } from 'react-i18next';
import exportIcon from '../../../../../../assets/images/tableIcon/export.svg';

/**
 * @author
 * @function TableButton
 **/

const TableButton = props => {
  const { columns, handle, setVisible, isCv } = props;
  const { t } = useTranslation();
  const handleOnClickFullScreen = () => {
    handle.enter();
  };
  const handleOnClickFullScreenExit = () => {
    handle.exit();
  };

  return (
    <div className="table-container__button cv__managerment--additional">
      {isCv && (
        <Tooltip
          title={t('cv.import')}
          color="blue"
          getTooltipContainer={parent => parent.parentNode}
        >
          <Button
            type="link"
            style={{ color: '#333', fontSize: '18px', marginRight: '-15px' }}
            onClick={() => setVisible(true)}
          >
            <ImportOutlined />
          </Button>
        </Tooltip>
      )}
      <Tooltip
        title={t('language.exportTooltip')}
        color="blue"
        getTooltipContainer={parent => parent.parentNode}
      >
        <span className="icon-table" onClick={props.handleExport}>
          <img src={exportIcon} alt="export" />
        </span>
      </Tooltip>

      <Popover
        content={<PopContent columns={columns} setColumns={props.setColumns} />}
        trigger="hover"
        placement="bottom"
        getPopupContainer={parent => parent.parentNode}
      >
        <Tooltip
          title={t('language.showColumnTooltip')}
          color="blue"
          getTooltipContainer={parent => parent.parentNode}
        >
          <span className="icon-table">
            <EyeOutlined />
          </span>
        </Tooltip>
      </Popover>
      <Tooltip
        title={t('language.fullScreenTooltip')}
        color="blue"
        getTooltipContainer={parent => parent.parentNode}
      >
        <span
          className="icon-table icon-enter"
          onClick={handleOnClickFullScreen}
        >
          <FullscreenOutlined />
        </span>
      </Tooltip>
      <Tooltip
        title={t('language.exitFullScreenTooltip')}
        color="blue"
        getTooltipContainer={parent => parent.parentNode}
      >
        <span
          className="icon-table icon-close"
          onClick={handleOnClickFullScreenExit}
        >
          <FullscreenExitOutlined />
        </span>
      </Tooltip>
    </div>
  );
};

export default React.memo(TableButton);

const PopContent = props => {
  const { columns, setColumns } = props;

  return (
    <Space direction="vertical" size={'small'} style={{ padding: '10px' }}>
      {columns &&
        columns
          .filter(
            filter =>
              filter.export &&
              filter.dataIndex !== 'username' &&
              filter.dataIndex !== 'id',
          )
          .map(e => (
            <Checkbox
              key={e.key}
              checked={e.show}
              onClick={() => {
                setColumns(
                  columns.map(prev => {
                    if (prev.dataIndex === e.dataIndex) {
                      return {
                        ...prev,
                        show: !e.show,
                      };
                    }
                    return prev;
                  }),
                );
              }}
            >
              {e.title}
            </Checkbox>
          ))}
    </Space>
  );
};
