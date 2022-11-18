import { SettingOutlined } from '@ant-design/icons';
import { Button } from 'antd';
import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useFetchRelease } from '../../../components/Hooks/FetchApi';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import { breadcrumbNameMap } from './constant';
import moment from 'moment';
import { DATE_FORMAT } from '../../../constants';

const View = () => {
  const { items } = useFetchRelease();
  return (
    <div className="release-view">
      {items?.map(version => (
        <div className="version">
          <span className="number">{version?.version}</span>
          {version.daterelease && (
            <span className="release-date">{`Ngày: ${moment(
              version.daterelease,
            ).format(DATE_FORMAT)}`}</span>
          )}
          <pre className="content">{version?.conent}</pre>
        </div>
      ))}
    </div>
  );
};
export default function ReleaseView() {
  const navigate = useNavigate();
  return (
    <LayoutBreadcrumb
      breadcrumbNameMap={breadcrumbNameMap}
      extra={[
        <Button
          type="primary"
          icon={<SettingOutlined />}
          onClick={() => navigate('/setting/release/config')}
        >
          CẤU HÌNH
        </Button>,
      ]}
      component={<View />}
    />
  );
}
