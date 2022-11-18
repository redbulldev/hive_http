import { Col, Form, InputNumber, Row } from 'antd';
import moment from 'moment';
import 'moment/locale/vi';
import { memo, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import 'react-quill/dist/quill.snow.css';
import { useSelector } from 'react-redux';
import { useLocation } from 'react-router-dom';
import {
  FormDatePicker,
  FormEditor,
  FormInput,
  LanguageSelect,
  LevelSelect,
  PositionSelect,
  UserSelect,
} from '../../../components/Form';
import PrioritySelect from '../../../components/Form/Selects/PrioritySelect';
import TypeworkSelect from '../../../components/Form/Selects/TypeworkSelect';
import { DAY_FORMAT } from '../../../constants';
import {
  DEADLINE_DEFAULT,
  DEFAULT_MAX_LENGTH_DESCRIPTION,
  DEFAULT_PRIORITY,
  DETAIL_TITLE_FORM,
  QUANTITY_RULES,
} from '../../../constants/requestPage';

import { hasPermission } from '../../../utils/hasPermission';
import useFetchDefaultField from './useFetchDefaultField';
import useFetchDetailRequest from './useFetchDetailRequest';
function RequestForm({ titleForm, form }) {
  const { t } = useTranslation();
  const { listDeadlineDay, listLevel, listPosition, listTypeWork } =
    useFetchDefaultField();
  const location = useLocation();
  const [disableEditor, setDisableEditor] = useState(false);
  const [, forceUpdate] = useState({}); // To disable submit button at the beginning.
  const userInfor = useSelector(state => state.auth.userInfor);
  const pathname = location.pathname.split('/').filter(x => x);
  const { detailRequest: requestFormInfo } = useFetchDetailRequest(pathname[2]);
  let groupPosition = {};
  let firstPosition = {};

  if (Object.keys(listPosition).length) {
    groupPosition = listPosition.reduce((groups, item) => {
      if (item.parent_title) {
        const group = groups[item.parent_title] || [];
        group.push(item);
        groups[item.parent_title] = group;
      }
      return groups;
    }, {});
    firstPosition = groupPosition[Object.keys(groupPosition)?.[0]]?.[0];
  }

  useEffect(() => {
    forceUpdate({});
  }, []);
  useEffect(() => {
    const detail = location.pathname.includes('/request/detail');
    if (detail && requestFormInfo.status === 0) {
      setDisableEditor(true);
    } else {
      setDisableEditor(false);
    }
  }, [location.pathname, requestFormInfo]);

  useEffect(() => {
    form.resetFields();
  }, [location]);
  const levelDrawer =
    requestFormInfo.levels !== undefined && JSON.parse(requestFormInfo.levels);
  const arrId = levelDrawer?.length ? levelDrawer.map(item => item.id) : [];

  const getLevelValue = () => {
    const arr = listLevel
      .filter(level => arrId.includes(level.id))
      .map(item => item.id);

    if (arr.length === listLevel.length)
      return [...listLevel.map(item => item.id)];

    return arr;
  };
  useEffect(() => {
    const path = location.pathname;
    form.resetFields();
    if (path.includes('add')) {
      form.setFieldsValue({
        requestor_id: userInfor.username,
        position_id: hasPermission(userInfor, 'positions', 'view')
          ? firstPosition?.id
          : '',
        levels: [],
        priority: DEFAULT_PRIORITY[1].value,
        typework_id: '',
      });
    } else {
      form.setFieldsValue({
        ...requestFormInfo,
        requestor_id: requestFormInfo.requestor_id,
        position_id: requestFormInfo.position_id || undefined,
        levels: requestFormInfo.levels ? getLevelValue() : [],
        quantity: requestFormInfo.target,
        typework_id: requestFormInfo.typework_id || undefined,
        deadline: requestFormInfo.deadline
          ? moment(requestFormInfo.deadline, DEADLINE_DEFAULT)
          : undefined,
        languages: requestFormInfo.languages
          ? JSON.parse(requestFormInfo.languages)
          : undefined,
        priority: requestFormInfo.priority,
        description: requestFormInfo.description,
      });
    }

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [
    location.pathname,
    listLevel,
    listTypeWork,
    listPosition,
    listDeadlineDay,
    requestFormInfo,
  ]);

  const disableDate = current => {
    const { daybefore, dayafter } = listDeadlineDay;
    const earlyDate = current <= moment().add(daybefore - 1, 'days');
    const lateDate = current >= moment().add(dayafter - 1, 'days');
    return earlyDate || lateDate;
  };

  const onChangeLevel = list => {
    const lastClickedItem = list[list.length - 1];
    if (list.includes(-1)) {
      if (list.length === listLevel.length && lastClickedItem !== -1) {
        form.setFieldsValue({
          levels: list.filter(item => item !== -1),
        });
      } else {
        form.setFieldsValue({
          levels: [...listLevel.map(item => item.id)],
        });
      }
    } else {
      if (list.length === listLevel.length) {
        form.setFieldsValue({
          levels: [...listLevel.map(item => item.id)],
        });
      }
    }
  };

  const isDisabled = location.pathname.includes('detail');
  return (
    <Row gutter={{ sm: 25, xl: 125 }}>
      <Col sm={24} xl={12} style={{ flexBasis: '100%' }}>
        <UserSelect
          name="requestor_id"
          label={t('request.requestor')}
          disabled={
            !hasPermission(userInfor, 'request', 'all') ||
            titleForm === DETAIL_TITLE_FORM
          }
          required
        />
        <PositionSelect
          name="position_id"
          label={`${t('request.position')}`}
          required
          disabled={isDisabled}
        />
        <LevelSelect
          label={`${t('request.level')}`}
          required
          name="levels"
          mode="multiple"
          disabled={isDisabled}
          onChange={onChangeLevel}
          selectAll
          form={form}
        />
        <Form.Item
          name="quantity"
          label={
            <span className="field--required">
              {t('request.quantity')}(<span>*</span>)
            </span>
          }
          rules={QUANTITY_RULES}
        >
          <InputNumber
            type="number"
            min={1}
            max={99}
            style={{ width: '100%' }}
            disabled={isDisabled}
            placeholder={t('request.placeholderQuantity')}
          />
        </Form.Item>

        <FormInput
          autoSize={{ minRows: 25 }}
          showCount={false}
          allowClear
          textArea
          max={DEFAULT_MAX_LENGTH_DESCRIPTION}
          placeholder={t('request.placeholderDescription')}
          label={t('request.description')}
          name="description"
          disabled={isDisabled}
        />
      </Col>
      <Col sm={24} xl={12}>
        <TypeworkSelect
          label={`${t('request.typework')}`}
          name="typework_id"
          required
          disabled={isDisabled}
        />

        <LanguageSelect
          name="languages"
          label={t('request.language')}
          mode="multiple"
          disabled={isDisabled}
        />
        <PrioritySelect
          name="priority"
          label={t('request.priority')}
          disabled={isDisabled}
        />
        <FormDatePicker
          name="deadline"
          label={t('request.deadline')}
          hasFeedback={!isDisabled}
          required
          disabled={isDisabled}
          format={DAY_FORMAT}
          disabledDate={disableDate}
          picker="date"
        />
        <FormEditor name="jd" label="JD" form={form} disabled={disableEditor} />
      </Col>
    </Row>
  );
}

export default memo(RequestForm);
