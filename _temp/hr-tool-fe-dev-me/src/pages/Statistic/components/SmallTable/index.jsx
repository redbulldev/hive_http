import { Empty, Spin } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import './small-table.scss';

function SmallTable({ labels = [], values = [], maxHeight, loading = false }) {
  const { t } = useTranslation();
  const hasData =
    Array.isArray(labels) &&
    labels.length &&
    Array.isArray(values) &&
    values.length;

  return (
    <Spin spinning={loading} className="box-list box-shadow">
      <div className="small-table ant-table ant-table-bordered ant-table-fixed-header">
        <div className="ant-table-container">
          <div className="ant-table-header">
            <table
              style={{
                tableLayout: 'fixed',
              }}
            >
              <colgroup>
                <col
                  style={{
                    width: '3rem',
                  }}
                />
                <col />
                <col
                  style={{
                    width: '6rem',
                  }}
                />
              </colgroup>
              <thead className="ant-table-thead">
                <tr>
                  <th className="ant-table-cell">#</th>
                  <th className="ant-table-cell">
                    {t('statistic.department')}
                  </th>
                  <th className="ant-table-cell">{t('statistic.request')}</th>
                  <th className="ant-table-cell ant-table-cell-scrollbar"></th>
                </tr>
              </thead>
            </table>
          </div>
          <div
            className="ant-table-body"
            style={{
              overflow: 'auto auto',
              maxHeight,
            }}
          >
            <table
              style={{
                minWidth: '100%',
                tableLayout: 'fixed',
              }}
            >
              <colgroup>
                <col
                  style={{
                    width: '3rem',
                  }}
                />
                <col />
                <col
                  style={{
                    width: '6rem',
                  }}
                />
              </colgroup>
              <tbody className="ant-table-tbody">
                {hasData ? (
                  labels.map((label, index) => (
                    <tr
                      className="ant-table-row ant-table-row-level-0"
                      data-row-key={index}
                      key={index}
                    >
                      <td className="ant-table-cell">{index}</td>
                      <td className="ant-table-cell">{label}</td>
                      <td className="ant-table-cell">{values[index]}</td>
                    </tr>
                  ))
                ) : (
                  <Empty image={Empty.PRESENTED_IMAGE_SIMPLE} />
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </Spin>
  );
}

export default SmallTable;
