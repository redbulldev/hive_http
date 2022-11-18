import { CloseCircleFilled } from '@ant-design/icons';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch } from 'react-redux';
import { setMessage } from '../../pages/CvManagerment/reducer/hrReview';

function Message() {
  const { t } = useTranslation();

  const dispatch = useDispatch();
  useEffect(() => {
    const timer = setTimeout(() => {
      dispatch(setMessage());
    }, 1000);
    return () => {
      clearTimeout(timer);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return (
    <div className="message">
      <CloseCircleFilled style={{ fontSize: '16px', color: '#F94144' }} />
      <p>{t('language.networkError')}</p>
    </div>
  );
}

export default Message;
