import {
  CheckOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
  EyeOutlined,
  FullscreenExitOutlined,
  FullscreenOutlined,
  ExportOutlined,
} from '@ant-design/icons';
import { Button, Dropdown, Menu, message, Modal, Tooltip } from 'antd';
import { Excel } from 'antd-table-saveas-excel';
import Checkbox from 'antd/lib/checkbox/Checkbox';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { DEFAULT_STYLE_EXCEL } from '../../../../constants';
import { titleConfirmRemove } from '../../../../constants/languagePage';
import { exportToExcel } from '../../../../utils/exportToExcel';
import { NoBorderButton } from '../../../Buttons';

function FullScreenButton({ handleFullScreen }) {
  const { isFullscreen } = useSelector(state => state.common);
  const { t } = useTranslation();

  const handleClick = () => {
    if (isFullscreen) handleFullScreen.exit();
    else handleFullScreen.enter();
  };

  return (
    <Tooltip title={'Toàn màn hình'} color="blue">
      <NoBorderButton
        onClick={handleClick}
        icon={
          isFullscreen ? (
            <FullscreenExitOutlined style={{ fontSize: 20 }} />
          ) : (
            <FullscreenOutlined style={{ fontSize: 20 }} />
          )
        }
      ></NoBorderButton>
    </Tooltip>
  );
}

const UtilitiesBars = ({
  handleFullScreen,
  callback,
  dataExport,
  columns,
  nth,
  setColumns,
  filter,
  selects,
  idKey,
  deleteContentKey,
  nthBy,
  buttons = ['export', 'toggle', 'fullscreen'],
  setSelects,
  fetchData,
  items,
  totalRecord,
  excelName,
  getApi,
  deletePermission,
  deleteManyApi,
  removeDeleteMany,
}) => {
  const { t } = useTranslation();
  const [visibleDropdown, setVisibleDropdown] = useState(false);

  const handleChangeVisibleCheckbox = (e, id) => {
    setColumns(prev => {
      return prev.map(col => {
        if (col.id === id) {
          col.show = e.target.checked;
        }
        return col;
      });
    });
  };

  const EyeMenu = () => {
    return (
      <Menu>
        {columns
          .filter(col => col.export)
          .map((col, i) => {
            return (
              <Menu.Item key={i}>
                <Checkbox
                  onChange={e => handleChangeVisibleCheckbox(e, col.id)}
                  value={col.id}
                  checked={col.show}
                >
                  {col.title}
                </Checkbox>
              </Menu.Item>
            );
          })}
      </Menu>
    );
  };
  const handleExportToExcel = sources => {
    const transformColumns = () => {
      let newColumns = JSON.parse(JSON.stringify(columns));
      columns.forEach((col, i) => {
        const oldFunc = col.render;
        if (oldFunc) {
          newColumns[i].render = oldFunc.bind({});
        }
      });
      if (callback) {
        newColumns = callback(newColumns);
      }

      newColumns = newColumns
        .filter(col => col.export && col.show)
        .map(col => {
          if (col.colType === 'status' && col.dataIndex === 'status') {
            col.render = status =>
              status ? t('typework.active') : t('typework.locking');
          }
          if (col.colType === 'tooltip') {
            col.render = undefined;
          }
          if (col?.width) delete col.width;
          return col;
        });
      return newColumns;
    };

    const excel = new Excel();
    // set font family to excel file
    excel.setTBodyStyle(DEFAULT_STYLE_EXCEL).setTHeadStyle(DEFAULT_STYLE_EXCEL);

    const newColumns = transformColumns();
    excel
      .addSheet(excelName)
      .addColumns(newColumns)
      .addDataSource(dataExport?.length ? dataExport : sources)
      .saveAs(`${excelName}.xlsx`);
  };

  const handleExport = () => {
    exportToExcel(
      totalRecord,
      filter,
      { getAll: getApi },
      handleExportToExcel,
      nth,
      nthBy,
      items,
    );
  };

  function confirm() {
    Modal.confirm({
      title: titleConfirmRemove(selects.length),
      icon: <ExclamationCircleOutlined />,
      content: (
        <div className="language__multipleDelete--content">
          {items
            .filter(row => selects.includes(row[idKey] || row.id))
            .map(row => row[deleteContentKey] || row[idKey] || row.title)
            .join(', ')}
        </div>
      ),
      okText: t('typework.okConfirm'),
      cancelText: t('typework.cancelConfirm'),
      onOk: () => handleDeleteMany(),
      getContainer: document.querySelector('.table-fullscreen'),
    });
  }

  const handleDeleteMany = async () => {
    try {
      await deleteManyApi(selects);
      message.success(t('typework.deleteSuccessfully'));
      fetchData();
      setSelects([]);
    } catch (res) {
      if (!res.status) message.error(t('typework.networkError'));
      if (res.data.message.includes('delete'))
        message.error(t('basicTable.depenDataCaseMany'));
    }
  };

  const definedButtons = {
    export: (
      <Tooltip title={t('language.exportTooltip')} color="blue">
        <NoBorderButton
          onClick={handleExport}
          icon={<ExportOutlined style={{ fontSize: 20 }} />}
        ></NoBorderButton>
      </Tooltip>
    ),
    toggle: (
      <Dropdown
        overlay={EyeMenu}
        trigger={['click']}
        visible={visibleDropdown}
        onVisibleChange={setVisibleDropdown}
        getPopupContainer={triggerNode => triggerNode.parentNode}
        placement="bottomRight"
      >
        <Tooltip title={t('language.showColumnTooltip')} color="blue">
          <NoBorderButton
            icon={<EyeOutlined style={{ fontSize: 20 }} />}
          ></NoBorderButton>
        </Tooltip>
      </Dropdown>
    ),
    fullscreen: (
      <Tooltip title={t('language.fullScreenTooltip')} color="blue">
        <FullScreenButton handleFullScreen={handleFullScreen} />
      </Tooltip>
    ),
  };

  return (
    <div className="action">
      {deletePermission && !removeDeleteMany && (
        <div className="action-all">
          <Button
            type="danger"
            icon={<DeleteOutlined />}
            disabled={selects.length === 0}
            onClick={confirm}
          >
            {t('typework.deleteMany')}
          </Button>
          {selects.length > 0 && (
            <Button
              danger
              style={{
                marginLeft: 10,
                cursor: 'default',
                pointerEvents: 'none',
              }}
              icon={<CheckOutlined />}
            >
              {`${t('typework.selected')}: ${selects.length}`}
            </Button>
          )}
        </div>
      )}

      <div
        className="action-btn"
        style={{ textAlign: 'end', flex: 1, justifyContent: 'flex-end' }}
      >
        <div style={{ display: 'inline-flex', gap: 6 }}>
          {buttons.map((button, i) => {
            if (typeof button === 'string') {
              const defined = definedButtons[button];
              if (defined)
                return <React.Fragment key={i}>{defined}</React.Fragment>;
              return null;
            }
            return <React.Fragment key={i}>{button}</React.Fragment>;
          })}
        </div>
      </div>
    </div>
  );
};
export default UtilitiesBars;
