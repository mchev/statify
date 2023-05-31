<script setup>

const props = defineProps({
  os: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Operating systems</h3>
    <p>Visitors</p>
  </div>
  <ul>
    <li v-for="(count, index) in os" :key="index" class="flex justify-between">
      <div>{{ index }}</div>
      {{ count }}
    </li>
  </ul>
</template>
