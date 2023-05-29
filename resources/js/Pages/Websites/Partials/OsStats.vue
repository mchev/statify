<script setup>

const props = defineProps({
  os: Object,
})

</script>
<template>
  <ul>
    <li v-for="(count, index) in os" :key="index" class="flex justify-between">
      <div>{{ index }}</div>
      {{ count }}
    </li>
  </ul>
</template>
