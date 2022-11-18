import {
  CalendarOutlined,
  HomeOutlined,
  MailOutlined,
  MobileOutlined,
} from '@ant-design/icons';
import { Breadcrumb, Col, Row } from 'antd';
import React, { useCallback, useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useNavigate, useParams } from 'react-router-dom';
import { getLevelById } from '../../../api/level/levelApi';
import { positionApi } from '../../../api/positionApi';
import sourceApi from '../../../api/sourceApi';
import departmentImg from '../../../assets/images/cvManagement/department.svg';
import positionImg from '../../../assets/images/cvManagement/position.svg';
import { Button, EditBlueButton } from '../../../components/Buttons';
import { useFetchUser, useGetDetail } from '../../../components/Hooks/FetchApi';
import { DATE_FORMAT } from '../../../constants';
import { GENDER } from '../../../constants/cvDetail';
import { getStep } from '../constant';
import { preProcessCv } from '../constant/cvInfo';
import AvatarShow from './AvatarShow';

export default function CvInfo({ cv, stepState, totalHistoryEmail }) {
  const { t } = useTranslation();
  const { id } = useParams();
  const permission = useSelector(state => state.auth.userInfor.permission.cv);
  const navigate = useNavigate();

  const { step, status } = cv;
  const { setVisible, setCurrentStep } = stepState;
  const detailStep = getStep(step, status);
  const { stepValue, statusValue } = detailStep || {};

  // Begin: get detail information
  const { detail: position } = useGetDetail({
    api: positionApi.getPositionById,
    value: cv.position_id,
  });

  const { detail: department } = useGetDetail({
    api: positionApi.getPositionById,
    value: position.parent_id,
  });

  const { detail: level } = useGetDetail({
    api: getLevelById,
    value: cv.level_id,
  });

  const { detail: lastLevel } = useGetDetail({
    api: getLevelById,
    value: cv.last_level_id,
  });

  const { detail: source } = useGetDetail({
    api: sourceApi.getById,
    value: cv.source_id,
  });

  const { items: users } = useFetchUser();

  const getUser = username => {
    if (!username) return null;
    const user = users.find(item => item.username === username);
    if (user) return `${user.fullname} (${username})`;
    return username;
  };

  const reviewer = getUser(cv.reviewer_id);
  const interviewer = getUser(cv.interviewer_id);
  const assignee = getUser(cv.assignee_id);
  // End: get detail information

  const cvAfter = useMemo(() => preProcessCv(cv), [cv]);

  const InfoLine = useCallback(({ condition = true, label, content }) => {
    return (
      condition && (
        <div className="cv-info-line">
          <p>{label}</p>
          <div>{content}</div>
        </div>
      )
    );
  }, []);

  const handleOpenLastStep = () => {
    setCurrentStep(stepValue);
    setVisible(true);
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  };

  return (
    <Row>
      <Col xl={12} lg={10}>
        <div className="cv-left-wrapper">
          <Col xxl={11} xl={13} lg={18} className="cv-left">
            <div className="cv-avatar">
              <AvatarShow avatarList={cvAfter.images} onlyView />
            </div>
            {detailStep && (
              <Button className="cv-avatar-step" onClick={handleOpenLastStep}>
                <div className="avatar-step-wrapper">
                  <b style={{ color: detailStep?.stepColor }}>
                    {`${detailStep?.stepTitle} `}
                  </b>
                  <span className="divider">-</span>
                  <b
                    style={{ color: detailStep?.statusColor }}
                  >{` ${detailStep?.statusTitle}`}</b>
                  {totalHistoryEmail > 0 && (
                    <span className="email-icon">
                      <MailOutlined />
                    </span>
                  )}
                </div>
              </Button>
            )}
          </Col>
        </div>
      </Col>
      <Col xl={12} lg={14}>
        <div className="cv-right">
          {/* Link Cv */}
          <InfoLine
            label={t('cv.detail.CV')}
            content={
              cvAfter.linkcv ? (
                <a
                  href={cvAfter.linkcv}
                  target="_blank"
                  style={{ textDecoration: 'underline' }}
                >
                  {t('cv.detail.views')}
                </a>
              ) : (
                t('cvDetail.noCv')
              )
            }
          />
          {/* Source */}
          <InfoLine
            label={t('cvDetail.source')}
            content={source.title}
            condition={!!source.title}
          />
          {/* Checklist */}
          <InfoLine
            label={t('updateCv.checklist')}
            content={
              cvAfter.checklist ? (
                <a
                  href={cvAfter.checklist}
                  target="_blank"
                  style={{ textDecoration: 'underline' }}
                >
                  {t('cv.detail.views')}
                </a>
              ) : (
                t('cvDetail.noChecklist')
              )
            }
          />
          {/* fullName, gender, age */}
          <InfoLine
            label={t('cv.detail.info')}
            content={
              <div className="cv-basic-info">
                {/* FullName */}
                <span>{cvAfter.fullname}</span>
                {/* Gender */}
                {![null, undefined].includes(cvAfter.gender) && (
                  <span className="cv-line-gender">
                    <img
                      src={GENDER[cvAfter.gender].icon}
                      style={{ height: 20 }}
                      alt="gender"
                    />
                    <span style={{ marginLeft: 3 }}>
                      {t(GENDER[cvAfter.gender].text)}
                    </span>
                  </span>
                )}
                {/* Age */}
                {cvAfter.age > 0 && (
                  <span className="age-badge">{`${cvAfter.age} ${t(
                    'cvDetail.age',
                  )}`}</span>
                )}
              </div>
            }
          />
          {/* Position and department */}
          <InfoLine
            label={t('cv.detail.position')}
            condition={!!(position.title || department.title)}
            content={
              <Breadcrumb>
                <Breadcrumb.Item>
                  <img src={departmentImg} alt="department" />
                  <span>{position.title}</span>
                </Breadcrumb.Item>
                <Breadcrumb.Item style={{ color: 'rgba(0, 0, 0, 0.45)' }}>
                  <img src={positionImg} alt="position" />
                  <span>{department.title}</span>
                </Breadcrumb.Item>
              </Breadcrumb>
            }
          />
          {/* Level at first */}
          <InfoLine
            condition={!!level.title}
            label={t('cvDetail.firstLevel')}
            content={<div className="level-badge">{level.title}</div>}
          />
          {/* Level after interview */}
          <InfoLine
            condition={!!lastLevel.title}
            label={t('cvDetail.afterInterviewLevel')}
            content={
              <div className="level-badge" style={{ background: 'orange' }}>
                {lastLevel.title}
              </div>
            }
          />
          {/* Mobile */}
          <InfoLine
            condition={!!cvAfter.mobile}
            label={t('cvDetail.mobile')}
            content={
              <>
                <MobileOutlined style={{ marginRight: 5 }} />
                {cvAfter.mobile}
              </>
            }
          />
          {/* Email */}
          <InfoLine
            condition={!!cvAfter.email}
            label={t('cvDetail.email')}
            content={
              <>
                <MailOutlined style={{ marginRight: 5 }} />
                {cvAfter.email}
              </>
            }
          />
          {/* Address */}
          <InfoLine
            condition={!!cvAfter.address}
            label={t('cvDetail.address')}
            content={
              <>
                <HomeOutlined style={{ marginRight: 5 }} />
                {cvAfter.address}
              </>
            }
          />
          {/* Onboard Date */}
          <InfoLine
            condition={!!cvAfter.momentOnboard}
            label={t('cvDetail.onboard')}
            content={
              <>
                <CalendarOutlined style={{ marginRight: 5 }} />
                {cvAfter.momentOnboard?.format(DATE_FORMAT)}
              </>
            }
          />
          {/* Description */}
          <InfoLine
            condition={!!cvAfter.description}
            label={t('cv.detail.note')}
            content={cvAfter.description}
          />
          {/* Reviewer */}
          <InfoLine
            condition={!!reviewer}
            label={t('updateCv.reviewer')}
            content={reviewer}
          />
          {/* Interviewer */}
          <InfoLine
            condition={!!interviewer}
            label={t('updateCv.interviewer')}
            content={interviewer}
          />
          {/* Assignee */}
          <InfoLine
            condition={!!assignee}
            label={t('common.assignee')}
            content={assignee}
          />

          {/* Update button */}
          {permission.edit && (
            <EditBlueButton
              type="primary"
              className="btn-edit"
              onClick={() => navigate('/cv/' + id + '/update')}
            >
              {t('common.edit')}
            </EditBlueButton>
          )}
        </div>
      </Col>
    </Row>
  );
}
