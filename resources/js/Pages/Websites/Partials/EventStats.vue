<script setup>
import { Bar } from "vue-chartjs";
import { parse } from "date-fns";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  dates: Object,
  events: Object,
})

const chartData = {
  labels: props.dates,
  datasets: props.events,
};

const chartOptions = { 
  responsive: true,
  maintainAspectRatio: false,
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
      position: 'left',
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
    x: {
      stacked: true
    },
    y: {
      min: 0,
      stacked: true
    }
  }
};
</script>
<template>
  <Bar id="events-chart" :options="chartOptions" :data="chartData" />
</template>
