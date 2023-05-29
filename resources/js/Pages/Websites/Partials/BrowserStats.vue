<script setup>

const props = defineProps({
  browsers: Object,
})

</script>
<template>
  <ul>
    <li v-for="(count, index) in browsers" :key="index" class="flex justify-between">
      <div>{{ index }}</div>
      {{ count }}
    </li>
  </ul>
</template>
