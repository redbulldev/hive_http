import { Col, Form, Row } from 'antd';
import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';
import withStepFrame from './withStepFrame';
import HRReviewApi from '../../../api/HRReviewApi';
import { requiredFields } from '../../../constants/preOffer';
import { LevelSelect } from '../../../components/Form';
import PreOfferCV from '../../../api/preOffer';
import cvApi from '../../../api/cvApi';
import { useParams } from 'react-router-dom';

function PreOffer(props) {
  const {
    form,
    Frame,
    FormInput,
    cv,
    FormTextArea,
    Reason,
    Status,
    setConfig,
    step,
    isFailed,
    status,
  } = props;
  const { t } = useTranslation();
  const { id } = useParams();

  const [showChecklist, setShowChecklist] = useState(false);

  const checkList = cv.checklist;

  useEffect(() => {
    setConfig({
      api: PreOfferCV.getById,
      submitApi: PreOfferCV.post,
      requiredFields: requiredFields,
      fillData: values => {
        form.setFieldsValue({
          checklist: checkList || undefined,
        });
        return {};
      },
      submitObject: values => {
        return values;
      },
      afterSubmit: async values => {
        const checklist_ = values?.checklist ? values?.checklist.trim() : null;
        if (showChecklist && checkList !== checklist_) {
          const data = { checklist: checklist_ };
          await cvApi.edit(id, data);
        }
      },
    });
  }, [showChecklist]);

  const editChecklist = e => {
    e.preventDefault();
    setShowChecklist(!showChecklist);
  };

  return (
    <Frame>
      <Row gutter={20}>
        <Col span={12}>
          {/*Begin: Checklist */}
          <div style={{ display: 'flex', marginBottom: '10px' }}>
            <p
              style={{
                fontSize: '14px',
                marginRight: '22px',
                marginBottom: '0',
              }}
            >
              {t('preoffer.checkList')}
            </p>
            {checkList ? (
              <div className="preoffer-checklist">
                <a style={{ margin: 0 }} target="/#" href={checkList}>
                  {t('preoffer.show')}
                </a>
                <a style={{ marginLeft: 15 }} href="/#" onClick={editChecklist}>
                  {showChecklist
                    ? t('preoffer.cancelEdit')
                    : t('preoffer.edit')}
                </a>
              </div>
            ) : (
              <p>{t('preoffer.noUpdate')}</p>
            )}
          </div>

          <Form.Item shouldUpdate noStyle>
            {() => {
              return (
                showChecklist && (
                  <FormInput
                    name="checklist"
                    placeholder={t('updateCv.checklistPlaceholder')}
                    label={t('updateCv.checklist')}
                  />
                )
              );
            }}
          </Form.Item>
          {/*End: Checklist */}
          <LevelSelect
            name="level_id"
            label={t('level.title')}
            disabled={isFailed}
            required
          />
          <FormTextArea name="content" label={t('preoffer.content')} rows={8} />
        </Col>
        <Col span={12}>
          <FormTextArea name="notes" label={t('review.review')} />
          <Status step={step} status={status} />
          <Reason />
        </Col>
      </Row>
    </Frame>
  );
}

export default withStepFrame(PreOffer);
