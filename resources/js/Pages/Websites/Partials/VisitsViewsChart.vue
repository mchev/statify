<script setup>
import { Line } from "vue-chartjs";
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
      borderColor: 'purple',
      fill: false,
    },
    {
      label: 'Page views',
      data: props.views,
      backgroundColor: '#333',
      fill: true,
      tension: 0.1
    },
  ],
};

const chartOptions = { 
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
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
