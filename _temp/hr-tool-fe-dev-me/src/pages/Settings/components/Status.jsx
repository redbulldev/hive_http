import { Radio } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { FormItem } from '../../../components/Form';
import { LIST_STATUS } from '../../../constants';

export default function Status() {
  const { t } = useTranslation();
  return (
    <FormItem name="status" label={t('typework.statusColumn')}>
      <Radio.Group>
        {LIST_STATUS.map(status => (
          <Radio key={status.id} value={status.value}>
            {t(`typework.${status.title}`)}
          </Radio>
        ))}
      </Radio.Group>
    </FormItem>
  );
}
