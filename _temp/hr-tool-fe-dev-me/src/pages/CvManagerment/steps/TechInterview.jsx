import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import {
  CHILDREN_NUMBER,
  POINT_LADDER,
  requiredFields,
} from '../../../constants/techInterview';
import { Col, Row, Form, Input } from 'antd';
import { LevelSelect } from '../../../components/Form';
import techInterviewApi from '../../../api/techInterviewApi';

function TechInterview(props) {
  const {
    form,
    Frame,
    FormInput,
    isFailed,
    FormTextArea,
    Reason,
    Status,
    setConfig,
    step,
    status,
  } = props;
  const { t } = useTranslation();

  const onChangePoint = (e, field) => {
    const value = e.target.value;
    let result = value;
    const valueWithoutDot = value.replace('.', '');
    if (/^\./.test(value) /* || /\.$/.test(value) */) {
      // console.log('ok');
      result = valueWithoutDot;
    }
    if (result > 10) result = 10;
    form.setFieldsValue({
      [field]: result,
    });
  };
  const inputNumberProps = {
    type: 'number',
    disabled: isFailed,
  };
  const pointRules = [
    {
      validator(_, value) {
        value = Number(value);
        if (value < 0) return Promise.reject(t('techinterview.pointMin'));
        if (value > 10) return Promise.reject(t('techinterview.pointMax'));
        return Promise.resolve();
      },
    },
  ];

  useEffect(() => {
    setConfig({
      api: techInterviewApi.getById,
      submitApi: techInterviewApi.post,
      requiredFields: requiredFields,
      submitObject: values => {
        return values;
      },
    });
  }, []);
  return (
    <Frame title={t(`techinterview.title`)}>
      <Row gutter={20}>
        <Col span={12}>
          <FormTextArea
            required
            name="language"
            label={t(`techinterview.language`)}
            rows={2}
          />
          <FormTextArea
            required
            name="expertise"
            label={t(`techinterview.tech`)}
            rows={2}
          />
          <FormTextArea
            required
            name="character_note"
            label={t(`techinterview.suitable`)}
            rows={2}
          />
          <FormTextArea
            required
            name="knowledge"
            label={t(`techinterview.skill`)}
            rows={2}
          />
          <FormTextArea
            required
            name="self_appraisal"
            label={t(`techinterview.appraisal`)}
            rows={2}
          />
          <FormTextArea
            required
            name="career_direction"
            label={t(`techinterview.orient`)}
            rows={2}
          />
        </Col>
        <Col span={12}>
          <FormTextArea
            required
            name="questions"
            label={t(`techinterview.questions`)}
            rows={2}
          />
          <FormTextArea name="notes" label={t(`techinterview.notes`)} />
          <LevelSelect
            name="level_id"
            label={t('techinterview.level')}
            disabled={isFailed}
          />
          {/* Begin: point rate */}
          <div style={{ marginBottom: 5 }}>
            {`${t('techinterview.point')} (${t(
              'techinterview.pointLadder',
            )} ${POINT_LADDER}) `}
          </div>
          <div className="wrapper--horizontal">
            <Form.Item
              name="point_tech"
              label={t('techinterview.techPoint')}
              rules={pointRules}
            >
              <Input
                {...inputNumberProps}
                onChange={e => onChangePoint(e, 'point_tech')}
              />
            </Form.Item>
            <Form.Item
              name="point_handler"
              label={t('techinterview.handlerPoint')}
              rules={pointRules}
            >
              <Input
                {...inputNumberProps}
                onChange={e => onChangePoint(e, 'point_handler')}
              />
            </Form.Item>
            <Form.Item
              name="point_thinking"
              label={t('techinterview.thinkingPoint')}
              rules={pointRules}
            >
              <Input
                {...inputNumberProps}
                onChange={e => onChangePoint(e, 'point_thinking')}
              />
            </Form.Item>
            <Form.Item shouldUpdate noStyle>
              {() => {
                const g = s => Number(form.getFieldValue(s) || 0);
                return (
                  <div>
                    <label>{`${t('techinterview.pointsSum')}: `}</label>
                    {g('point_tech') + g('point_handler') + g('point_thinking')}
                    /{POINT_LADDER * CHILDREN_NUMBER}
                  </div>
                );
              }}
            </Form.Item>
          </div>
          {/* End: point rate */}
          <Status step={step} status={status} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(TechInterview);
