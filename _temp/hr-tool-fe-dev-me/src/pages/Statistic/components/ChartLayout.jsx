import { FileImageOutlined } from '@ant-design/icons';
import { Button, Dropdown, Menu } from 'antd';
import moment from 'moment';
import qs from 'query-string';
import { memo } from 'react';
import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import Action from '../../../assets/images/statistic/action.svg';
import { DAY_FORMAT } from '../../../constants';

function ChartLayout({
  children,
  onExport = () => {},
  height = 470,
  chartTitle = '',
}) {
  const { t } = useTranslation();
  const { search } = useLocation();
  const queryParams = qs.parse(search);

  return (
    <section
      className="statistic__sub-info"
      style={{
        height,
      }}
    >
      <div className="statistic__sub-info--item sub-info__item">
        <div className="item">
          <div className="item__title">
            <h4 className="statistic__chart--title chart">
              {t(`statistic.chartTitle.${chartTitle}`)}
            </h4>
            <p className="item__time">{`${moment(queryParams.from).format(
              DAY_FORMAT,
            )} - ${moment(queryParams.to).format(DAY_FORMAT)}`}</p>
          </div>
          <Dropdown
            overlay={
              <Menu>
                <Menu.Item key={1}>
                  <Button type="text" onClick={onExport}>
                    <FileImageOutlined />
                    {t('statistic.export')}
                  </Button>
                </Menu.Item>
              </Menu>
            }
            placement="bottomRight"
          >
            <Button type="text" className="btn-action">
              <img src={Action} alt="action" />
            </Button>
          </Dropdown>
        </div>
        {children}
      </div>
    </section>
  );
}

export default memo(ChartLayout);
