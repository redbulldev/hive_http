import { EditOutlined, PlusCircleFilled } from '@ant-design/icons';
import { Button, Checkbox, Drawer, Form, Input, message, Radio } from 'antd';
import { useEffect, useRef, useState } from 'react';

import { useTranslation } from 'react-i18next';
import { settingUserApi } from '../../../../../api/settingUserApi';
import { RequireMark } from '../../../User/userModal/index';
import { STATUS_OPTION } from './constant';
import './drawer.scss';
/**
 * @author
 * @function DrawerAddRole
 **/

export const DrawerAddRole = props => {
  const { permissionApi, checkList, setCheckList, isEdit, form, fetchData } =
    props;
  const [check, setCheck] = useState({});
  const [checkAllOption, setCheckAllOption] = useState([]);
  const [disabled, setDisabled] = useState(true);

  const [isCheckAll, setIsCheckAll] = useState(false);
  const submitBtn = useRef();
  // parse checklist to opject
  useEffect(() => {
    const permission = {};
    checkList.map(e => {
      let arr = e.split('.');
      let key = arr[0];
      let value = arr[1];
      permission[key] = { ...permission[key], [value]: true };
      return e;
    });
    setCheck(permission);
  }, [checkList]);
  // parse All option
  useEffect(() => {
    permissionApi.map(e => {
      const action = JSON.parse(e.action);
      action.map(element => {
        setCheckAllOption(prev => [...prev, `${e.alias}.${element}`]);
        return element;
      });
      return e;
    });
  }, [permissionApi]);
  //reset all
  useEffect(() => {
    if (!isEdit) {
      setIsCheckAll(false);
    }
  }, [isEdit]);
  const { t } = useTranslation();
  const onCloseDrawer = () => {
    props.closeDrawer(false);
    setCheckList([]);
    form.setFieldsValue({
      title: '',
      status: 1,
    });
  };
  const onCheckChange = val => {
    setCheckList(val);
  };
  useEffect(() => {
    if (!isEdit) {
      setDisabled(true);
    } else {
      setDisabled(false);
    }
  }, [props.visible, isEdit]);
  const onSubmitForm = async val => {
    try {
      if (check) {
        if (!isEdit) {
          await settingUserApi.create({
            ...val,
            title: val.title.trim(),
            permission: check,
          });
          props.closeDrawer(false);
          setCheckList([]);
          form.setFieldsValue({
            title: '',
            status: 1,
          });
          setIsCheckAll(false);
          fetchData();
          message.success(t('role.addRoleSuccess'));
        } else {
          await settingUserApi.edit(val.id, {
            ...val,
            permission: check,
          });
          setCheckList([]);
          form.setFieldsValue({
            title: '',
            status: 1,
          });
          setIsCheckAll(false);
          message.success(t('role.editRoleSuccess'));
          fetchData();

          props.closeDrawer(false);
        }
      }
    } catch (err) {
      if (err?.data?.status === 'success') {
        setCheckList([]);
        form.setFieldsValue({
          title: '',
          status: 1,
        });
        setIsCheckAll(false);
        isEdit
          ? message.success(t('role.editRoleSuccess'))
          : message.success(t('role.addRoleSuccess'));
        fetchData();

        props.closeDrawer(false);
      } else if (err?.data?.message?.includes('exists')) {
        message.warn(t('role.createRoleWarn'));
      } else {
        message.error(t('user.installErr'));
      }
    }
  };
  const checkAllChange = e => {
    setCheckList(e.target.checked ? checkAllOption : []);
    setIsCheckAll(e.target.checked);
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
  return (
    <>
      <Drawer
        title={isEdit ? t('role.titleModalEdit') : t('role.titleModalAdd')}
        placement="right"
        visible={props.visible}
        onClose={onCloseDrawer}
        className="drawer-add add-role"
        getPopupContainer={() => document.querySelector('.table-fullscreen')}
        footer={[
          <Button
            key="submit"
            type="primary"
            icon={isEdit ? <EditOutlined /> : <PlusCircleFilled />}
            onClick={() => {
              submitBtn.current.click();
            }}
            disabled={disabled}
          >
            {isEdit ? t('user.edit') : t('user.create')}
          </Button>,
        ]}
      >
        <Form
          form={form}
          layout="vertical"
          onFinish={onSubmitForm}
          onFieldsChange={handleFieldChange}
        >
          <Form.Item
            name={'title'}
            label={
              <>
                {t('role.role')} <RequireMark />{' '}
              </>
            }
            rules={[
              {
                required: true,
                message: t('user.validateRequireMessageInput'),
              },
              {
                validator: (_, val) => {
                  let message = t('role.requireTitle');
                  let check = false;
                  if (val?.trim().length >= 3 && val?.trim().length <= 200) {
                    check = true;
                    message = '';
                  }
                  if (!val) {
                    check = true;
                    message = '';
                  }
                  return check
                    ? Promise.resolve(message)
                    : Promise.reject(message);
                },
              },
            ]}
          >
            <Input placeholder={t('user.roleplace')} maxLength={200} />
          </Form.Item>
          {isEdit && (
            <Form.Item name={'id'} hidden>
              <Input />
            </Form.Item>
          )}
          <p className="role-permission__label">{t('role.permission')}</p>
          <div className="permission-box">
            <Checkbox onChange={checkAllChange} checked={isCheckAll}>
              {t('role.checkAll')}
            </Checkbox>
            <Checkbox.Group onChange={onCheckChange} value={checkList}>
              <table>
                <thead>
                  <tr>
                    <th className="thead-title"></th>
                    <th>{t('role.menu')}</th>
                    <th>{t('role.view')}</th>
                    <th>{t('role.add')}</th>
                    <th>{t('role.edit')}</th>
                    <th>{t('role.delete')}</th>
                    <th>{t('role.decision')}</th>
                    <th>{t('role.all')}</th>
                  </tr>
                </thead>
                <tbody>
                  {permissionApi &&
                    permissionApi.map(e => {
                      let action = JSON.parse(e.action);
                      return (
                        <tr key={e.id}>
                          <td className="text-left">{e.title}</td>
                          <td>
                            {action.includes('menu') ? (
                              <Checkbox value={`${e.alias}.menu`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('view') ? (
                              <Checkbox value={`${e.alias}.view`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('add') ? (
                              <Checkbox value={`${e.alias}.add`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('edit') ? (
                              <Checkbox value={`${e.alias}.edit`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('delete') ? (
                              <Checkbox value={`${e.alias}.delete`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('decision') ? (
                              <Checkbox value={`${e.alias}.decision`} />
                            ) : (
                              ''
                            )}
                          </td>
                          <td>
                            {action.includes('all') ? (
                              <Checkbox value={`${e.alias}.all`} />
                            ) : (
                              ''
                            )}
                          </td>
                        </tr>
                      );
                    })}
                </tbody>
              </table>
            </Checkbox.Group>
          </div>
          <Form.Item
            name="status"
            label={t('user.status')}
            initialValue={1}
            className="role-status__label"
          >
            <Radio.Group>
              {STATUS_OPTION.map(e => (
                <Radio value={e.value} key={e.value}>
                  {e.title}
                </Radio>
              ))}
            </Radio.Group>
          </Form.Item>
          <Form.Item>
            <Button htmlType="submit" ref={submitBtn} hidden>
              subnit
            </Button>
          </Form.Item>
        </Form>
      </Drawer>
    </>
  );
};
