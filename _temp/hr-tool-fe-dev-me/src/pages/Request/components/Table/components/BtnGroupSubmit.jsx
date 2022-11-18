import { CloseOutlined, SaveOutlined } from '@ant-design/icons';
import { Button } from 'antd';
import { useTranslation } from 'react-i18next';
import { useLocation, useNavigate } from 'react-router-dom';
import { SubmitBtn } from '../../../../../components/Form';

export default function BtnGroupSubmit({ form, requestFormInfo = null }) {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const location = useLocation();
  const isDetailPage = location.pathname.includes('request/detail');

  const create = !isDetailPage && requestFormInfo?.status !== 0;

  return (
    <>
      {create && (
        <div className="btn-wrapper">
          <SubmitBtn
            requiredFields={[
              'requestor_id',
              'levels',
              'typework_id',
              'deadline',
              'position_id',
              'quantity',
            ]}
            form={form}
            icon={<SaveOutlined />}
          >
            {t('request.btnSave')}
          </SubmitBtn>
          <Button
            style={{ marginLeft: 20 }}
            onClick={() => navigate('/request')}
          >
            <CloseOutlined />
            {t('request.btnCancel')}
          </Button>
        </div>
      )}
    </>
  );
}
