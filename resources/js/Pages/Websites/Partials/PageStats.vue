<script setup>
import { ref, watch } from 'vue'
import TextInput from "@/Components/TextInput.vue"

const props = defineProps({
  pages: Object,
})

const search = ref('')
const filteredPages = ref(props.pages)
const total = Object.values(props.pages).reduce((a, b) => a + b, 0);

watch(
  search,
  (value) => {
    filteredPages.value = Object.fromEntries(Object.entries(props.pages).filter(([key]) => key.includes(value)));
  }
)

</script>
<template>
  <div class="flex items-center justify-between mb-4 font-semibold pr-5 gap-4">
    <h3>Pages</h3>
    <TextInput v-model="search" placeholder="Search" class="p-2" />
    <p>Views</p>
  </div>
  <ul class="h-80 overflow-y-auto pr-4">
    <li v-for="(count, index) in filteredPages" :key="index" class="flex justify-between">
      <div class="flex py-2 my-1 w-full relative text-sm">
        <div class="absolute block top-0 bottom-0 z-0 bg-teal-900 bg-opacity-50 rounded-r" :style="`width:${count / total * 100}%`"/>
        <div class="relative z-10 pl-4">{{ index ? index : 'Undefined' }}</div>
      </div>
      <div class="w-20 text-right">{{ count }}</div>
    </li>
  </ul>
</template>
