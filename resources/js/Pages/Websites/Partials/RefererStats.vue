<script setup>

const props = defineProps({
  referers: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Referers</h3>
    <p>Views</p>
  </div>
  <ul>
    <li v-for="(count, index) in referers" :key="index" class="flex justify-between">
      <div>{{ index ? index : 'Undefined / Direct' }}</div>
      {{ count }}
    </li>
  </ul>
</template>
