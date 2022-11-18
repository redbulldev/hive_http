import { Breadcrumb, PageHeader } from 'antd';
import { Link, useLocation } from 'react-router-dom';

const BreadCrumb = ({ breadcrumbNameMap, extra }) => {
  const location = useLocation();
  const path = location.pathname.split('/').filter(i => i);

  const extraBreadcrumbItems = path.map((_, index) => {
    const url = `/${path.slice(0, index + 1).join('/')}`;
    return (
      breadcrumbNameMap[url] && (
        <Breadcrumb.Item key={url}>
          <Link
            to={
              breadcrumbNameMap.params
                ? `${url}${breadcrumbNameMap.params}`
                : url
            }
          >
            {breadcrumbNameMap[url]}
          </Link>
        </Breadcrumb.Item>
      )
    );
  });

  return (
    <PageHeader
      className="breadcrumb-header"
      title={<Breadcrumb>{extraBreadcrumbItems}</Breadcrumb>}
      subTitle=""
      style={{ marginBottom: 54 }}
      extra={[
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
          }}
        >
          {extra
            ? extra.map((x, i) => (
                <div key={i} style={{ marginLeft: 20 }}>
                  {x}
                </div>
              ))
            : ''}
        </div>,
      ]}
    />
  );
};

export default BreadCrumb;
