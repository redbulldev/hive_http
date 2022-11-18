import { Col, Form } from 'antd';
import React, { useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { FormInput, UserSelect } from '../../../../components/Form';
import DepartmentSelect from '../../../../components/Form/Selects/DepartmentSelect';
import { Filter } from '../../../../components/Table';

function PositionFilter(props) {
  const { isDepartment } = props;
  const [form] = Form.useForm();
  const { t } = useTranslation();

  const FormContent = useCallback(() => {
    return (
      <>
        {isDepartment ? (
          ''
        ) : (
          <>
            <Col span={4}>
              <DepartmentSelect
                label={t('position.department')}
                mode="multiple"
                name="parent_id"
              />
            </Col>

            <Col span={4}>
              <UserSelect
                label={t('position.manager')}
                name="manager_id"
                mode="multiple"
              />
            </Col>
            <Col span={4}>
              <UserSelect
                label={t('position.requestor')}
                name="requestor"
                mode="multiple"
              />
            </Col>
          </>
        )}
        <Col span={isDepartment ? 9 : 5}>
          <FormInput
            name="key"
            label={t('position.key')}
            placeholder={t('position.placeholderInput')}
          />
        </Col>
      </>
    );
  }, []);

  return <Filter {...props} form={form} FormContent={FormContent} />;
}

export default React.memo(PositionFilter);
