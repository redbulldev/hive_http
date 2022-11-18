import React from 'react';
import { Form, DatePicker } from 'antd';
import { DAY_FORMAT } from '../../../constants';
import moment from 'moment';
import i18n from '../../../translation/i18n';
import {
  getPreviousMonth,
  getTwoPreviousMonth,
} from '../../../utils/getPreviousMonth';
import locale from 'antd/es/date-picker/locale/vi_VN';

const { RangePicker: RangePickerAntd } = DatePicker;

export const RangePicker = ({ name, label }) => {
  const newLocale = locale;
  newLocale.lang.rangePlaceholder = [i18n.t('time.from'), i18n.t('time.to')];
  return (
    <Form.Item name={name} label={label}>
      <RangePickerAntd
        locale={newLocale}
        format={DAY_FORMAT}
        ranges={{
          [i18n.t('time.today')]: [moment(), moment()],
          [i18n.t('time.thisMonth')]: [
            moment().startOf('month'),
            moment().endOf('month'),
          ],
          [i18n.t('time.previousMouth')]: [
            getPreviousMonth(new Date()),

            getPreviousMonth(new Date()).endOf('month'),
          ],
          [i18n.t('time.twoPreviousMouth')]: [
            getTwoPreviousMonth(new Date()),
            getPreviousMonth(new Date()).endOf('month'),
          ],
        }}
        style={{ width: '100%' }}
        getPopupContainer={trigger => trigger.parentNode}
      />
    </Form.Item>
  );
};
