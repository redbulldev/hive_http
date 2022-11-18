import { Checkbox, Form, message } from 'antd';
import React, { useCallback, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import GeneralDrawer from '../../../../components/Drawer/GeneralDrawer';
import { FormInput, FormRadio } from '../../../../components/Form';
import { DEFAULT_STATUS } from '../../../../constants';
import { status } from '../../Mail/constants';
import '../component/drawer/drawer.scss';

export default function ModalForm({
  permissionApi,
  addApi,
  editApi,
  ...props
}) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { initial, mode } = useSelector(state => state.drawer);

  const [checkList, setCheckList] = useState([]);
  const [isCheckAll, setIsCheckAll] = useState(false);
  const [checkAllOption, setCheckAllOption] = useState([]);
  const [check, setCheck] = useState({});

  // parse checklist to object
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

  useEffect(() => {
    if (initial?.permission) {
      const record = JSON.parse(initial.permission);
      let arr = [];
      for (let i in record) {
        for (let e in record[i]) {
          arr.push(`${i}.${e}`);
        }
      }
      setCheckList(arr);
    }
  }, [initial]);

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

  const onCheckChange = val => {
    setCheckList(val);
  };

  const checkAllChange = e => {
    setCheckList(e.target.checked ? checkAllOption : []);
    setIsCheckAll(e.target.checked);
  };

  const fillData = () => {
    form.resetFields();
    setCheck([]);
    setCheckList([]);
    setIsCheckAll(false);
    const init = mode === 'add' ? {} : initial;
    form.setFieldsValue({
      ...init,
      status: initial.status ?? DEFAULT_STATUS,
    });
  };

  const onFinish = async values => {
    if (check) {
      values = { ...values, permission: check };
      if (mode === 'add' && addApi) {
        await addApi(values);
      }
      const id = initial?.id;
      if (mode === 'edit' && editApi && id) {
        await editApi(id, values);
      }
    }
  };

  const catchCallback = err => {
    if (err?.data?.message?.includes('exists')) {
      message.warn(t('role.createRoleWarn'));
    } else {
      message.error(t('user.installErr'));
    }
  };

  const FormContent = useCallback(() => {
    return (
      <>
        <FormInput
          name="title"
          placeholder={t('user.roleplace')}
          label={t('role.role')}
          min={3}
          required
        />
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
        <FormRadio label={t('common.status')} name="status" items={status} />
      </>
    );
  }, [permissionApi, isCheckAll, checkList]);

  return (
    <GeneralDrawer
      {...props}
      fillData={fillData}
      form={form}
      FormContent={FormContent}
      onFinish={onFinish}
      modal
      catchCallback={catchCallback}
      width="35%"
      className="role-content"
      requiredFields={['title']}
      fullscreenClassName="table-fullscreen"
    />
  );
}
