<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  value: {
    type: Number,
    required: true,
  },
});

const formattedValue = ref(props.value);

const formatNumber = (num) => {
  if (num < 1000) {
    formattedValue.value = num.toString();
  } else if (num < 1000000) {
    formattedValue.value = (num / 1000).toFixed(1) + "k";
  } else if (num < 1000000000) {
    formattedValue.value = (num / 1000000).toFixed(1) + "M";
  } else {
    formattedValue.value = (num / 1000000000).toFixed(1) + "B";
  }
};

watch(
  () => props.value,
  (newValue) => {
    formattedValue.value = formatNumber(newValue);
  }
);
</script>
<template>
  <div :title="value">
    {{ formattedValue }}
  </div>
</template>
