import { LoadingOutlined } from '@ant-design/icons';
import { Drawer, message, Modal, Table, Upload } from 'antd';
import 'moment/locale/en-au';
import { useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import importApi from '../../../api/importApi';
import ImportImage from '../../../assets/images/cvManagement/import.svg';
import '../../../assets/scss/_import.scss';
function Import({ visible, fullScreen, setVisible }) {
  const [placement] = useState('right');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(0);
  const [invalid, setInvalid] = useState(0);
  const [fileList, setFileList] = useState([]);
  const ref = useRef();
  const onClose = () => {
    setVisible(false);
    setFileList([]);
  };
  const { t } = useTranslation();

  function beforeUpload(file) {
    const isExcel =
      file.type ===
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
      file.type === 'application/pdf';
    if (!isExcel) {
      setFileList([]);
      message.error('You can only upload XLSX,XLS,CSV file!');
    } else {
      setFileList([file]);
    }
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
      message.error(t('cv.limited_file'));
    }
    return isExcel && isLt2M;
  }

  const dummyRequest = ({ file, onSuccess }) => {
    setTimeout(() => {
      onSuccess('ok');
    }, 0);
  };
  const handleImport = async file => {
    setLoading(true);

    try {
      const resp = await importApi.postFile(file);
      if (resp.data.error) {
        setError(resp.data.error);
        setInvalid(resp.data.data);
      }
      setLoading(false);
    } catch (error) {
      setLoading(false);

      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('cv.fail_to_import'));
      }
    }
  };

  const columns = [
    {
      title: 'Dòng',
      dataIndex: 'row',
    },
    {
      title: 'Họ và tên',
      dataIndex: 'fullname',
    },
    {
      title: 'Mô tả',
      dataIndex: 'message',
    },
  ];

  const uploadButton = (
    <div className="upload">
      <div
        style={{
          color: 'rgba(0, 0, 0, 0.5)',
          textAlign: 'center',
          flexGrow: 1,
        }}
      >
        Drag and drop file here or click
      </div>
      {loading ? (
        <LoadingOutlined />
      ) : (
        <div
          style={{
            display: 'flex',
            justifyContent: 'center',
            marginTop: '40px',
          }}
        >
          <img
            style={{ width: '30px', height: '27px' }}
            src={ImportImage}
            alt="import icon"
          />
        </div>
      )}
    </div>
  );
  const handleRemoveFile = file => {
    setFileList([]);
  };
  return (
    <Modal
      title={t('cv.import')}
      className="import-modal"
      width={600}
      visible={visible}
      onCancel={onClose}
      footer={false}
      getContainer={
        fullScreen ? document.querySelector('.full-screen') : document.body
      }
    >
      <Upload
        name="cv"
        listType="text"
        className="file-uploader"
        showUploadList={true}
        beforeUpload={beforeUpload}
        accept=".xlsx,.xls,.csv"
        ref={ref}
        action={handleImport}
        customRequest={dummyRequest}
        maxCount={1}
        fileList={fileList}
        onRemove={handleRemoveFile}
      >
        {uploadButton}
      </Upload>
      <p className="download-link">
        <a href="/CV-Import.xlsx" download>
          Tải xuống
        </a>{' '}
        File mẫu
      </p>
      <p className="note">
        {' '}
        <i>Chú ý: file không vượt quá 100 dòng</i>
      </p>
      {error.length && fileList.length ? (
        <div className="preview">
          <h3>Xem trước</h3>
          <p>Hợp lệ {`(${invalid})`}</p>
          <p>Không hợp lệ {`(${error.length})`}</p>
          <Table
            className="table-drawer"
            rowKey="row"
            columns={columns}
            dataSource={error}
            bordered
            pagination={false}
            scrollToFirstRowOnChange={true}
          />
        </div>
      ) : (
        ''
      )}
    </Modal>
  );
}

export default Import;
