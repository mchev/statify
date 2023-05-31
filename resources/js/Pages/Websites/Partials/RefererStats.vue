<script setup>

const props = defineProps({
  referers_visitors: Object,
  referers_views: Object,
})

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3 class="flex-grow">Referers</h3>
    <div class="flex gap-4 text-center">
      <p class="w-16">Visitors</p>
      <p class="w-16">Views</p>
    </div>
  </div>
  <ul>
    <li v-for="(count, index) in referers_views" :key="index" class="flex justify-between">
      <div>{{ index ? index : 'Undefined / Direct' }}</div>
      <div class="flex gap-4 text-center">
        <span class="w-16">{{ referers_visitors[index] }}</span>
        <span class="w-16">{{ count }}</span>
      </div>
    </li>
  </ul>
</template>
