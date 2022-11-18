import React, { useEffect, useRef, useState } from 'react';
import { Button, Form, Input, Drawer, Radio, Select, message } from 'antd';
import { useDispatch, useSelector } from 'react-redux';
import {
  changeVisibleDrawer,
  setReloadTable,
} from '../../commonSlice/userSlice';
import { EditFilled, PlusCircleFilled } from '@ant-design/icons';
import { FORM_FIELD, STATUS_FIELD } from './constant';
import { settingUserApi } from '../../../../api/settingUserApi';
import { USER_URL } from '../../../../constants/api';
import { useTranslation } from 'react-i18next';
import { messageAction } from '../userBody/constant';
/**
 * @author
 * @function UserModal
 **/

const { Option } = Select;

export const UserDrawer = props => {
  const filterOption = (input, option) =>
    option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0;
  const dispatch = useDispatch();
  const [disabled, setDisabled] = useState(true);
  const { t } = useTranslation();
  const { edit } = useSelector(state => state.user);
  const onClose = () => {
    props.form.setFieldsValue({
      username: '',
      fullname: '',
      email: '',
      role_id: null,
      status: 1,
    });
    dispatch(changeVisibleDrawer(false));
  };
  const [loading, setLoading] = useState(false);
  const handleOk = () => {
    setLoading(true);
    submitBtn.current.click();
  };
  const handleFieldChange = (_, val) => {
    const error = val.every(e => e.errors.length === 0);
    const value = val.find(
      e => e.name[0] !== 'id' && e.name[0] !== 'status' && !e.value,
    );
    if (error && !value) {
      setDisabled(false);
    } else {
      setDisabled(true);
    }
  };

  const onFinishFailed = () => {
    setLoading(false);
  };

  useEffect(() => {
    if (!edit) {
      setDisabled(true);
    } else {
      setDisabled(false);
    }
  }, [edit, props.visible]);
  const onFinish = async values => {
    try {
      if (edit) {
        const res = await settingUserApi.updateData(USER_URL, values.id, {
          ...values,
          fullname: values.fullname.trim().replace('  ', ' '),
        });
        if (res.data?.status === 'success') {
          dispatch(changeVisibleDrawer(false));
          props.form.setFieldsValue({
            username: '',
            fullname: '',
            email: '',
            role_id: null,
            status: 1,
          });
          message.success(t(`user.${messageAction.updateSuccess}`));
          setLoading(false);
          dispatch(setReloadTable());
        } else if (res.data?.code === 'dberror') {
          message.warn(t(`user.${messageAction.createWarn}`));
          setLoading(false);
        } else {
          setLoading(false);
          message.warn(t(`user.${messageAction.createWarn}`));
        }
      } else {
        const res = await settingUserApi.addNew(USER_URL, {
          ...values,
          fullname: values.fullname.trim().replace('  ', ' '),
        });
        if (res.data?.status === 'success') {
          dispatch(changeVisibleDrawer(false));
          props.form.setFieldsValue({
            username: '',
            fullname: '',
            email: '',
            role_id: null,
            status: 1,
          });
          message.success(t(`user.${messageAction.createSuccess}`));
          setLoading(false);
          dispatch(setReloadTable());
        } else if (res.data?.code === 'dberror') {
          message.warn(t(`user.${messageAction.createWarn}`));
          setLoading(false);
        } else {
          setLoading(false);
          message.warn(t(`user.${messageAction.createWarn}`));
        }
      }
    } catch (err) {
      if (err?.data?.status === 'success') {
        edit
          ? message.success(t('user.updateSuccess'))
          : message.success(t('user.createSuccess'));
        dispatch(setReloadTable());
        props.form.setFieldsValue({
          username: '',
          fullname: '',
          email: '',
          role_id: null,
          status: 1,
        });
        dispatch(changeVisibleDrawer(false));
      } else if (err?.data?.message?.includes('exists')) {
        message.warn(t('user.updateWarn'));
      } else {
        message.error(t('user.installErr'));
      }
      setLoading(false);
    }
  };
  const submitBtn = useRef();
  return (
    <>
      <Drawer
        title={edit ? t('user.modalLabelEdit') : t('user.titleModalAdd')}
        placement="right"
        onClose={onClose}
        visible={props.visible}
        className="drawer-add"
        getContainer={
          document.querySelector('.fullscreen-enabled')
            ? document.querySelector('.fullscreen-enabled')
            : document.body
        }
        footer={[
          <Button
            key="submit"
            type="primary"
            icon={edit ? <EditFilled /> : <PlusCircleFilled />}
            loading={loading}
            onClick={handleOk}
            disabled={disabled}
          >
            {edit ? t('user.edit') : t('user.create')}
          </Button>,
        ]}
      >
        <Form
          form={props.form}
          name="control-hooks"
          onFinish={onFinish}
          onFinishFailed={onFinishFailed}
          layout={'vertical'}
          onFieldsChange={handleFieldChange}
        >
          {FORM_FIELD.map((e, i) => {
            return (
              <Form.Item
                key={i}
                name={e.name}
                label={
                  <>
                    {t(`user.${e.name}`)} <RequireMark />{' '}
                  </>
                }
                rules={e.rule}
                hidden={e.hidden}
              >
                <Input
                  placeholder={t(`user.${e.placehoder}`)}
                  maxLength={e.maxlength}
                  disabled={edit ? e.disabled : false}
                />
              </Form.Item>
            );
          })}
          <Form.Item
            name="role_id"
            label={
              <>
                {t(`user.role_id`)} <RequireMark />
              </>
            }
            rules={[
              {
                required: true,
                message:
                  t(`user.${messageAction.validateRequireMessage}`) +
                  ' ' +
                  t('user.role_id'),
              },
            ]}
          >
            <Select
              placeholder={t('user.dropdownplace')}
              showSearch
              allowClear
              filterOption={filterOption}
            >
              {props.roleApi &&
                props.roleApi.map(e => (
                  <Option key={e.id} value={e.id}>
                    {e.title}
                  </Option>
                ))}
            </Select>
          </Form.Item>
          <Form.Item
            name="status"
            label={t('user.statusLabelModal')}
            rules={[
              {
                required: true,
                message: t('user.validateRequireMessage'),
              },
            ]}
            initialValue={1}
          >
            <Radio.Group>
              {STATUS_FIELD.map((e, i) => (
                <Radio value={i} key={i}>
                  {e}
                </Radio>
              ))}
            </Radio.Group>
          </Form.Item>
          <Form.Item shouldUpdate>
            {() => {
              return (
                <Button type="primary" htmlType="submit" ref={submitBtn} hidden>
                  login
                </Button>
              );
            }}
          </Form.Item>
        </Form>
      </Drawer>
    </>
  );
};

export const RequireMark = () => (
  <>
    <span style={{ marginLeft: '4px' }}>(</span>
    <span style={{ color: '#ff4d4f' }}>*</span>
    <span>)</span>
  </>
);
