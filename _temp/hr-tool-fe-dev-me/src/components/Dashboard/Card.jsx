import { InfoCircleOutlined } from '@ant-design/icons';
import { Button, Card, Tooltip, Row, Col } from 'antd';
import React from 'react';

function DashboardCard({ title, desc, subDesc }) {
  return (
    <div className="card">
      <div className="card__content">
        <div>
          <p>{desc}</p>
          <p style={{ textAlign: 'center' }}>{`(${subDesc})`}</p>
        </div>
        <Tooltip
          placement="topLeft"
          title={<span>{`${desc} : ${title}`}</span>}
        >
          <Button type="text" style={{ padding: 0 }}>
            <InfoCircleOutlined />
          </Button>
        </Tooltip>
      </div>
      <div className="card__number">
        <h4 className="total">{title ? title : 0}</h4>
      </div>
    </div>
  );
}

export default DashboardCard;
