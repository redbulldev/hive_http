import { SendOutlined } from '@ant-design/icons';
import { Button } from 'antd';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useNavigate, useParams } from 'react-router-dom';
import cvApi from '../../../api/cvApi';
import { BoxShadow } from '../../../components/Layout';
import { breadcrumbsCv } from '../../../constants/newAddCv';
import LayoutBreadcrumb from '../../Request/components/LayoutBreadcrumb';
import useFetchEmail from '../hooks/useFetchEmail';
import '../scss/cvDetail.scss';
import CvInfo from './CvInfo';
import HistoryTable from './HistoryTable';
import StepFlow from './StepFlow';

export default function Detail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const [cv, setCv] = useState({});
  const [reload, setReload] = useState(false);

  const [visible, setVisible] = useState(false); /* Visible step Cv */
  const [currentStep, setCurrentStep] = useState(null);

  const fetchCv = async () => {
    try {
      const res = await cvApi.getById(id);
      setCv(res.data.data);
    } catch (e) {
      console.log('e :', e);
    }
  };

  useEffect(() => {
    fetchCv();
  }, [reload]);

  const { emailTemplate, totalHistoryEmail } = useFetchEmail(
    id,
    cv.step,
    cv.status,
  );
  const handleOpenSendEmail = () => {
    navigate(`/cv/${cv.id}/sendEmail`);
  };
  // End: email handle

  return (
    <LayoutBreadcrumb
      breadcrumbNameMap={{
        ...breadcrumbsCv,
        [`/cv/${id}`]: `${cv.fullname || ''} (${id})`,
      }}
      extra={[
        emailTemplate?.isauto === 0 && (
          <Button type="primary" onClick={handleOpenSendEmail}>
            <SendOutlined />
            {t('updateCv.send_mail')}
          </Button>
        ),
      ]}
      component={
        <div className="cv-detail-temp">
          <div className="cv-detail ">
            {/* Begin: step flow */}
            <div className="step-flow-wrapper cv-detail-box">
              <BoxShadow>
                <StepFlow
                  cv={cv}
                  reload={() => setReload(!reload)}
                  stepState={{
                    visible,
                    setVisible,
                    currentStep,
                    setCurrentStep,
                  }}
                />
              </BoxShadow>
            </div>
            {/* Begin: Information */}
            <div className="cv-information cv-detail-box">
              <BoxShadow>
                <CvInfo
                  cv={cv}
                  totalHistoryEmail={totalHistoryEmail}
                  stepState={{
                    setVisible,
                    setCurrentStep,
                  }}
                />
              </BoxShadow>
            </div>
            {/* Begin: history table*/}
            <div className="cv-history cv-detail-box">
              <HistoryTable justReload={reload} />
            </div>
          </div>
        </div>
      }
    />
  );
}
