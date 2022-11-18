import { Col, Form, message, Modal, Row } from 'antd';
import moment from 'moment';
import { breadcrumbsCv, requiredFields } from '../../../constants/newAddCv';

import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import cvApi from '../../../api/cvApi';
import uploadApi from '../../../api/uploadApi';
import {
  FormDatePicker,
  FormInput,
  GeneralSelect,
  LevelSelect,
  PositionSelect,
  SourceSelect,
  SubmitBtn,
  UserSelect,
} from '../../../components/Form';
import { useFetchUser } from '../../../components/Hooks/FetchApi';
import AvatarShow from './AvatarShow';
import usePositionAndLevel from '../hooks/usePositionLevelAndRequest';
import useCheckExist from '../hooks/useCheckExist';
import {
  rulesValidateEmail,
  validatePhoneNumber,
} from '../../../utils/validation';
import NoPermission from '../../../components/NoPermission';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import { CancelWhiteButton } from '../../../components/Buttons/Buttons';
import { useNavigate } from 'react-router-dom';
import { SaveOutlined } from '@ant-design/icons';
import UploadCv from './UploadCv';
import { DATE_FORMAT, DEFAULT_DATE_PICK_RANGE } from '../../../constants';

export default function NewAdd(props) {
  const [form] = Form.useForm();
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { userInfor } = useSelector(state => state.auth);
  const { cv: permission } = userInfor.permission;

  useEffect(() => {
    form.setFieldsValue({
      assignee_id: userInfor.username,
    });
  }, []);

  const { onChangeRequest, onChangePositionAndLevel, requests } =
    usePositionAndLevel({ form });

  const { onBlurMobile, onBlurEmail, onBlurBasicInfo, finishWithCheckExists } =
    useCheckExist({
      form,
      sendDataToServer,
    });

  const { items: usersList } = useFetchUser();

  const [avatarList, setAvatarList] = useState([]);
  const [cvFile, setCvFile] = useState(null);
  const [submitLoading, setSubmitLoading] = useState(false);

  const disableTime = current => {
    return current && current > moment().subtract(18, 'year').endOf('day');
  };

  const backToList = () => {
    navigate('/cv');
  };

  async function sendDataToServer(values) {
    const listFile = {};
    avatarList.map((e, i) => {
      listFile[`file${i}`] = e.file;
      return e;
    });
    setSubmitLoading(true);
    try {
      let images = [];
      let linkcv = '';
      if (avatarList && avatarList.length) {
        const urlImg = await uploadApi.postMutiple(listFile);
        images = urlImg.data.data;
      }
      if (cvFile) {
        const urlCv = await uploadApi.post(cvFile);
        linkcv = urlCv.data.data;
      }
      const item = {
        ...values,
        images: typeof images == 'object' ? images : [images],
        birthday: values?.birthday
          ? moment(values?.birthday).format('YYYY-MM-DD')
          : undefined,
        linkcv: linkcv ? linkcv : null,
      };

      for (let key in item) {
        if (typeof item[key] === 'string') item[key] = item[key].trim();
      }
      await cvApi.create(item);
      backToList();
      message.success(t('cv.createSuccess'));
      setSubmitLoading(false);
    } catch (e) {
      console.log(e);
      setSubmitLoading(false);
      message.error(e.message);
    }
  }

  return permission.add ? (
    <Form
      form={form}
      layout="vertical"
      onFinish={finishWithCheckExists}
      className="standard-form"
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbsCv}
        extra={[
          <SubmitBtn
            form={form}
            loading={submitLoading}
            requiredFields={requiredFields}
            icon={<SaveOutlined />}
          >
            {t('common.save')}
          </SubmitBtn>,

          <CancelWhiteButton onClick={backToList}>
            {t('common.cancel')}
          </CancelWhiteButton>,
        ]}
        component={
          <div className="addCv">
            <Row>
              {/* Avatar show */}
              <Col span={7} className="show">
                <AvatarShow
                  avatarList={avatarList}
                  setAvatarList={setAvatarList}
                />
              </Col>
              {/* Left Form */}
              <Col span={7} className="left">
                <FormInput
                  name="fullname"
                  label={t('user.fullname')}
                  min={3}
                  required
                  onBlur={onBlurBasicInfo}
                  placeholder={t('user.nameplace')}
                />
                <PositionSelect
                  name="position_id"
                  label={t('user.position')}
                  required
                  onChange={e => {
                    onChangePositionAndLevel(e);
                    onBlurBasicInfo();
                  }}
                />
                <LevelSelect
                  name="level_id"
                  label={t('user.level')}
                  required
                  onBlur={onBlurBasicInfo}
                  onChange={e => {
                    onChangePositionAndLevel(e);
                    onBlurBasicInfo();
                  }}
                />
                <GeneralSelect
                  name="request_id"
                  label={t('updateCv.request')}
                  required
                  valueKey="id"
                  contentKey="content"
                  onChange={onChangeRequest}
                  fetchedItems={requests}
                />
                <UserSelect
                  name="reviewer_id"
                  label={t('updateCv.reviewer')}
                  fetchedItems={usersList}
                  withFullName
                />
                <UserSelect
                  name="interviewer_id"
                  label={t('updateCv.interviewer')}
                  fetchedItems={usersList}
                  withFullName
                />
                <UserSelect
                  name="assignee_id"
                  label={t('common.assignee')}
                  withFullName
                  required
                  fetchedItems={usersList}
                />
                <div className="upload-file">
                  <UploadCv cvFile={cvFile} setCvFile={setCvFile} />
                </div>
              </Col>

              {/* Right form */}
              <Col span={7} className="right">
                <FormInput
                  name="email"
                  label={t('user.email')}
                  onBlur={onBlurEmail}
                  placeholder={t('user.emailplace')}
                  rules={rulesValidateEmail()}
                />
                <FormInput
                  name="mobile"
                  label={t('user.mobile')}
                  onBlur={onBlurMobile}
                  placeholder={t('user.mobileplace')}
                  type="phone"
                  rules={validatePhoneNumber()}
                />
                <FormDatePicker
                  name="birthday"
                  label={t('user.birthday')}
                  format="DD/MM/YYYY"
                  showToday={false}
                  defaultPickerValue={moment(
                    DEFAULT_DATE_PICK_RANGE,
                    DATE_FORMAT,
                  )}
                  disabledDate={disableTime}
                />
                <SourceSelect
                  name="source_id"
                  label={t('user.source')}
                  required
                />
                <FormInput
                  name="checklist"
                  label={t('updateCv.checklist')}
                  placeholder={t('updateCv.checklistPlaceholder')}
                />
                <FormInput
                  textArea
                  name="description"
                  label={t('user.description')}
                />
              </Col>
            </Row>
          </div>
        }
      />
    </Form>
  ) : (
    <NoPermission />
  );
}
