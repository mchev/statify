<script setup>

const props = defineProps({
  languages: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Languages</h3>
    <p>Visitors</p>
  </div>
  <ul>
    <li v-for="(count, index) in languages" :key="index" class="flex justify-between">
      <div>{{ index }}</div>
      {{ count }}
    </li>
  </ul>
</template>
