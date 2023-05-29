<script setup>

const props = defineProps({
  pages: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Pages</h3>
    <p>Views</p>
  </div>
  <ul>
    <li v-for="(count, index) in pages" :key="index" class="flex justify-between">
      <div>{{ index ? index : 'Undefined' }}</div>
      {{ count }}
    </li>
  </ul>
</template>
