<script setup>

const props = defineProps({
  countries: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Countries</h3>
    <p>Visitors</p>
  </div>
  <ul>
    <li v-for="(count, index) in countries" :key="index" class="flex justify-between">
      <div>{{ index ? index : 'Undefined' }}</div>
      {{ count }}
    </li>
  </ul>
</template>
