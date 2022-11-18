import { Input, InputNumber } from 'antd';
import React, { useState } from 'react';
import FormItem from './FormItem';
import './scss/formInput.scss';
import { TagInput } from '../CustomBase/';

export default function FormInput(props) {
  const {
    name,
    required,
    label,
    placeholder,
    min,
    max,
    rows,
    form,
    textArea,
    disabled,
    tag,
    existMessage,
    onChange,
    autoSize,
    style,
    className,
    onBlur,
    inputClassName,
    showCount = true,
    hasFeedBack,
    type,
    inputNumber,
    autoWidth,
    onKeyPress,
    defaultValue,
    value,
    minNumber,
    maxNumber,
    allowClear,
    rules,
  } = props;

  const [inputWidth, setInputWidth] = useState(autoWidth);

  const lowerLabel = label.toLowerCase();

  const formItemProps = {
    name,
    required,
    label,
    min,
    style,
    className,
    hasFeedBack,
    rules,
    form,
  };

  const getInput = () => {
    if (tag) return TagInput;
    if (textArea) return Input.TextArea;
    if (inputNumber)
      return props => {
        return <InputNumber {...props} style={{ width: '100%' }} />;
      };
    return Input;
  };

  const InputComponent = getInput();

  const onChangeInput = e => {
    if (onChange) onChange(e);
    if (autoWidth) {
      const nextWidth = e.target.value.length * 9;
      if (nextWidth > autoWidth) {
        setInputWidth(nextWidth);
      }
    }
  };

  const inputProps = {
    placeholder,
    disabled,
    onBlur,
    onKeyPress,
    type,
    defaultValue,
    value,
    allowClear,
    onChange: onChangeInput,
  };

  if (tag && existMessage) inputProps.existMessage = existMessage;

  inputProps.maxLength = max || 200;
  if (max) inputProps.maxLength = max;
  if (inputWidth) inputProps.style = { width: inputWidth };
  if (inputClassName) inputProps.className = inputClassName;

  if (textArea) {
    if (!placeholder) inputProps.placeholder = `Nhập ${lowerLabel}`;
    inputProps.maxLength = max || 5000;
    inputProps.autoSize = autoSize || { minRows: rows || 4 };
    if (showCount) inputProps.showCount = true;
  }

  return (
    <FormItem
      {...formItemProps}
      className={`${tag ? 'tag-input' : ''} ${className ? className : ''}`}
    >
      <InputComponent {...inputProps} min={minNumber} max={maxNumber} />
    </FormItem>
  );
}
