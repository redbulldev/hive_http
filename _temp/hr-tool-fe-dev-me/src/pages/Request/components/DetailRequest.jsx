import { CheckOutlined, CloseOutlined, SaveOutlined } from '@ant-design/icons';
import { Button, Form, message } from 'antd';
import qs from 'query-string';
import { useTranslation } from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import { useLocation, useNavigate } from 'react-router-dom';
import requestApi from '../../../api/requestApi';
import {
  DEADLINE_DEFAULT,
  LIST_REQUEST_STATUS,
} from '../../../constants/requestPage';
import { hasPermission } from '../../../utils/hasPermission';
import { removeWhiteSpaces } from '../../../utils/removeWhitespaces';
import { setReloadTable } from '../requestSlice';
import LayoutBreadcrumb from './LayoutBreadcrumb';
import RequestForm from './RequestForm';
import useFetchDetailRequest from './useFetchDetailRequest';
export default function DetailRequest() {
  const { t } = useTranslation();
  const breadcrumbNameMap = {
    '/request': t('sidebar.request'),
    '/request/detail': t('request.titleDetailForm'),
  };
  const navigate = useNavigate();
  const listLevel = useSelector(state => state.request.listLevel);
  const location = useLocation();
  const dispatch = useDispatch();
  const { status } = qs.parse(location.search);

  const pathname = location.pathname.split('/').filter(x => x);
  const { detailRequest: requestFormInfo } = useFetchDetailRequest(pathname[2]);
  const [form] = Form.useForm();
  const { userInfor } = useSelector(state => state.auth);
  const handleChangeStatusRequest = async payload => {
    try {
      payload.target = requestFormInfo.target;
      await requestApi.edit(requestFormInfo.id, payload);
      dispatch(setReloadTable());
      message.success(t('request.titleChangeStatusSuccess'));
      navigate('/request');
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.titleChangeStatusFail'));
      }
      navigate('/request');
    }
  };
  const handleSaveRequest = async () => {
    try {
      await requestApi.edit(requestFormInfo.id, {
        jd: form.getFieldValue('jd'),
      });
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
  const handleEditRequest = async values => {
    message.success('successfully');
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
        extra={[
          status === '0' ? (
            <div style={{ display: 'flex', justifyContent: 'center' }}>
              <Button
                type="primary"
                disabled={!hasPermission(userInfor, 'request', 'decision')}
                onClick={() =>
                  handleChangeStatusRequest({
                    status: LIST_REQUEST_STATUS[2].value,
                  })
                }
              >
                <CheckOutlined />
                {t('request.titleApproveRequest')}
              </Button>
              <Button
                type="danger"
                style={{ marginLeft: 20 }}
                disabled={!hasPermission(userInfor, 'request', 'decision')}
                onClick={() =>
                  handleChangeStatusRequest({
                    status: LIST_REQUEST_STATUS[1].value,
                  })
                }
              >
                <CloseOutlined />
                {t('request.titleRejectRequest')}
              </Button>
            </div>
          ) : (
            <div className="btn-wrapper">
              <Button
                type="primary"
                onClick={handleSaveRequest}
                icon={<SaveOutlined />}
              >
                {t('request.btnSave')}
              </Button>
              <Button
                style={{ marginLeft: 20 }}
                onClick={() => navigate('/request')}
              >
                <CloseOutlined />
                {t('request.btnCancel')}
              </Button>
            </div>
          ),
        ]}
        component={
          <RequestForm titleForm={t('request.titleDetailForm')} form={form} />
        }
      />
    </Form>
  );
}
