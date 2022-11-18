import { EditOutlined, PlusCircleFilled } from '@ant-design/icons';
import { Button, Form } from 'antd';
import React from 'react';
export default function SubmitBtn({
  form,
  children,
  style,
  className,
  requiredFields = [],
  onClick,
  disabled,
  loading,
  normalBtn = false,
  fieldsToCheck = () => 'normal',
  condition = () => false,
  create = true,
  icon,
}) {
  const formItemProps = { style, className };
  const submitBtnProps = { onClick, loading };
  if (!normalBtn) submitBtnProps.htmlType = 'submit';
  const g = form.getFieldValue;
  let requireCondition = false;
  let errorCondition = false;
  const normalCheck = () => {
    requireCondition = requiredFields.some(
      field =>
        [undefined, null, ''].includes(g(field)) || g(field)?.length === 0,
    );

    // Check required condition
    // requiredFields.forEach(field => {
    //   console.log(field, g(field));
    // });

    const errors = form.getFieldsError();
    errorCondition = errors.some(field => field.errors.length > 0);
  };
  const noCheck = () => {
    requireCondition = false;
    errorCondition = false;
  };
  const specificCheck = () => {
    console.log('do something');
  };
  return (
    <Form.Item noStyle shouldUpdate>
      {() => {
        const checkFields = fieldsToCheck();

        if (checkFields === 'normal') normalCheck();
        if (checkFields === 'noCheck') noCheck();
        if (typeof checkFields === 'object') specificCheck();
        const moreCondition = condition();
        return (
          <Form.Item
            {...formItemProps}
            className={'submit-btn ' + (className || '')}
          >
            <Button
              {...submitBtnProps}
              type="primary"
              icon={
                icon ? icon : create ? <PlusCircleFilled /> : <EditOutlined />
              }
              disabled={
                requireCondition || errorCondition || moreCondition || disabled
              }
              style={{ textTransform: 'uppercase' }}
            >
              {children}
            </Button>
          </Form.Item>
        );
      }}
    </Form.Item>
  );
}
