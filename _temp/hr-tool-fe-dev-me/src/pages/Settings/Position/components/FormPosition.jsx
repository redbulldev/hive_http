import { PlusCircleFilled } from '@ant-design/icons';
import { Button, Drawer, Form, message, Radio } from 'antd';
import React, { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { positionApi } from '../../../../api/positionApi';
import { setIsOpenedDrawer } from '../../../../components/Drawer/slice/drawer';
import { FormInput, UserSelect } from '../../../../components/Form';
import DepartmentSelect from '../../../../components/Form/Selects/DepartmentSelect';
import { DEFAULT_STATUS } from '../../../../constants';
import { getDetailPosition } from '../reducer';
function FormPosition(props) {
  const { formBtnTitle, formTilte, isEdit, setIsEdit, fetchData } = props;
  const { department, manager } = useSelector(item => item.position);
  const { isOpened, initial: detailData } = useSelector(state => state.drawer);
  const { t } = useTranslation();
  const [form] = Form.useForm();
  const [isDisabledSubmit, setIsDisabledSubmit] = useState(false);
  const dispatch = useDispatch();
  const onClose = () => {
    dispatch(setIsOpenedDrawer(false));
    dispatch(getDetailPosition({}));
    setIsEdit(false);
  };

  useEffect(() => {
    form.resetFields();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isOpened]);

  const handleFormCreate = async value => {
    const data = {
      ...value,
      title: value.title.trim(),
      description: value.description.trim(),
    };
    try {
      await positionApi.postPosition(data);
      dispatch(setIsOpenedDrawer(false));
      fetchData();
      message.success(t('position.successCreate'));
    } catch (error) {
      const { status, data } = error;
      if (status === 201) {
        form.setFieldsValue({});
        message.error(t('position.titlePositionExits'));
      } else if (!status) {
        message.error(t('position.failNetWork'));
      } else {
        message.error(t('position.cantCreate'));
        throw new Error(data.message);
      }
    }
  };

  const handelFormEdit = async value => {
    if (typeof value.parent_id === 'object') {
      value.parent_id = Number(value.parent_id);
    }
    try {
      const dataEdit = {
        ...detailData,
        ...value,
      };
      await positionApi.putPosition(dataEdit);
      dispatch(setIsOpenedDrawer(false));
      fetchData();
      message.success(t('position.successEdit'));
    } catch (error) {
      if (!error.status) {
        message.error(t('position.failNetWork'));
      } else {
        if (error.data.message) {
          const msg = error.data.message;
          if (msg.includes('exists')) {
            message.error(t('position.positionExists'));
          } else {
            message.error(msg);
          }
        }
      }
    }
  };
  useEffect(() => {
    if (!isEdit) {
      form.setFieldsValue({
        parent_id: department[0]?.parent_id,
        description: '',
        status: DEFAULT_STATUS,
        title: '',
        manager_id: manager[0],
      });
    } else {
      let isDepartment = department?.filter(
        item => detailData?.parent_id === item.parent_id,
      );
      form.setFieldsValue({
        parent_id: detailData.parent_id
          ? isDepartment.map(item => item.parent_id)
          : [],
        description: detailData?.description,
        status: detailData?.status,
        title: detailData?.title,
        manager_id: detailData?.manager_id,
        requestor: detailData?.requestor
          ? JSON.parse(detailData?.requestor)
          : [],
      });
    }
    checkDisableSubmit();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isOpened, isEdit]);

  const checkDisableSubmit = () => {
    const requiredFields = ['title', 'requestor', 'manager_id'];
    const errors = form.getFieldsError([...requiredFields]);
    const requireCondition = requiredFields.some(field => {
      const value = form.getFieldValue(field);
      if (value?.length === 0) return true;
      return [undefined, null].includes(value);
    });
    const condition =
      requireCondition || errors.some(field => field.errors.length > 0);
    setIsDisabledSubmit(condition);
  };

  const onChangeForm = () => {
    checkDisableSubmit();
  };

  const submitBtn = useRef();
  return (
    <div className="position__form">
      <Drawer
        title={formTilte}
        placement="right"
        onClose={onClose}
        visible={isOpened}
        footerStyle={{ border: 'none', padding: '21px 17px' }}
        bodyStyle={{ padding: '16px 29px 0px 22px' }}
        getContainer={() => document.querySelector('.table-fullscreen')}
        footer={
          <Button
            icon={<PlusCircleFilled />}
            color="white"
            type="primary"
            htmlType="submit"
            disabled={isDisabledSubmit}
            onClick={() => submitBtn.current.click()}
          >
            {formBtnTitle}
          </Button>
        }
      >
        <Form
          form={form}
          layout="vertical"
          onFinish={!isEdit ? handleFormCreate : handelFormEdit}
          onFieldsChange={onChangeForm}
        >
          <DepartmentSelect label={t('position.department')} name="parent_id" />
          <FormInput
            name="title"
            label={t('position.position')}
            required
            max={200}
            placeholder="Vd: Sales"
          />
          <UserSelect
            label={t('position.manager')}
            name="manager_id"
            required
          />
          <UserSelect
            label={t('position.requestor')}
            name="requestor"
            required
            mode="multiple"
          />
          <FormInput
            label={t('position.description')}
            name="description"
            textArea
            max={5000}
          />

          <Form.Item
            style={{ marginBottom: '12px' }}
            name="status"
            label={t('position.status')}
            initialValue={0}
          >
            <Radio.Group>
              <Radio value={0}>{t('position.lock')}</Radio>
              <Radio value={1}>{t('position.unlock')}</Radio>
            </Radio.Group>
          </Form.Item>
          <Form.Item noStyle shouldUpdate>
            {() => {
              return (
                <Form.Item>
                  <Button
                    icon={<PlusCircleFilled />}
                    color="white"
                    type="primary"
                    htmlType="submit"
                    ref={submitBtn}
                    hidden={true}
                  >
                    {formBtnTitle}
                  </Button>
                </Form.Item>
              );
            }}
          </Form.Item>
        </Form>
      </Drawer>
    </div>
  );
}

export default React.memo(FormPosition);
