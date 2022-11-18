import { Col, Row } from 'antd';
import { useMemo } from 'react';
import { memo } from 'react';
import { useTranslation } from 'react-i18next';
import DashboardCard from '../../../components/Dashboard/Card';
function Summary({ summary, isCv = false }) {
  const { target, total_cv, onboard_cv } = summary;
  const { t } = useTranslation();

  const dataCard = useMemo(() => {
    const data = [
      {
        title: target ?? '',
        desc: t('statistic.positon_recruit'),
        subDesc: t('statistic.position'),
      },
      {
        title: total_cv ?? '',
        desc: t('statistic.application_letter'),
        subDesc: t('statistic.letter'),
      },
      {
        title: onboard_cv ?? '',
        desc: t('statistic.applicants'),
        subDesc: t('statistic.candidate'),
      },
      {
        title:
          onboard_cv && target
            ? `${Math.round((onboard_cv / target) * 100)}%`
            : '',
        desc: t('statistic.ratio_success'),
        subDesc: t('statistic.percent'),
      },
    ];
    if (isCv) {
      data.push({
        title: onboard_cv,
        desc: t('statistic.total'),
        subDesc: t('statistic.point'),
      });
    }
    return data;
  }, [isCv, summary]);

  return (
    <div className="summary">
      {dataCard.map((cardProps, id) => (
        <DashboardCard {...cardProps} key={id} />
      ))}
    </div>
  );
}

export default memo(Summary);
