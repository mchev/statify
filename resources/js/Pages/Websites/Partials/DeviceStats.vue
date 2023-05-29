<script setup>

const props = defineProps({
  devices: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Devices</h3>
    <p>Visitors</p>
  </div>
  <ul>
    <li v-for="(count, index) in devices" :key="index" class="flex justify-between">
      <div>{{ index }}</div>
      {{ count }}
    </li>
  </ul>
</template>
