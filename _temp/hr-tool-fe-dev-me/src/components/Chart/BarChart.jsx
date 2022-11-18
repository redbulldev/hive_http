import { forwardRef, memo } from 'react';
import { Bar } from 'react-chartjs-2';
import { Chart } from 'chart.js';

import ChartDataLabels from 'chartjs-plugin-datalabels';
function BarChart(props, ref) {
  Chart.register(ChartDataLabels);
  Chart.defaults.font.family = 'Roboto, IBM Plex Sans, sans-serif';
  Chart.defaults.font.size = 14;
  const { data, labels } = props;
  const dataSource = {
    labels: labels?.map(x => x.split(' ')),
    datasets: data.map(x => {
      return {
        label: x.label,
        backgroundColor: x.backgroundColor,
        data: x.data,
        borderRadius: 3,
        maxBarThickness: 30,
        minBarLength: 3,
      };
    }),
  };
  let delayed;
  const options = {
    animation: {
      onComplete: () => {
        delayed = true;
      },
      delay: context => {
        let delay = 0;
        if (context.type === 'data' && context.mode === 'default' && !delayed) {
          delay = context.dataIndex * 300 + context.datasetIndex * 100;
        }
        return delay;
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        grid: {
          display: false,
          drawBorder: false,
        },
        grace: 5,
        offset: true,
        display: false,
      },
      y: {
        grid: {
          display: false,
          drawBorder: false,
        },
      },
    },

    indexAxis: 'y',

    plugins: {
      datalabels: {
        anchor: 'start', // remove this line to get label in middle of the bar
        align: 'start',
      },
      legend: {
        display: true,
        position: 'top',

        onClick: () => null,
        labels: {
          boxWidth: 15,
          usePointStyle: true,
          pointStyle: 'circle',
          padding: 15,
        },
      },

      tooltip: {
        titleFont: { size: '16px' },
        callbacks: {
          title: tooltipItems => tooltipItems[0].label.replace(/,/g, ' '),
        },
      },
    },
  };
  return (
    <div className="bar">
      <Bar ref={ref} data={dataSource} options={options} />
    </div>
  );
}

export default memo(forwardRef(BarChart));
