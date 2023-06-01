<script setup>
import Card from "@/Components/Card.vue"
import FormattedNumber from "@/Components/FormattedNumber.vue"
import { format } from "date-fns";

const props = defineProps({
  summary: Object,
})

</script>
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <Card>
      <FormattedNumber :value="summary.visitors.total" class="text-2xl font-semibold"/>
      <div class="flex items-end gap-2">
        <p>Visitors</p>
        <span class="font-semibold text-sm flex" :class="summary.visitors.diff > 0 ? 'text-teal-500' : 'text-red-400'">
          <span v-if="summary.visitors.diff > 0">+</span><FormattedNumber :value="summary.visitors.diff"/>
        </span>
      </div>
    </Card>
    <Card>
      <FormattedNumber :value="summary.views.total" class="text-2xl font-semibold"/>
      <div class="flex items-end gap-2">
        <p>Views</p>
        <span class="font-semibold text-sm flex" :class="summary.views.diff > 0 ? 'text-teal-500' : 'text-red-400'">
          <span v-if="summary.views.diff > 0">+</span><FormattedNumber :value="summary.views.diff"/>
        </span>
      </div>
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
