<script setup>
import { ref, watch } from 'vue'
import TextInput from "@/Components/TextInput.vue"

const props = defineProps({
  referers_visitors: Object,
  referers_views: Object,
})

const search = ref('')
const filtered = ref(props.referers_views)
const total = Object.values(props.referers_views).reduce((a, b) => a + b, 0);

watch(
  search,
  (value) => {
    filtered.value = Object.fromEntries(Object.entries(props.referers_views).filter(([key]) => key.includes(value)));
  }
)

</script>
<template>
  <div class="flex items-center justify-between mb-4 font-semibold pr-5 gap-4">
    <h3>Referers</h3>
    <TextInput v-model="search" placeholder="Search" class="p-2" />
    <div class="flex gap-4 text-center">
      <p class="w-16">Visitors</p>
      <p class="w-16">Views</p>
    </div>
  </div>
  <ul class="h-80 overflow-y-auto pr-4">
    <li v-for="(count, index) in filtered" :key="index" class="flex justify-between">
      <div class="flex py-2 my-1 w-full relative text-sm">
        <div class="absolute block top-0 bottom-0 z-0 bg-teal-900 bg-opacity-50 rounded-r" :style="`width:${count / total * 100}%`"/>
        <div class="relative z-10 pl-4">{{ index ? index : 'Undefined / Direct' }}</div>
      </div>
      <div class="flex gap-4 text-center">
        <span class="w-16">{{ referers_visitors[index] ?? 0 }}</span>
        <span class="w-16">{{ count }}</span>
      </div>
    </li>
  </ul>
</template>

