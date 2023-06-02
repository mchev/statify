<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue'
import CreateWebsiteForm from '@/Pages/Websites/Partials/CreateWebsiteForm.vue';

defineProps({
    website: Object,
    script: String,
})



</script>

<template>
    <AppLayout title="Create Team">
        <template #header>
            <div class="flex flex-wrap items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ website.name }} settings
                </h2>
                <Link :href="route('websites.show', website)" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to statistics
                </Link>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="flex relative gap-8">
                    <div class="flex flex-col border-dashed border-2 rounded border-gray-500 left-0 top-0 p-4">
                        Sidebars
                    </div>
                    <div class="flex flex-grow">
                        <Card>
                            <h3 class="leading-4 font-semibold">Script</h3>
                            <p class="mb-4">Paste this script between the head balise on your site</p>
                            <pre class="bg-gray-600 text-gray-200 font-mono rounded p-4 text-sm">{{ script }}</pre>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
