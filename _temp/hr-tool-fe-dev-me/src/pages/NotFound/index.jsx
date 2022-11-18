import { Button, Result } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';

function NotFound() {
  const { t } = useTranslation();

  return (
    <div>
      <Result
        status="404"
        title="404"
        subTitle={t('notfound.subTitle')}
        extra={
          <Link to="/">
            <Button type="primary">{t('notfound.buttonText')}</Button>
          </Link>
        }
      />
    </div>
  );
}

export default NotFound;
