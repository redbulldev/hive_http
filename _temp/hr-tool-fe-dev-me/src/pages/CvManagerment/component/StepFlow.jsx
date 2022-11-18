import { DoubleRightOutlined, LoadingOutlined } from '@ant-design/icons';
import { Steps, Button } from 'antd';
import React, { useState, useCallback, useRef, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { CV_STATUS_DEFAULT_VALUE, CV_STEP } from '../../../constants';
import HrReview from '../steps/HrReview';
import Physiognomy1 from '../steps/Physiognomy1';
import CvReview from '../steps/CvReview';
import PreInterview from '../steps/PreInterview';
import Interview from '../steps/Interview';
import Physiognomy2 from '../steps/Physiognomy2';
import PreOffer from '../steps/PreOffer';
import Offer from '../steps/Offer';
import Onboard from '../steps/Onboard';
import Probation from '../steps/Probation';
import { getStep } from '../constant';
import { useSelector } from 'react-redux';

const { Step } = Steps;

export default function StepFlow({ cv, reload, stepState }) {
  const { t } = useTranslation();
  const { userInfor } = useSelector(state => state.auth);
  const permission = userInfor?.permission?.cv;

  const { visible, setVisible, currentStep, setCurrentStep } = stepState;

  const { step, status } = cv;
  const detailStep = getStep(step, status);
  const { stepValue, statusValue } = detailStep || {};

  useEffect(() => {
    if (stepValue) {
      setCurrentStep(stepValue);
      // scroll to last step
      const element = document.querySelector('.last-step');
      element?.scrollIntoView();
    }
  }, [stepValue]);

  useEffect(() => {
    if (!visible) {
      setTimeout(() => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth',
        });
      }, 500);
    }
  }, [visible]);

  const onClickStep = value => {
    value = value + 1;
    setCurrentStep(value);
    if (value === currentStep) {
      setVisible(!visible);
    } else {
      setVisible(true);
    }
  };

  const toggleStep = () => {
    setVisible(!visible);
  };

  const WhichStep = () => {
    const props = {
      currentStep,
      cv,
      reload,
      step: stepValue,
      status: statusValue,
      isDecision: permission.decision,
      onClose: () => setVisible(false),
    };

    switch (currentStep) {
      case 1:
        return <HrReview {...props} />;
      case 2:
        return <Physiognomy1 {...props} />;
      case 3:
        return <CvReview {...props} />;
      case 4:
        return <PreInterview {...props} />;
      case 5:
        return <Interview {...props} />;
      case 6:
        return <Physiognomy2 {...props} />;
      case 7:
        return <PreOffer {...props} />;
      case 8:
        return <Offer {...props} />;
      case 9:
        return <Onboard {...props} />;
      case 10:
        return <Probation {...props} />;
      default:
        return null;
    }
  };

  return (
    <div className="step-flow">
      <h3>{t('cvDetail.flowHeader')}</h3>
      <div className="flow">
        <Steps
          labelPlacement="vertical"
          size="small"
          className="steps"
          current={stepValue - 1}
        >
          {CV_STEP.map((step, i) => {
            if (i === 0) return null;
            return (
              <Step
                title={t(`cv.${step.title}`)}
                key={i}
                disabled={i > stepValue}
                onStepClick={onClickStep}
                icon={
                  <div
                    className={`step-circle ${i > stepValue ? 'disabled' : ''}`}
                  >
                    {i}
                  </div>
                }
                className={`${
                  i === stepValue &&
                  statusValue === CV_STATUS_DEFAULT_VALUE.FAILED
                    ? 'failed'
                    : ''
                } ${i === currentStep && visible ? 'active-show' : ''} ${
                  i === stepValue ? 'last-step' : ''
                }`}
              />
            );
          })}
        </Steps>
      </div>
      {visible && (
        <div className="step-main">
          <WhichStep />
        </div>
      )}
      <div className="toggle" onClick={toggleStep}>
        <DoubleRightOutlined
          rotate={visible ? 270 : 90}
          style={{ fontSize: 18 }}
        />
      </div>
    </div>
  );
}
