import {
  CheckCircleFilled,
  CloseCircleFilled,
  EllipsisOutlined,
} from '@ant-design/icons';
import { Popover, Tooltip } from 'antd';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';

import './scss/useTableCols.scss';

export default function useTableMain({
  cols,
  nth = false,
  titleLabel,
  filter,
  ActionContent,
}) {
  const { t } = useTranslation();

  const getDefaultSortOrder = col => {
    const { orderby } = filter;
    if (!orderby) return false;
    if (!orderby.includes(col)) return false;
    else {
      if (orderby.includes('ASC')) return 'ascend';
      if (orderby.includes('DESC')) return 'descend';
    }
  };

  const definedCols = {
    // Begin: General col
    allWithoutSort: {
      show: true,
      export: true,
    },
    all: dataIndex => ({
      defaultSortOrder: getDefaultSortOrder(dataIndex),
      show: true,
      export: true,
      sorter: true,
    }),
    tooltip: keyName => ({
      title: t('typework.description'),
      dataIndex: keyName,
      defaultSortOrder: getDefaultSortOrder(keyName),
      width: 500,
      show: true,
      export: true,
      sorter: true,
      render: value => (
        <Tooltip title={value} placement="topLeft">
          <div className="text-truncate">{value || ''}</div>
        </Tooltip>
      ),
    }),
    // End: General col
    id: {
      title: '#',
      width: 41,
      defaultSortOrder: getDefaultSortOrder('id'),
      dataIndex: nth ? 'nth' : 'id',
      show: true,
      export: true,
      sorter: true,
    },
    title: keyName => ({
      title: titleLabel,
      dataIndex: keyName,
      defaultSortOrder: getDefaultSortOrder(keyName),
      width: 200,
      show: true,
      export: true,
      sorter: true,
      render: value => <div className="text-truncate">{value || ''}</div>,
    }),
    description: {
      title: t('typework.description'),
      dataIndex: 'description',
      defaultSortOrder: getDefaultSortOrder('description'),
      width: 700,
      show: true,
      export: true,
      sorter: true,
      render: value => <div className="text-truncate">{value || ''}</div>,
    },

    status: keyName => ({
      title: t('typework.status'),
      width: 100,
      dataIndex: keyName,
      align: 'center',
      render: value => {
        if (value) {
          return <CheckCircleFilled className="check-icon" />;
        } else {
          return <CloseCircleFilled className="close-icon" />;
        }
      },
      show: true,
      export: true,
    }),

    action: {
      title: t('typework.action'),
      width: 120,
      align: 'center',
      render: (_, record) => {
        return (
          <Popover
            placement="bottom"
            content={<ActionContent record={record} />}
            trigger="hover"
            overlayClassName="overlayActionContent"
            getTooltipContainer={triggerNode => triggerNode.parentNode}
            getPopupContainer={() =>
              document.querySelector('.table-fullscreen')
            }
          >
            <span className="action-icon">
              <EllipsisOutlined />
            </span>
          </Popover>
        );
      },
      show: true,
      export: false,
    },
  };

  const getDefaultColumns = () => {
    const getDefinedCol = (type, col) => {
      if (type === 'status') {
        if (!col.dataIndex) {
          return definedCols.status('status');
        } else {
          return definedCols.status(col.dataIndex);
        }
      }
      if (type === 'all') {
        if (!col.dataIndex) {
          return undefined;
        } else {
          return definedCols.all(col.dataIndex);
        }
      }
      if (type === 'tooltip') {
        if (!col.dataIndex) {
          return undefined;
        } else {
          return definedCols.tooltip(col.dataIndex);
        }
      }
      if (type === 'title') {
        if (!col.dataIndex) {
          return definedCols.title('title');
        } else {
          return definedCols.title(col.dataIndex);
        }
      }
      if (typeof col.defaultSortOrder === 'string') {
        return {
          ...col,
          defaultSortOrder: getDefaultSortOrder(col.defaultSortOrder),
        };
      }
      return definedCols[type];
    };

    return cols.map((col, id) => {
      const { type, ...rest } = col;
      const found = getDefinedCol(type, col);
      const defined = type && found ? found : {};
      const other = {};
      if (typeof col.defaultSortOrder === 'string') {
        other.defaultSortOrder = getDefaultSortOrder(col.defaultSortOrder);
      }
      return { id, colType: type, ...defined, ...rest, ...other };
    });
  };

  const [columns, setColumns] = useState(getDefaultColumns());

  return { columns, setColumns };
}
