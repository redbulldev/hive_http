import React, { forwardRef } from 'react';
import { Pie } from 'react-chartjs-2';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import { memo } from 'react';
import { Chart } from 'chart.js';

function PieChart({ labels, data }, ref) {
  Chart.defaults.font.family = 'Roboto, IBM Plex Sans, sans-serif';
  Chart.defaults.font.size = 14;
  const dataSource = {
    labels: labels,
    datasets: data.map(x => ({
      data: x.data,
      fill: true,
      backgroundColor: x.backgroundColor,
    })),
  };
  const optionsPie = {
    plugins: {
      dataLabel: true,
      tooltip: {
        titleFont: {
          size: '16px',
        },
      },

      legend: {
        display: true,
        position: 'right',
        onClick: e => e.stopPropagation(),
        labels: {
          boxWidth: 10,
          usePointStyle: true,
          pointStyle: 'circle',
          padding: 5,
        },
      },
    },
    elements: {
      line: {
        borderWidth: 3,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  };
  return (
    <div className="pie">
      <Pie
        ref={ref}
        data={dataSource}
        options={optionsPie}
        plugins={[ChartDataLabels]}
      />
    </div>
  );
}

export default memo(forwardRef(PieChart));
