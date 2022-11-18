import { Form, message } from 'antd';
import dayjs from 'dayjs';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import requestApi from '../../../api/requestApi';
import { useFetchLevel } from '../../../components/Hooks/FetchApi';
import { DEADLINE_DEFAULT } from '../../../constants/requestPage';
import { removeWhiteSpaces } from '../../../utils/removeWhitespaces';
import { setReloadTable } from '../requestSlice';
import LayoutBreadcrumb from './LayoutBreadcrumb';
import RequestForm from './RequestForm';
import BtnGroupSubmit from './Table/components/BtnGroupSubmit';
export default function AddRequest() {
  const { t } = useTranslation();
  const breadcrumbNameMap = {
    '/request': t('sidebar.request'),
    '/request/add': t('request.createRequest'),
  };

  const [form] = Form.useForm();
  const navigate = useNavigate();
  const { items: listLevel } = useFetchLevel();

  const dispatch = useDispatch();
  const handleCreatRequest = async values => {
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
    };

    delete payload.deadline;
    delete payload.quantity;
    // add target and year month fields
    payload.target = parseInt(values.quantity);
    payload.month = dayjs(values.deadline).month() + 1;
    payload.year = dayjs(values.deadline).year();
    payload.day = dayjs(values.deadline).date();
    payload.status = 0;
    try {
      await requestApi.create(removeWhiteSpaces(payload));
      message.success(t('request.createRequestSuccess'));
      dispatch(setReloadTable());
      form.resetFields();
      navigate('/request');
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToCreateRequest'));
      }
    }
  };

  return (
    <Form
      form={form}
      layout="vertical"
      name="form_in_modal"
      className="request__form"
      onFinish={handleCreatRequest}
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbNameMap}
        extra={[<BtnGroupSubmit form={form} />]}
        component={
          <RequestForm titleForm={t('request.createRequest')} form={form} />
        }
      />
    </Form>
  );
}
