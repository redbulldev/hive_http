import { CloseOutlined, SendOutlined } from '@ant-design/icons';
import { Button, Col, Form, message, Row } from 'antd';
import { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useNavigate, useParams } from 'react-router-dom';
import cvApi from '../../../api/cvApi';
import { sendEmail } from '../../../api/historyCv';
import {
  FormEditor,
  FormInput,
  FormItem,
  SubmitBtn,
} from '../../../components/Form';
import InputTags from '../../../components/InputTags/InputTags';
import { autoFillCustomField } from '../../../utils/autoFillCustomField';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import { REG_VALIDATE_EMAIL } from '../constant';
import useFetchEmail from '../hooks/useFetchEmail';
function ModalSendEmail() {
  const [errorCc, setErrorCc] = useState(false);
  const [tags, setTags] = useState([]);
  const refQ = useRef();
  const [form] = Form.useForm();
  const { id } = useParams();
  const [cv, setCv] = useState({});
  const fetchCvDetail = async () => {
    const res = await cvApi.getById(id);
    setCv(res.data.data);
  };
  useEffect(() => {
    fetchCvDetail();
  }, []);

  const { emailHistoryResponse, emailTemplate, totalHistoryEmail } =
    useFetchEmail(id, cv.step, cv.status);
  const navigate = useNavigate();
  const quill = refQ.current?.getEditor();
  const { t } = useTranslation();
  const onFinish = async val => {
    const newValue = {
      ...val,
      email_id: emailTemplate?.id,
      email: cv.email ? cv.email : val.email,
      cc: tags.length ? tags : [],
      cv_id: id,
      cv_step: cv.step,
      cv_status: cv.status,
    };
    try {
      await sendEmail(newValue);
      message.success(t('updateCv.sendmail_success'));
      navigate(-1);
    } catch (error) {
      message.error(error.data.message);
    }
  };
  const handleClickSubmit = e => {
    const checkEmail = tags.every(t => REG_VALIDATE_EMAIL.test(t));

    if (tags.length && !checkEmail) {
      e.preventDefault();
      setErrorCc(true);
    } else {
      setErrorCc(false);
    }
  };
  useEffect(() => {
    const { email, fullname } = cv;

    form.setFieldsValue({
      email: `${fullname ? `${fullname}${email ? ` ( ${email} )` : ''}` : ''}`,
    });
  }, [cv]);

  useEffect(() => {
    /*    if (emailHistoryResponse) {
      const { content, title, cc } = emailHistoryResponse;
      if (cc && JSON.parse(cc)?.length) {
        setTags(JSON.parse(cc));
      }
      form.setFieldsValue({
        title: title ? title : '',
        content: content ? content : '',
      });
    } else */ if (emailTemplate) {
      const { content, title, cc } = emailTemplate;
      const templateContent = content ? autoFillCustomField(cv, content) : '';
      if (cc && JSON.parse(cc)?.length) {
        setTags(JSON.parse(cc));
      }
      form.setFieldsValue({
        title: title ? title : '',
        content: templateContent,
      });
    }
  }, [emailTemplate, /*  emailHistoryResponse, */ cv]);

  const breadcrumbNameMap = {
    '/cv': t('sidebar.cv-managerment'),
    [`/cv/${id}`]: `${cv.fullname || ''} (${id})`,
    [`/cv/${id}/sendEmail`]: 'Gửi email',
  };
  return (
    <Form
      form={form}
      layout="vertical"
      onFinish={onFinish}
      onKeyDown={e => (e.keyCode == 13 ? e.preventDefault() : '')}
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbNameMap}
        extra={[
          <SubmitBtn
            icon={<SendOutlined />}
            style={{ marginBottom: '0' }}
            form={form}
            requiredFields={['email', 'title', 'content']}
            onClick={handleClickSubmit}
            disabled={quill?.getLength() === 1}
          >
            {t('updateCv.send')}
          </SubmitBtn>,
          <Button
            onClick={() => {
              navigate(-1);
            }}
          >
            <CloseOutlined />
            {t('updateCv.cancelBtn')}
          </Button>,
        ]}
        component={
          <>
            <Row gutter={{ sm: 25, xl: 125 }}>
              <Col sm={24} xl={12}>
                <FormItem name="cc" label="CC">
                  <InputTags tags={tags} setTags={setTags} />
                  {errorCc && (
                    <p className="danger-text">{t('updateCv.invalid_email')}</p>
                  )}
                </FormItem>
                <FormInput name="title" label={t('updateCv.title')} required />
                <FormInput
                  disabled={cv.email}
                  name="email"
                  label={t('updateCv.received')}
                  required
                />
              </Col>
              <Col sm={24} xl={12}>
                <FormEditor
                  name="content"
                  label={t('updateCv.content')}
                  required
                  form={form}
                />
              </Col>
            </Row>
          </>
        }
      />
    </Form>
  );
}

export default ModalSendEmail;
