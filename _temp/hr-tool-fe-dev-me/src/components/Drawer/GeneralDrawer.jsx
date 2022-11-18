import { Drawer, Form, message, Modal } from 'antd';
import React, { memo, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { DEFAULT_FILTER } from '../../constants';
import { SubmitBtn } from '../Form';
import { setIsOpenedDrawer } from './slice/drawer';
import './styles.scss';

const PopUp = memo(
  ({
    children,
    modal,
    title,
    onClose,
    visible,
    bodyStyle,
    getContainer,
    width,
    className,
  }) => {
    return modal ? (
      <Modal
        title={title}
        visible={visible}
        onCancel={onClose}
        footer={null}
        centered
        className="addCv custom-modal"
        bodyStyle={bodyStyle}
        width={width || '70%'}
        getContainer={getContainer}
      >
        {children}
      </Modal>
    ) : (
      <Drawer
        title={title}
        placement="right"
        onClose={onClose}
        visible={visible}
        forceRender
        width={width || '20%'}
        zIndex={2000}
        className={className}
        getContainer={getContainer}
      >
        {children}
      </Drawer>
    );
  },
);

export default function GeneralDrawer({
  FormContent,
  form,
  onFinish,
  fetchData,
  fillData,
  addApi,
  setFilter,
  editApi,
  idKey,
  catchCallback,
  transformValues,
  height = 750,
  width = '30%',
  modal = true,
  fullscreenClassName,
  requiredFields = [],
  className,
}) {
  const bodyStyle = {};
  if (height) {
    bodyStyle.minHeight = height;
  }
  const { isOpened, modeText, mode, initial } = useSelector(
    state => state.drawer,
  );
  const { isFullscreen } = useSelector(state => state.common);
  const dispatch = useDispatch();
  const { t } = useTranslation();

  const [loadingBtn, setLoadingBtn] = useState(false);

  const fieldsChange = () => {
    // console.log(form.getFieldsValue());
  };

  useEffect(() => {
    if (fillData) fillData();
  }, [isOpened]);

  const onFinishDefault = async values => {
    if (mode === 'add' && addApi) {
      await addApi(values);
    }
    const id = initial?.[idKey] || initial?.id;
    if (mode === 'edit' && editApi && id) {
      await editApi(id, values);
    }
  };

  const onFinishForm = async values => {
    setLoadingBtn(true);
    try {
      for (let key in values) {
        if (typeof values[key] === 'string') values[key] = values[key].trim();
      }
      if (transformValues) values = transformValues(values);
      const finish = onFinish || onFinishDefault;
      await finish(values);
      if (mode === 'add' && setFilter) setFilter(DEFAULT_FILTER);
      if (fetchData) {
        fetchData();
      }
      dispatch(setIsOpenedDrawer(false));
      setLoadingBtn(false);
      message.success(`${modeText.title} ${t('common.success')}`);
    } catch (e) {
      setLoadingBtn(false);
      if (!e.status) message.error(t('typework.networkError'));
      if (catchCallback) catchCallback(e);
      console.log(e);
    }
  };

  return (
    <PopUp
      modal={modal}
      title={modeText.title}
      placement="right"
      onClose={() => dispatch(setIsOpenedDrawer(false))}
      visible={isOpened}
      bodyStyle={bodyStyle}
      forceRender
      width={width}
      zIndex={2000}
      className={className}
      getContainer={
        isFullscreen
          ? document.querySelector(`.${fullscreenClassName}`)
          : document.body
      }
    >
      <Form
        form={form}
        layout="vertical"
        className="d-flex standard-form"
        onFinish={onFinishForm}
        style={{ flexDirection: 'column', minHeight: 'inherit' }}
        onValuesChange={fieldsChange}
      >
        <div className="content-wrapper">
          <FormContent />
        </div>
        <SubmitBtn
          style={{ marginTop: 'auto', marginBottom: 0 }}
          form={form}
          loading={loadingBtn}
          requiredFields={requiredFields}
          create={mode === 'add'}
        >
          {modeText.btn}
        </SubmitBtn>
      </Form>
    </PopUp>
  );
}
