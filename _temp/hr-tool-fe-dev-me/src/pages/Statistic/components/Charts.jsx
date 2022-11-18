import { Col, Row, Table } from 'antd';
import React, { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import BarChart from '../../../components/Chart/BarChart';
import PieChart from '../../../components/Chart/PieChart';
import RadarChart from '../../../components/Chart/RadarChart';
import { LABELSRADAR } from '../../../constants';
import ChartLayout from './ChartLayout';
import ChartWithExport from './ChartWithExport';
import SmallTable from './SmallTable';

function Charts({ data }) {
  const { t } = useTranslation();
  const summary = data?.summary;
  const department = data?.department ?? {};
  const { labels, values, colors } = department;

  const barChartProps = useMemo(() => {
    return {
      labels: summary?.labels?.split(','),
      data: [
        {
          label: t('statistic.request'),
          backgroundColor: '#FF7D8F',
          data: summary?.list_target?.split(','),
        },
        {
          label: t('statistic.onboard'),
          backgroundColor: '#00B4D8',
          data: summary?.list_onboard?.split(','),
        },
      ],
    };
  }, [summary, t]);

  const radarChartData = useMemo(
    () => [
      [
        summary?.total_cv,
        summary?.interview_cv,
        summary?.pass_cv,
        summary?.onboard_cv,
      ],
    ],
    [summary],
  );
  // const pieChartData = useMemo(
  //   () => [
  //     {
  //       data: values,
  //       fill: true,
  //       backgroundColor: colors,
  //     },
  //   ],
  //   [values, colors],
  // );

  return (
    <Row gutter={[32, 64]} align="stretch" style={{ marginBottom: 32 }}>
      <Col xs={24} sm={24} lg={12}>
        <ChartWithExport
          chartName="request_recruitment_success"
          height={472 * 2 + 24}
        >
          <BarChart {...barChartProps} />
        </ChartWithExport>
      </Col>
      <Col xs={24} sm={24} lg={12}>
        <Row gutter={32} style={{ rowGap: 24 }}>
          <Col xs={24} sm={24} lg={24}>
            <ChartWithExport chartName="recruitment_rate">
              <RadarChart labels={LABELSRADAR} data={radarChartData} />
            </ChartWithExport>
          </Col>
          <Col xs={24} sm={24} lg={24}>
            <ChartLayout chartTitle="required_number_personnel_ranking">
              <SmallTable labels={labels} values={values} maxHeight={300} />
            </ChartLayout>
          </Col>
        </Row>
      </Col>
    </Row>
  );
}

export default Charts;
