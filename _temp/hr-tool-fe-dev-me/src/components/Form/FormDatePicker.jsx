import { DatePicker } from 'antd';
import React from 'react';
import FormItem from './FormItem';

export default function FormDatePicker(props) {
  const {
    name,
    required,
    label,
    style,
    className,
    disabled,
    placeholder,
    hasFeedBack,
    ...rest
  } = props;
  const lowerLabel = label.toLowerCase();

  const formItemProps = {
    name,
    required,
    label,
    style,
    className,
    hasFeedBack,
    type: 'select',
  };
  const datePickerProps = {
    disabled,
    getPopupContainer: triggerNode => triggerNode.parentNode,
  };
  datePickerProps.placeholder = placeholder || `Chọn ${lowerLabel}`;
  return (
    <FormItem {...formItemProps}>
      <DatePicker
        {...datePickerProps}
        {...rest}
        style={{ width: '100%' }}
        format={'DD/MM/YYYY'}
      />
    </FormItem>
  );
}
