import { SaveOutlined } from '@ant-design/icons';
import { Checkbox, Col, DatePicker, Form, message, Row } from 'antd';
import moment from 'moment';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useNavigate, useParams } from 'react-router-dom';
import NoPermission from '../../../../src/components/NoPermission';
import cvApi from '../../../api/cvApi';
import uploadApi from '../../../api/uploadApi';
import { CancelWhiteButton } from '../../../components/Buttons/Buttons';
import {
  FormInput,
  FormItem,
  FormRadio,
  GeneralSelect,
  LevelSelect,
  PositionSelect,
  SourceSelect,
  SubmitBtn,
  UserSelect,
} from '../../../components/Form';
import { useFetchUser } from '../../../components/Hooks/FetchApi';
import { DATE_FORMAT, LINK_MAX_LENGTH } from '../../../constants';
import { breadcrumbsCv } from '../../../constants/newAddCv';
import { dateDefaultToPick, overYear } from '../../../constants/updateCv';
import {
  rulesValidateEmail,
  validatePhoneNumber,
} from '../../../utils/validation.js';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import {
  BIRTHDAY_FORMAT_FROM_BACKEND,
  genderRadio,
  requiredFields,
} from '../constant/updateCv';
import useCheckExist from '../hooks/useCheckExist';
import usePositionAndLevel from '../hooks/usePositionLevelAndRequest';
import '../scss/updateCv.scss';
import AvatarShow from './AvatarShow';
import UploadCv from './UploadCv';

function UpdateCv() {
  const { t } = useTranslation();
  const { id } = useParams();
  const navigate = useNavigate();
  const [form] = Form.useForm();

  const { items: usersList } = useFetchUser();
  const [submitLoading, setSubmitLoading] = useState(false);

  const [isHasData, setIsHasData] = useState(false);
  const [cv, setCv] = useState({});

  const { onChangeRequest, onChangePositionAndLevel, requests, auto } =
    usePositionAndLevel({ form, cv });

  const { onBlurMobile, onBlurEmail, onBlurBasicInfo, finishWithCheckExists } =
    useCheckExist({
      form,
      sendDataToServer,
      cv,
    });

  const [avatarList, setAvatarList] = useState([]);
  const [file, setFile] = useState(null);
  const [isEditingCv, setIsEditingCv] = useState(false);

  useEffect(() => {
    fetchCV();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const fetchCV = async () => {
    try {
      const res = await cvApi.getById(id);
      const item = res.data.data;
      const images = JSON.parse(item.images);

      setCv(item);
      if (images?.length > 0) {
        setAvatarList(
          images.map(image => {
            return { show: image };
          }),
        );
      }

      form.setFieldsValue({
        ...item,
        birthday: item?.birthday
          ? moment(item?.birthday, BIRTHDAY_FORMAT_FROM_BACKEND)
          : undefined,
      });
      setIsHasData(true);
    } catch (e) {
      console.log(e);
    }
  };

  useEffect(() => {
    // Run to find request with condition that is has data and auto is fetched
    if (auto && isHasData) {
      onChangePositionAndLevel(undefined, cv?.request_id);
    }
  }, [auto, isHasData]);

  const backToDetail = () => {
    navigate('/cv/' + id);
  };

  async function sendDataToServer(values) {
    try {
      setSubmitLoading(true);
      const imageLinks = [];
      let cvLink = '';
      const promises = [];

      avatarList.forEach((image, i) => {
        if (image.file) {
          promises.push(uploadApi.post(image.file));
          imageLinks.push(undefined);
        } else imageLinks.push(image.show);
      });

      await Promise.all(promises).then(results => {
        results.forEach(res => {
          imageLinks[imageLinks.indexOf(undefined)] =
            res.status === 200 ? res.data.data : '';
        });
      });

      if (isEditingCv && file) {
        const res = await uploadApi.post(file);
        cvLink = res.data.data;
      }

      const item = {
        ...values,
        images: JSON.stringify(imageLinks),
        birthday: values?.birthday
          ? moment(values?.birthday).format(BIRTHDAY_FORMAT_FROM_BACKEND)
          : undefined,
        linkcv: cvLink ? cvLink : cv?.linkcv,
      };

      for (let key in item) {
        if (typeof item[key] === 'string' && item[key]) {
          item[key] = item[key].trim();
        }
        if (typeof item[key] === 'string') {
          if (item[key] === '') delete item[key];
        }
      }

      await cvApi.edit(id, item);
      backToDetail();
      message.success(t('updateCv.editSuccess'));
      setSubmitLoading(false);
    } catch (e) {
      console.log(e);
      setSubmitLoading(false);
      message.error(e.message);
    }
  }

  const disabledDate = date => {
    return date > moment().subtract(overYear, 'years');
  };

  const checkStringNotAllow = event => {
    if (!/[0-9]/.test(event.key)) {
      event.preventDefault();
    }
  };

  return (
    <Form
      form={form}
      onFinish={finishWithCheckExists}
      className="standard-form"
    >
      <LayoutBreadcrumb
        extra={[
          <SubmitBtn
            form={form}
            requiredFields={requiredFields}
            icon={<SaveOutlined />}
            loading={submitLoading}
          >
            {t('common.save')}
          </SubmitBtn>,

          <CancelWhiteButton onClick={backToDetail}>
            {t('common.cancel')}
          </CancelWhiteButton>,
        ]}
        breadcrumbNameMap={{
          ...breadcrumbsCv,
          [`/cv/${id}`]: t('updateCv.detailBreadcrumb'),
          [`/cv/${id}/update`]: `${t('updateCv.editBreadcrumb')} ${
            cv.fullname || ''
          } (${id})`,
        }}
        component={
          <div className="update-form-box">
            <Row>
              <Col span={12}>
                <Row justify="center" align="center" className="avatar-row">
                  <Col xxl={12} xl={14} lg={19}>
                    <AvatarShow
                      avatarList={avatarList}
                      setAvatarList={setAvatarList}
                    />
                  </Col>
                </Row>
              </Col>
              <Col span={12}>
                {/* Form */}
                <div className="update-form">
                  <FormItem label="CV" className="cv-field">
                    <Checkbox
                      checked={isEditingCv}
                      onChange={e => setIsEditingCv(e.target.checked)}
                    >
                      {t('updateCv.checkbox')}
                    </Checkbox>
                    {isEditingCv && (
                      <UploadCv cvFile={file} setCvFile={setFile} />
                    )}
                  </FormItem>
                  <FormRadio
                    name="gender"
                    label={t('updateCv.gender')}
                    items={genderRadio}
                  />
                  <FormInput
                    name="fullname"
                    label={t('updateCv.fullname')}
                    placeholder={t('updateCv.fullnamePlaceholder')}
                    min={3}
                    required
                    onBlur={onBlurBasicInfo}
                  />
                  <FormItem name="birthday" label={t('updateCv.birthday')}>
                    <DatePicker
                      style={{ width: '100%' }}
                      placeholder={t('updateCv.birthdayPlaceholder')}
                      format={DATE_FORMAT}
                      showToday={false}
                      disabledDate={disabledDate}
                      defaultPickerValue={moment(
                        dateDefaultToPick,
                        DATE_FORMAT,
                      )}
                    />
                  </FormItem>
                  <FormInput
                    name="mobile"
                    label={t('updateCv.mobile')}
                    rules={validatePhoneNumber()}
                    placeholder={t('updateCv.mobilePlaceholder')}
                    onBlur={onBlurMobile}
                    onKeyPress={checkStringNotAllow}
                  />
                  <FormInput
                    name="email"
                    label={t('updateCv.email')}
                    rules={rulesValidateEmail()}
                    onBlur={onBlurEmail}
                    placeholder={t('updateCv.emailPlaceholder')}
                  />
                  <FormInput
                    name="address"
                    label={t('updateCv.address')}
                    placeholder={t('updateCv.addressPlaceholder')}
                  />
                  <PositionSelect
                    name="position_id"
                    label={t('updateCv.position')}
                    onChange={e => {
                      onChangePositionAndLevel(e);
                      onBlurBasicInfo();
                    }}
                    required
                  />
                  <LevelSelect
                    name="level_id"
                    label={t('updateCv.level')}
                    onChange={e => {
                      onChangePositionAndLevel(e);
                      onBlurBasicInfo();
                    }}
                    required
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
                  <SourceSelect
                    name="source_id"
                    label={t('user.source')}
                    required
                  />
                  <FormInput
                    textArea
                    name="description"
                    label={t('user.description')}
                    showCount={false}
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
                    fetchedItems={usersList}
                  />

                  <FormInput
                    name="checklist"
                    label={t('updateCv.checklist')}
                    placeholder={t('updateCv.checklistPlaceholder')}
                    max={LINK_MAX_LENGTH}
                  />
                </div>
              </Col>
            </Row>
          </div>
        }
      />
    </Form>
  );
}

export default function UpadteCvIndex() {
  const permission = useSelector(state => state.auth.userInfor.permission.cv);
  return permission.edit ? <UpdateCv /> : <NoPermission />;
}
