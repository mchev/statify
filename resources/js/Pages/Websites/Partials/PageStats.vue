<script setup>

const props = defineProps({
  pages: Object,
})

const total = Object.values(props.pages).reduce((a, b) => a + b, 0);

</script>
<template>
  <div class="flex justify-between mb-4 font-semibold">
    <h3>Pages</h3>
    <p>Views</p>
  </div>
  <ul>
    <li v-for="(count, index) in pages" :key="index" class="flex justify-between">
      <div class="flex py-2 my-1 w-full relative text-sm">
        <div class="absolute block top-0 bottom-0 z-0 bg-teal-800 rounded-r-sm" :style="`width:${count / total * 100}%`"/>
        <div class="relative z-10 pl-4">{{ index ? index : 'Undefined' }}</div>
      </div>
      <div class="w-20 text-right">{{ count }}</div>
    </li>
  </ul>
</template>
