import { Form } from 'antd';
import React, { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import GeneralDrawer from '../../../../components/Drawer/GeneralDrawer';
import { FormDatePicker, FormInput } from '../../../../components/Form';
import { DATE_BACKEND, DATE_FORMAT } from '../../../../constants';
import { requiredFields } from '../constant';
import moment from 'moment';

export default function ReleaseForm(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const { initial, mode } = useSelector(state => state.drawer);

  const fillData = () => {
    form.resetFields();
    const init = mode === 'add' ? {} : initial;
    const release = init.daterelease;

    form.setFieldsValue({
      ...init,
      daterelease: release ? moment(release) : undefined,
    });
  };

  const transformValues = values => {
    const release = values.daterelease;
    if (release) values.daterelease = release.format(DATE_BACKEND);
    else delete values.daterelease;
    return values;
  };

  const Content = useCallback(() => {
    return (
      <>
        <FormInput
          name="version"
          label={'Tên phiên bản'}
          placeholder={'Vd: 1.6'}
          required
        />
        <FormDatePicker
          name="daterelease"
          label={'Ngày phát hành'}
          format={DATE_FORMAT}
        />
        <FormInput name="conent" label={'Mô tả'} required textArea rows={12} />
      </>
    );
  }, []);

  return (
    <GeneralDrawer
      {...props}
      form={form}
      fillData={fillData}
      FormContent={Content}
      requiredFields={requiredFields}
      transformValues={transformValues}
      modal
      fullscreenClassName="table-fullscreen"
    />
  );
}
