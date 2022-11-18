import BreadCrumb from '../../../components/Breadcrumb';

export default function LayoutBreadcrumb({
  breadcrumbNameMap,
  extra,
  component,
  className,
}) {
  return (
    <div className={className}>
      <BreadCrumb breadcrumbNameMap={breadcrumbNameMap} extra={extra} />
      {component && <div style={{ padding: '0 26px' }}>{component}</div>}
    </div>
  );
}
