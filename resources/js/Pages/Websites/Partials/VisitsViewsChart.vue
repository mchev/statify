<script setup>
import { Line } from "vue-chartjs";
import { parse } from "date-fns";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  dates: Object,
  visitors: Object,
  views: Object
})

const chartData = {
  labels: props.dates,
  datasets: [
    {
      label: 'Unique visitors',
      data: props.visitors,
      borderColor: 'rgba(149, 128, 255, .4)',
      backgroundColor: 'rgba(149, 128, 255, .4)',
      fill: true,
      tension: 0.1
    },
    {
      label: 'Page views',
      data: props.views,
      borderColor: 'rgba(149, 128, 255, .2)',
      backgroundColor: 'rgba(149, 128, 255, .2)',
      fill: true,
      tension: 0.1
    },
  ],
};

const chartOptions = { 
  responsive: true,
  interaction: {
    mode: 'index'
  },
  elements: {
    point: {
      radius: 0,
    }
  },
  plugins: {
    legend: {
      position: 'top',
      labels: {
        font: {
          size: 14,
        }
      }
    },
    tooltip: {
      intersect: false,
    }
  },
  scales: {
    y: {
      min: 0,
    }
  }
};
</script>
<template>
  <Line id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>
