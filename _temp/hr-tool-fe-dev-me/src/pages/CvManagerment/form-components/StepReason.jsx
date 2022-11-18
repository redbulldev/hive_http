import { Form, Input } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';

export default function StepReason({
  form,
  name,
  label,
  placeholder,
  autoSize,
}) {
  const { t } = useTranslation();
  return (
    <Form.Item shouldUpdate noStyle>
      {() => {
        const condition = form.getFieldValue('status') === 0;
        const rules = [];
        if (condition)
          rules.push({
            whitespace: true,
            required: true,
            message: t('hrinterview.requiredReason'),
          });
        return (
          <Form.Item
            name={name || 'reason'}
            label={
              condition ? (
                <p className="field--required" style={{ marginBottom: 0 }}>
                  {label || t('hrinterview.reason')} (<span>*</span>)
                </p>
              ) : (
                label || t('hrinterview.reason')
              )
            }
            rules={rules}
          >
            <Input.TextArea
              autoSize={autoSize || { minRows: 4 }}
              showCount
              maxLength={5000}
              placeholder={placeholder || t('hrinterview.reasonPlaceholder')}
            />
          </Form.Item>
        );
      }}
    </Form.Item>
  );
}
