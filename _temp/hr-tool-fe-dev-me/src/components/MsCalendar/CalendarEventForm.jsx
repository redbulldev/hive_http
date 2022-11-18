import { Form } from 'antd';
import React, { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import GeneralDrawer from '../Drawer/GeneralDrawer';
import { FormDatePicker, FormInput, GeneralSelect } from '../Form';
import { eventTag, prioritySelect, requiredFields } from './constants';
import moment from 'moment';
import { useCallMsApi } from '.';
import { date } from 'yup';

export default function CalendarEventForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { initial, mode } = useSelector(state => state.drawer);
  const { api: editApi } = useCallMsApi({ type: 'edit' });

  const fillData = () => {
    form.resetFields();
    if (mode === 'edit') {
      const { startDate } = initial;
      const { description, priority } = initial.hrtoolData;
      form.setFieldsValue({
        startDate: startDate ? moment(startDate, 'DD/MM/YYYY') : undefined,
        description,
        priority,
      });
    }
  };

  const onFinish = async values => {
    if (mode === 'add') {
    }

    if (mode === 'edit') {
      const { id } = initial;
      const obj = { ...initial.hrtoolData, ...values };
      const data = {
        id,
        body: {
          contentType: 'text',
          content: `${eventTag.open}${JSON.stringify(obj)}${eventTag.close}`,
        },
      };

      const { startDate } = values;
      if (startDate) {
        const obj = {
          timeZone: 'UTC',
          dateTime: startDate.toISOString(),
        };
        data.start = obj;
        data.end = obj;
      }
      await editApi(data);
    }
  };

  const Content = useCallback(() => {
    return (
      <>
        <FormDatePicker name="startDate" label={'Ngày'} required />
        <GeneralSelect
          name="priority"
          required
          label={'Ưu tiên'}
          fetchedItems={prioritySelect}
          valueKey="key"
          contentKey="label"
        />
        <FormInput textArea name="description" label={'Ghi chú'} />
      </>
    );
  }, []);

  return (
    <GeneralDrawer
      {...props}
      fillData={fillData}
      form={form}
      FormContent={Content}
      onFinish={onFinish}
      fullscreenClassName="table-fullscreen"
      requiredFields={requiredFields}
      height={500}
    />
  );
}
