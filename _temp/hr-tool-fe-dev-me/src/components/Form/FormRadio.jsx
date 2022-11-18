import { Radio } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { FormItem } from '../../components/Form';

export default function FormRadio(props) {
  const { items = [], onChange, ...formItemProps } = props;
  return (
    <FormItem {...formItemProps}>
      <Radio.Group onChange={onChange}>
        {items.map(item => (
          <Radio key={item.value} value={item.value}>
            {item.label}
          </Radio>
        ))}
      </Radio.Group>
    </FormItem>
  );
}
