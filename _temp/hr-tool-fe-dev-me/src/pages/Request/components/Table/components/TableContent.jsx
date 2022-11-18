import { EllipsisOutlined, StopOutlined } from '@ant-design/icons';
import { Button, message, Modal, Popover } from 'antd';
import 'moment/locale/vi';
import { memo } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import requestApi from '../../../../../api/requestApi';
import removeIcon from '../../../../../assets/images/request/DeleteOutlined.svg';
import editIcon from '../../../../../assets/images/request/Edit.svg';
import detailIcon from '../../../../../assets/images/request/InfoCircle.svg';
import { TableMain } from '../../../../../components/Table';
import { titleConfirmRemove } from '../../../../../constants/languagePage';
import {
  createRequestColumns,
  LIST_REQUEST_STATUS,
} from '../../../../../constants/requestPage';
import { hasPermission } from '../../../../../utils/hasPermission';
import SearchForm from '../../SearchForm';

function ContentRequestTable({
  filter,
  setFilter,
  items,
  loadingTable,
  fetchData,
  totalRecord,
}) {
  const navi = useNavigate();
  const { t } = useTranslation();

  const userInfor = useSelector(state => state.auth.userInfor);

  const ContentAction = ({ record }) => {
    // add config to show message in fullscreen mode
    message.config({
      getContainer: node => {
        return node
          ? document.body
          : document.querySelector('.request__fullscreen');
      },
      duration: 2,
    });
    return (
      <div className="request__action">
        {Number(record.status) !== LIST_REQUEST_STATUS[1].value && (
          <Button
            className="request__action--detail"
            icon={<img src={detailIcon} alt="detail" />}
            size="large"
            onClick={() => handleRequestDetail(record)}
          >
            {t('request.detail')}
          </Button>
        )}
        {(record.status === LIST_REQUEST_STATUS[0].value ||
          record.status === LIST_REQUEST_STATUS[1].value) &&
          hasPermission(userInfor, 'request', 'edit') && (
            <Button
              className="request__action--edit"
              icon={<img src={editIcon} alt="detail" />}
              size="large"
              onClick={() => handleEditRequest(record)}
            >
              {t('request.edit')}
            </Button>
          )}
        {record.status !== LIST_REQUEST_STATUS[2].value &&
          hasPermission(userInfor, 'request', 'delete') && (
            <Button
              className="request__action--remove"
              icon={<img src={removeIcon} alt="detail" />}
              size="large"
              onClick={() => handleRemoveRequest(record)}
            >
              {t('request.remove')}
            </Button>
          )}
      </div>
    );
  };

  const handleRequestDetail = async record => {
    navi(`detail/${record.id}?status=${record.status}`);
  };

  const handleEditRequest = async record => {
    navi(`edit/${record.id}`);
  };

  const handleRemoveRequest = record => {
    Modal.confirm({
      title: titleConfirmRemove(),
      content: record.author_id,
      onOk() {
        handleConfirmOk(record.id);
      },
      okType: 'primary',
      okText: t('request.remove'),
      cancelText: t('request.cancel'),
      width: 450,
      icon: <StopOutlined style={{ color: '#f00' }} />,
      getContainer: document.querySelector('.request__fullscreen'),
    });
  };

  const handleConfirmOk = async requestId => {
    try {
      await requestApi.delete(requestId);
      message.success(t('request.deleteSuccessText'));
      fetchData();
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.deleteFailText'));
      }
    }
  };
  const Filter = () => <SearchForm filter={filter} setFilter={setFilter} />;
  const cols = [
    ...createRequestColumns(),
    {
      title: t('request.action'),
      width: '6%',
      fixed: 'right',
      render: (_, record) => {
        return (
          <Popover
            zIndex={999}
            content={<ContentAction record={record} />}
            trigger={'hover'}
            placement="bottom"
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
      type: 'action',
    },
  ];
  const disableCheckbox = record => ({
    disabled: record.status !== 0,
  });

  return (
    <>
      <TableMain
        disableCheckbox={disableCheckbox}
        cols={cols}
        callback={createRequestColumns}
        titleLabel={t('request.request')}
        items={items}
        title={t('request.request')}
        fetchData={fetchData}
        deleteApi={requestApi.delete}
        getApi={requestApi.getAll}
        deleteManyApi={requestApi.multiDelete}
        deletePermission={hasPermission(userInfor, 'request', 'delete')}
        editPermission={hasPermission(userInfor, 'request', 'edit')}
        filter={filter}
        Filter={Filter}
        setFilter={setFilter}
        totalRecord={totalRecord}
        loadingTable={loadingTable}
        excelName="request-templates"
      />
    </>
  );
}

export default memo(ContentRequestTable);
