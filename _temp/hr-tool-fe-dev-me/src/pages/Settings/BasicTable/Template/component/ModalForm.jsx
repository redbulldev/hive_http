import { Form, message } from 'antd';
import { useSelector } from 'react-redux';
import GeneralDrawer from '../../../../../components/Drawer/GeneralDrawer';
import { DEFAULT_STATUS } from '../../../../../constants';
import { fieldsCreator } from '../../../../../constants/basicTable';
import { renderFields } from '../../../../../utils/formHandle';

export default function ModalForm(props) {
  const [form] = Form.useForm();

  const { initial, mode } = useSelector(state => state.drawer);

  const fillData = () => {
    form.resetFields();
    const init = mode === 'add' ? {} : initial;
    form.setFieldsValue({
      ...init,
      status: initial.status ?? DEFAULT_STATUS,
    });
  };

  const catchCallback = e => {
    if (e?.data?.message?.includes('exists')) {
      message.error(props.i18n.itemExists);
    }
  };

  const Content = () => {
    return <>{renderFields(fieldsCreator(props.i18n.titlePlaceholder))}</>;
  };

  return (
    <GeneralDrawer
      {...props}
      fillData={fillData}
      form={form}
      modal
      catchCallback={catchCallback}
      FormContent={Content}
      fullscreenClassName="table-fullscreen"
      requiredFields={['title']}
    />
  );
}
