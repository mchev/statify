<script setup>
import Card from "@/Components/Card.vue"
import { format } from "date-fns";

const props = defineProps({
  summary: Object,
})

</script>
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <Card>
      <span class="text-2xl font-semibold">{{ summary.visitors }}</span>
      <p>Visitors</p>
    </Card>
    <Card>
      <span class="text-2xl font-semibold">{{ summary.views }}</span>
      <p>Views</p>
    </Card>
    <Card>
      <span class="text-2xl font-semibold">{{ format((summary.average_time * 1000), 'mm:ss') }}</span>
      <p>Avg time on site</p>
    </Card>
    <Card>
      <span class="text-2xl font-semibold">{{ summary.engagement_rate }}%</span>
      <p>Engagement rate</p>
    </Card>
  </div>
</template>
