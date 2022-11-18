import { CloseOutlined } from '@ant-design/icons';
import { Button, Col, Form, message, Row, Select } from 'antd';
import moment from 'moment';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import planApi from '../../../api/planApi';
import typeworkApi from '../../../api/typeworkApi';
import {
  FormDatePicker,
  FormEditor,
  FormInput,
  FormItem,
  LanguageSelect,
  LevelSelect,
  SourceSelect,
  SubmitBtn,
  UserSelect,
} from '../../../components/Form';
import { useNavigate, useLocation } from 'react-router-dom';
import TypeworkSelect from '../../../components/Form/Selects/TypeworkSelect';
import { LIST_ASSESSMENT } from '../../../constants';
import {
  DEFAULT_MAX_LENGTH_DESCRIPTION,
  DEFAULT_PRIORITY,
} from '../../../constants/requestPage';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import qs from 'query-string';
import PrioritySelect from '../../../components/Form/Selects/PrioritySelect';
import { useFetchLevel } from '../../../components/Hooks/FetchApi';
export default function EditPlanForm() {
  const { Option } = Select;
  const location = useLocation();
  const { defaultValueForm } = useSelector(state => state.detailParams);
  const timeParams = qs.parse(location.search);
  const [typework, setTypework] = useState([]);
  const navigate = useNavigate();
  const {
    typework_id,
    id,
    deadline,
    assignee_id,
    assessment,
    sources,
    description,
    typework_title,
    levels,
    year,
    target,
    position_title,
    onboard_cv,
    month,
    requestor_id,
    languages,
    priority,
  } = defaultValueForm;
  const [form] = Form.useForm();
  const { items: levelsArray } = useFetchLevel();
  useEffect(() => {
    typeworkApi
      .getAll({ limit: 0 })
      .then(res => {
        setTypework(res.data.data);
      })
      .catch(console.log);
  }, []);
  const { t } = useTranslation();

  useEffect(() => {
    form.setFieldsValue({
      typework_id: typework_title
        ? typework.find(item => typework_title === item.title)?.id
        : null,
      assignee_id,
      assessment,
      sources: sources ? JSON.parse(sources) : [],
      description,
      doing_date: moment(`${year}-${month}`),
      requestor_id: requestor_id ? requestor_id : '',
      status:
        onboard_cv < target
          ? 'Ongoing'
          : onboard_cv === target
          ? 'Done'
          : 'Cancel',
      position: position_title ? position_title : '',
      levels: levels ? JSON.parse(levels).map(x => x.id) : [],
      amount: target ? target : '',
      description: description ? description : '',
      languages: languages ? JSON.parse(languages) : [],
      priority: priority ? DEFAULT_PRIORITY[priority].title : '',
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [typework]);
  const breadcrumbNameMap = {
    '/plan': t('sidebar.plan'),
    '/plan/detail': `${t('plan.detail')} ${
      timeParams.month || moment().format('MM')
    }/${timeParams.year || moment().format('YYYY')}`,
    '/plan/detail/edit': t('plan.fix_plan'),
    params: `?month=${month}&year=${year}`,
  };
  const handleDisableDate = dates => {
    const momentDeadline = moment(deadline, 'YYYY-MM-DD');
    return (
      dates.isAfter(momentDeadline) ||
      dates.isBefore(moment(moment().format('YYYY-MM-DD')))
    );
  };
  const onFinish = value => {
    delete value.status;
    delete value.requestor_id;
    delete value.position;
    delete value.languages;
    const date = value.doing_date;
    value = {
      ...value,
      levels: levelsArray
        .filter(item => value.levels.includes(item.id))
        .map(({ id, title }) => ({ id, title })),
      target: value.amount,
      month: +date.format('MM'),
      year: +date.format('YYYY'),
      day: +date.format('DD'),
    };

    delete value.amount;
    delete value.doing_date;
    planApi
      .fixPlan(id, {
        ...value,
      })
      .then(resp => {
        message.success(t('plan.fix_successfully'));
        form.resetFields();
        navigate(-1);
      })
      .catch(e => {
        if (!e?.data?.status) {
          message.error(t('plan.network_error'));
        } else {
          message.error(t('plan.fix_failed'));
        }
      });
  };
  const handleCancelEditPlan = () => {
    navigate(-1);
  };
  return (
    <Form
      layout="vertical"
      form={form}
      onFinish={onFinish}
      className="plan-form"
    >
      <LayoutBreadcrumb
        breadcrumbNameMap={breadcrumbNameMap}
        extra={[
          <SubmitBtn
            requiredFields={['typework_id', 'assignee_id', 'doing_date']}
            form={form}
            className="submit-drawer-plan"
          >
            {t('plan.save')}
          </SubmitBtn>,
          <Button onClick={handleCancelEditPlan}>
            <CloseOutlined />
            {t('request.btnCancel')}
          </Button>,
        ]}
        component={
          <Row gutter={{ sm: 25, xl: 125 }}>
            <Col sm={24} xl={12}>
              <FormInput label={t('plan.status')} name="status" disabled />
              <UserSelect
                name="assignee_id"
                label={t('plan.assignee')}
                required
              />
              <FormInput
                label={t('plan.requestor')}
                name="requestor_id"
                disabled
              />
              <FormInput label={t('plan.position')} name="position" disabled />

              <LevelSelect
                label={t('plan.level')}
                name="levels"
                mode="multiple"
              />

              <FormInput
                label={t('plan.amount')}
                inputNumber
                name="amount"
                placeholder={'Vd: 10'}
              />

              <FormInput
                showCount={false}
                style={{ marginTop: 60 }}
                autoSize={{ minRows: 25 }}
                allowClear
                textArea
                max={DEFAULT_MAX_LENGTH_DESCRIPTION}
                placeholder={t('request.placeholderDescription')}
                label={t('request.description')}
                name="description"
              />
            </Col>
            <Col sm={24} xl={12}>
              <SourceSelect
                mode="multiple"
                name="sources"
                label={t('plan.recruitment_chanel')}
              />
              <FormItem
                name="assessment"
                label={t('plan.assessment')}
                labelCol={{ span: 24 }}
              >
                <Select>
                  {LIST_ASSESSMENT.map(item => (
                    <Option key={item.id} value={item.value}>
                      {item.title}
                    </Option>
                  ))}
                </Select>
              </FormItem>
              <TypeworkSelect
                label={t('plan.typework')}
                name="typework_id"
                required
              />
              <LanguageSelect
                label={t('plan.language')}
                name="languages"
                disabled
              />
              <PrioritySelect label={t('plan.priority')} name="priority" />
              <FormDatePicker
                name="doing_date"
                label={t('plan.doing_date')}
                required
                format="DD-MM-YYYY"
                disabledDate={handleDisableDate}
                picker="date"
              />

              {deadline && (
                <p style={{ color: 'red' }}>
                  {t('plan.deadline')}: {moment(deadline).format('DD/MM/YYYY')}
                </p>
              )}
              <FormEditor name="jd" label="JD" form={form} />
            </Col>
          </Row>
        }
      />
    </Form>
  );
}
