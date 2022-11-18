import { CloudUploadOutlined } from '@ant-design/icons';
import { Button, message, Upload } from 'antd';
import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function UploadCv({ cvFile, setCvFile }) {
  const { t } = useTranslation();
  const [isShowFileName, setIsShowFileName] = useState(false);

  const dummyRequest = ({ file, onSuccess }) => {
    onSuccess('ok');
  };
  const onUploadFile = async files => {
    if (files.file.status === 'done') {
      const file = files.file.originFileObj;
      setCvFile(file);
    }
  };
  const beforeUploadFile = file => {
    if (file.size / 1024 / 1024 > 10) {
      const content = t('updateCv.fileMaxSize');
      message.error(content);
      setIsShowFileName(false);
      return false;
    } else {
      setIsShowFileName(true);
      return true;
    }
  };
  return (
    <Upload
      maxCount={1}
      onChange={onUploadFile}
      customRequest={dummyRequest}
      beforeUpload={beforeUploadFile}
      showUploadList={!!cvFile && isShowFileName}
      accept=".pdf"
    >
      <Button icon={<CloudUploadOutlined />} type="dashed">
        Upload Cv
      </Button>
    </Upload>
  );
}
