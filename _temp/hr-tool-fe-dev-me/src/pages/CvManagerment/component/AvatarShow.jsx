import { DeleteOutlined, PlusOutlined } from '@ant-design/icons';
import React, { useEffect, useState } from 'react';
import '../scss/avatarShow.scss';
import userDefault from '../../../assets/images/cvManagement/userDefault.png';
import ImgCrop from 'antd-img-crop';
import { Button, message, Upload } from 'antd';
import { useTranslation } from 'react-i18next';
import Lightbox from 'react-image-lightbox';

export default function AvatarShow({ avatarList, setAvatarList, onlyView }) {
  const { t } = useTranslation();
  const [avatar, setAvatar] = useState(null);
  const [isClickImage, setIsClickImage] = useState(false);

  const handleDeleteImage = () => {
    setAvatar(avatar === 0 ? (avatarList.length >= 2 ? 0 : null) : avatar - 1);
    setAvatarList(avatarList.filter((_, id) => id !== avatar));
    URL.revokeObjectURL(avatarList[avatar].show);
  };

  const dummyRequest = ({ file, onSuccess }) => {
    onSuccess('ok');
  };

  useEffect(() => {
    if (avatarList) {
      if (avatarList.length > 0 && avatar === null) {
        setAvatar(0);
      }
    }
  }, [avatarList]);

  const beforeCrop = file => {
    if (file.size / 1024 / 1024 > 5) {
      message.error(t('updateCv.imageMaxSize'));
      return false;
    }
    return true;
  };

  const onUploadImage = async files => {
    if (files.file.status === 'done') {
      const file = files.file.originFileObj;
      setAvatarList([
        ...avatarList,
        { show: URL.createObjectURL(file), file: file },
      ]);
      setAvatar(avatarList.length);
    }
  };

  return (
    <div className="avatar-show">
      <div className="avatar">
        <div style={{ position: 'relative' }}>
          <img
            src={
              avatarList?.length > 0 ? avatarList[avatar]?.show : userDefault
            }
            alt=""
            className="avatar-img"
            onClick={() => setIsClickImage(true)}
          />
          {avatar !== null && !onlyView && (
            <span className="delete-btn" onClick={handleDeleteImage}>
              <DeleteOutlined style={{ fontSize: 15 }} />
            </span>
          )}
          {isClickImage && (
            <Lightbox
              onCloseRequest={() => setIsClickImage(false)}
              mainSrc={
                avatarList?.length > 0 ? avatarList[avatar].show : userDefault
              }
              nextSrc={
                avatarList.length <= 1
                  ? undefined
                  : avatarList[(avatar + 1) % avatarList.length].show
              }
              prevSrc={
                avatarList.length <= 1
                  ? undefined
                  : avatarList[
                      (avatar + avatarList.length - 1) % avatarList.length
                    ].show
              }
              onMovePrevRequest={() =>
                setAvatar((avatar + avatarList.length - 1) % avatarList.length)
              }
              onMoveNextRequest={() =>
                setAvatar((avatar + 1) % avatarList.length)
              }
              imageLoadErrorMessage={t('cvDetail.loadImageFailed')}
            />
          )}
        </div>
      </div>

      {(avatarList?.length > 0 || !onlyView) && (
        <div className="list">
          {avatarList?.length > 0 &&
            avatarList?.map((img, i) => (
              <img
                key={i}
                src={img.show}
                style={{ opacity: i === avatar ? 1 : 0.5 }}
                onClick={() => setAvatar(i)}
                alt=""
              />
            ))}
          {!onlyView && (
            <ImgCrop rotate beforeCrop={beforeCrop}>
              <Upload
                onChange={onUploadImage}
                showUploadList={false}
                maxCount={1}
                customRequest={dummyRequest}
              >
                <Button type="dashed" icon={<PlusOutlined />}></Button>
              </Upload>
            </ImgCrop>
          )}
        </div>
      )}
    </div>
  );
}
