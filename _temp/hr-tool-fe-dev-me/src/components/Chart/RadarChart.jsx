import React from 'react';
import { memo } from 'react';
import { Radar } from 'react-chartjs-2';
import { Chart } from 'chart.js';
import { forwardRef } from 'react';

function RadarChart({ labels, data }, ref) {
  Chart.defaults.font.family = 'Roboto, IBM Plex Sans, sans-serif';

  const dataSource = {
    labels: labels,
    datasets: data.map(x => ({
      data: x,
      fill: true,
      backgroundColor: 'rgba(0, 180, 216, 0.25)',
      borderColor: '#00B4D8',
      borderWidth: 1,
    })),
  };
  const options = {
    plugins: {
      tooltip: {
        titleFont: {
          size: '16px',
        },
      },
      legend: {
        display: false,
      },
    },
    elements: {
      line: {
        borderWidth: 3,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      r: {
        min: 0,
        pointLabels: {
          font: {
            size: 14,
          },
        },
      },
    },
  };
  return (
    <div className="radar">
      <Radar ref={ref} data={dataSource} options={options} />
    </div>
  );
}

export default memo(forwardRef(RadarChart));
