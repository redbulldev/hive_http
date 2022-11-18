import { Col, Form, Input, Row } from 'antd';
import { t } from 'i18next';
import React, { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { checkStringNotAllow } from '../../../../constants/requestPage';
import {
  MAX_DAY_REQUEST_RULE,
  MIN_DAY_REQUEST_RULE,
} from '../../../../constants/settingsPage';
import { hasPermission } from '../../../../utils/hasPermission';

function SettingContent({ form, listDeadlineDay }) {
  useEffect(() => {
    form.setFieldsValue({
      daybefore: listDeadlineDay.daybefore,
      dayafter: listDeadlineDay.dayafter,
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [listDeadlineDay]);
  const { userInfor } = useSelector(state => state.auth);

  return (
    <div className="settings__content">
      <Form
        form={form}
        id="settingForm"
        className="settings__content--form form"
      >
        <Row style={{ width: '543px' }}>
          <Col span={20}>
            <Form.Item
              label={t('settings.dayBefore')}
              labelCol={{ span: 12 }}
              labelAlign="left"
              className="form__item"
              name="daybefore"
              rules={MIN_DAY_REQUEST_RULE}
              dependencies={['dayafter']}
            >
              <Input
                maxLength={4}
                onKeyPress={event => checkStringNotAllow(event)}
                disabled={!hasPermission(userInfor, 'general', 'edit')}
              />
            </Form.Item>
          </Col>
          <Col span={4}>
            <span className="form__item--day"> {t('settings.days')}</span>
          </Col>
        </Row>
        <Row style={{ width: '543px' }}>
          <Col span={20}>
            <Form.Item
              label={t('settings.dayAfter')}
              labelCol={{ span: 12 }}
              labelAlign="left"
              className="form__item"
              name="dayafter"
              rules={MAX_DAY_REQUEST_RULE}
              dependencies={['daybefore']}
            >
              <Input
                maxLength={4}
                onKeyPress={event => checkStringNotAllow(event)}
                disabled={!hasPermission(userInfor, 'general', 'edit')}
              />
            </Form.Item>
          </Col>
          <Col span={4}>
            <span className="form__item--day"> {t('settings.days')}</span>
          </Col>
        </Row>
      </Form>
    </div>
  );
}

export default SettingContent;
