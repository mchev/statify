<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateWebsiteForm from '@/Pages/Websites/Partials/CreateWebsiteForm.vue';

defineProps({
    website: Object,
    script: String,
})



</script>

<template>
    <AppLayout title="Create Team">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ website.name }} settings
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="flex relative">
                    <div class="absolute flex flex-col bg-white left-0 top-0 px-12">
                        Sidebars
                    </div>
                    <div class="flex flex-grow">
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
