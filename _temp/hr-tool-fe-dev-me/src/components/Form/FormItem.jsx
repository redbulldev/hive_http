import { Form } from 'antd';
import React from 'react';

export default function FormItem({
  children,
  name,
  required,
  label,
  min,
  type,
  form,
  style,
  className,
  rules,
}) {
  const props = { name, style, className };
  const lowerLabel = label.toLowerCase();

  const labelValue = required ? (
    <span className="field--required">
      {label} (<span>*</span>)
    </span>
  ) : (
    label
  );

  const rulesFormItem = rules ? [...rules] : [];
  if (required) {
    const placeholderText = type === 'select' ? 'chọn' : 'nhập';
    const message = `Vui lòng ${placeholderText} ${lowerLabel}`;

    const obj = {
      required: true,
      message,
    };

    if (type !== 'select') obj.whitespace = true;
    rulesFormItem.push(obj);
  }

  if (min)
    rulesFormItem.push({
      validator(_, value) {
        value = value?.trim();
        if (value !== '' && value?.length < min)
          return Promise.reject(
            new Error(`${label} cần tối thiểu ${min} kí tự`),
          );
        return Promise.resolve();
      },
    });
  return (
    <Form.Item {...props} rules={rulesFormItem} label={labelValue}>
      {children}
    </Form.Item>
  );
}
