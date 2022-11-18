import { Form, message } from 'antd';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useLocation, useNavigate } from 'react-router-dom';
import requestApi from '../../../api/requestApi';
import { useFetchLevel } from '../../../components/Hooks/FetchApi';
import { DEADLINE_DEFAULT } from '../../../constants/requestPage';
import { removeWhiteSpaces } from '../../../utils/removeWhitespaces';
import { setReloadTable } from '../requestSlice';
import LayoutBreadcrumb from './LayoutBreadcrumb';
import RequestForm from './RequestForm';
import BtnGroupSubmit from './Table/components/BtnGroupSubmit';
import useFetchDetailRequest from './useFetchDetailRequest';
export default function EditRequest() {
  const { t } = useTranslation();
  const breadcrumbNameMap = {
    '/request': t('sidebar.request'),
    '/request/edit': t('request.titleEditForm'),
  };
  const [form] = Form.useForm();
  const navigate = useNavigate();
  const location = useLocation();
  const { items: listLevel } = useFetchLevel();
  const dispatch = useDispatch();
  const pathname = location.pathname.split('/').filter(x => x);
  const { detailRequest: requestFormInfo } = useFetchDetailRequest(pathname[2]);
  const handleEditRequest = async values => {
    message.success('Successfully');
    let newValues = {};
    Object.keys(values).forEach(key => {
      if (values[key] === null || values[key] === undefined) {
        delete values[key];
      } else {
        newValues[key] = values[key];
      }
    });
    const newLevel = listLevel
      .filter(level => values.levels.includes(level.id))
      .map(level => {
        return {
          id: level.id,
          title: level.title,
        };
      });
    const payload = {
      ...newValues,
      deadline: values.deadline.format(DEADLINE_DEFAULT),
      levels: newLevel,
      status: requestFormInfo.status === 1 ? 0 : requestFormInfo.status,
    };

    delete payload.quantity;

    // add target fields
    payload.target = parseInt(values.quantity);

    try {
      await requestApi.edit(requestFormInfo.id, removeWhiteSpaces(payload));
      dispatch(setReloadTable());
      message.success(t('request.editRequestSuccess'));
      navigate('/request');
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.editRequestFail'));
      }
    }
  };
  return (
    <Form
      form={form}
      layout="vertical"
      name="form_in_modal"
      className="request__form"
      onFinish={handleEditRequest}
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbNameMap}
        extra={[<BtnGroupSubmit form={form} />]}
        component={
          <RequestForm titleForm={t('request.titleEditForm')} form={form} />
        }
      />
    </Form>
  );
}
