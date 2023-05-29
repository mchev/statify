<script setup>

const props = defineProps({
  cities: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Cities</h3>
    <p>Visitors</p>
  </div>
  <ul>
    <li v-for="(count, index) in cities" :key="index" class="flex justify-between">
      <div>{{ index ? index : 'Undefined' }}</div>
      {{ count }}
    </li>
  </ul>
</template>
