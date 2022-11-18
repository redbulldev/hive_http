import { ExclamationCircleOutlined } from '@ant-design/icons';
import { Modal } from 'antd';
import React, { useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import cvApi from '../../../api/cvApi';

export default function useCheckExist({ form, sendDataToServer, cv }) {
  const { t } = useTranslation();

  const prevMobile = useRef(null);
  const prevEmail = useRef(null);

  const [isExistsMobile, setIsExistsMobile] = useState(null);
  const [isExistsEmail, setIsExistsEmail] = useState(null);
  const [isExistsBasicInfo, setIsExistsBasicInfo] = useState(null);

  const checkExits = async (field, message) => {
    let obj = {};
    let value = null;
    if (Array.isArray(field)) {
      field.forEach(item => {
        obj[item] = form.getFieldValue(item);
      });
      const res = await cvApi.getAll(obj);
      if (res.data.total > 0) return message || true;
    } else {
      value = form.getFieldValue(field);
      if (value) {
        const res = await cvApi.getAll({ [field]: value });
        if (res.data.total > 0) return value;
      }
    }
    return false;
  };

  const isValidField = field => {
    return form.getFieldValue(field) && form.getFieldError(field).length === 0;
  };

  const handleBlur = async (field, preField, setState) => {
    const value = form.getFieldValue(field);
    const error = form.getFieldError(field);
    if (cv) {
      if (checkEqualInitialValue(field)) {
        return setState(false);
      }
    }
    if (value && value !== preField.current && error.length === 0) {
      preField.current = value;
      setState(null);
      const result = await checkExits(field);
      setState(result);
    }
  };

  const checkEqualInitialValue = field => {
    const value = form.getFieldValue(field);
    const initial = cv[field];
    if (!value || !initial) return false;
    return value === initial;
  };

  const checkIsEqualInitialForUpdateCv = () => {
    return (
      checkEqualInitialValue('position_id') &&
      checkEqualInitialValue('fullname') &&
      checkEqualInitialValue('level_id')
    );
  };

  const onBlurBasicInfo = async () => {
    if (cv) {
      if (checkIsEqualInitialForUpdateCv()) {
        return setIsExistsBasicInfo(false);
      }
    }
    if (
      isValidField('position_id') &&
      isValidField('fullname') &&
      isValidField('level_id')
    ) {
      setIsExistsBasicInfo(null);
      const result = await checkExits(
        ['position_id', 'fullname', 'level_id'],
        `${form.getFieldValue('fullname')} ${t('cv.existInfo')}`,
      );
      setIsExistsBasicInfo(result);
    }
  };

  const onBlurMobile = () => {
    handleBlur('mobile', prevMobile, setIsExistsMobile);
  };

  const onBlurEmail = () => {
    handleBlur('email', prevEmail, setIsExistsEmail);
  };

  function confirmWithDuplicate(content, values) {
    Modal.confirm({
      title: t('updateCv.existsWarning'),
      content: content,
      icon: <ExclamationCircleOutlined />,
      okText: t('typework.okConfirm'),
      cancelText: t('typework.cancelConfirm'),
      onOk: () => sendDataToServer(values),
    });
  }

  const finishWithCheckExists = async values => {
    const basicInfoCondition =
      values.fullname &&
      values.level_id &&
      values.position_id &&
      (cv ? !checkIsEqualInitialForUpdateCv() : true);

    const mobileCondition =
      values.mobile && (cv ? !checkEqualInitialValue('mobile') : true);

    const emailCondition =
      values.email && (cv ? !checkEqualInitialValue('email') : true);

    const arr = [];
    let email = null,
      mobile = null,
      basicInfo = null;

    if (mobileCondition) {
      mobile =
        isExistsMobile !== null ? isExistsMobile : await checkExits('mobile');
    }

    if (emailCondition) {
      email =
        isExistsEmail !== null ? isExistsEmail : await checkExits('email');
    }

    if (basicInfoCondition) {
      basicInfo =
        isExistsBasicInfo !== null
          ? isExistsBasicInfo
          : await checkExits(
              ['position_id', 'fullname', 'level_id'],
              `${form.getFieldValue('fullname')} ${t('cv.existInfo')}`,
            );
    }

    if (email) arr.push(email);
    if (mobile) arr.push(mobile);
    if (basicInfo) arr.push(basicInfo);
    if (arr.length > 0) confirmWithDuplicate(arr.join(', '), values);
    else sendDataToServer(values);
  };

  return {
    isExistsMobile,
    isExistsEmail,
    onBlurMobile,
    onBlurEmail,
    onBlurBasicInfo,
    finishWithCheckExists,
  };
}
