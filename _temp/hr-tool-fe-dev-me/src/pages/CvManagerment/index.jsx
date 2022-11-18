import { ImportOutlined, UserOutlined } from '@ant-design/icons';
import { Button } from 'antd';
import queryString from 'query-string';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import cvApi from '../../api/cvApi';
import BreadCrumb from '../../components/Breadcrumb';
import { AddBlueButton } from '../../components/Buttons';
import NoPermission from '../../components/NoPermission';
import { TableMain, useTable } from '../../components/Table';
import { breadcrumbsCv } from '../../constants/newAddCv';
import { hasPermission } from '../../utils/hasPermission';
import Filter from './component/Filter';
import Import from './component/Import';
import { createCvColumns } from './constant';
function CvManagerment() {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const [visible, setVisible] = useState(false);

  const { filter, setFilter, items, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: cvApi.getAll,
    });

  const { userInfor } = useSelector(state => state.auth);

  useEffect(() => {
    navigate({
      pathname: window.location.pathname,
      search: queryString.stringify(filter, {
        skipEmptyString: true,
      }),
    });
  }, [navigate, filter]);
  const cols = [
    ...createCvColumns(),

    {
      width: '6%',
      type: 'action',
      fixed: 'right',
    },
  ];

  //Bấm nút tạo mới
  const handleCreate = e => {
    navigate('/cv/add');
  };

  const disableCheckbox = record => ({
    disabled: record.status !== 0,
  });

  const actionButtons = [
    {
      component: record => (
        <Button
          icon={<UserOutlined />}
          onClick={() => navigate('/cv/' + record.id)}
        >
          {t('request.detail')}
        </Button>
      ),
    },
    'delete',
  ];

  const headerButtons = [
    <Button
      onClick={() => setVisible(true)}
      type="primary"
      icon={<ImportOutlined />}
    >
      IMPORT
    </Button>,

    hasPermission(userInfor, 'cv', 'add') && (
      <AddBlueButton onClick={handleCreate}>TẠO CV</AddBlueButton>
    ),
  ];

  const onDoubleClickRow = record => {
    window.open(`/cv/${record.id}`, '_blank');
  };

  return hasPermission(userInfor, 'cv', 'view') ? (
    <div className="cv__managerment">
      <div className="cv__managerment">
        <BreadCrumb breadcrumbNameMap={breadcrumbsCv} extra={headerButtons} />
        <div className="cv__managerment--content">
          <Filter filter={filter} setFilter={setFilter} />
          <Import visible={visible} setVisible={setVisible} columns={cols} />

          <TableMain
            disableCheckbox={disableCheckbox}
            removeDeleteMany
            cols={cols}
            callback={createCvColumns}
            nth
            titleLabel={t('cv.cv')}
            items={items}
            title={t('cv.cv')}
            actionButtons={actionButtons}
            fetchData={fetchData}
            deleteApi={cvApi.delete}
            deleteContentKey="fullname"
            getApi={cvApi.getAll}
            deletePermission={hasPermission(userInfor, 'cv', 'delete')}
            editPermission={hasPermission(userInfor, 'cv', 'edit')}
            filter={filter}
            Filter={Filter}
            onRow={record => ({
              onDoubleClick() {
                onDoubleClickRow(record);
              },
            })}
            setFilter={setFilter}
            totalRecord={totalRecord}
            loadingTable={loadingTable}
            excelName="cv-templates"
          />
        </div>
      </div>
    </div>
  ) : (
    <NoPermission />
  );
}

export default CvManagerment;
