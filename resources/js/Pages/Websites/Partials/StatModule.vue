<script setup>

const props = defineProps({
  title: Object,
  data: Object,
})

const total = Object.values(props.data).reduce((a, b) => a + b, 0);

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>{{ title }}</h3>
    <p>Visitors</p>
  </div>
  <ul v-if="total > 0" class="max-h-80 overflow-y-auto pr-4">
    <li v-for="(count, index) in data" :key="index" class="flex justify-between">
      <div class="flex py-2 my-1 w-full relative text-sm">
        <div class="absolute block top-0 bottom-0 z-0 bg-teal-800 rounded-r-sm" :style="`width:${count / total * 100}%`"/>
        <div class="relative z-10 pl-4">{{ index ? index : 'Undefined' }}</div>
      </div>
      <div class="w-20 text-right">{{ count }}</div>
    </li>
  </ul>
  <div v-else>
    <p>No data</p>
  </div>
</template>
