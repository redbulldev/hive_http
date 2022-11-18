import { CategoryScale } from 'chart.js';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import React, { isValidElement, memo } from 'react';
import { useExportImg } from '../hooks/index';
import ChartLayout from './ChartLayout';
Chart.register(ChartDataLabels);
Chart.register(CategoryScale);

function ChartWithExport({ children, chartName = '', ...props }) {
  const { ref, onExport } = useExportImg(chartName + '.png');

  const clonedChild =
    isValidElement(children) &&
    React.cloneElement(children, {
      ref,
    });
  return (
    <ChartLayout onExport={onExport} chartTitle={chartName} {...props}>
      {clonedChild}
    </ChartLayout>
  );
}

export default memo(ChartWithExport);
