<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import FormSection from "@/Components/FormSection.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import UpdateWebsiteInformationForm from "@/Pages/Websites/Partials/UpdateWebsiteInformationForm.vue";
import CreateWebsiteForm from "@/Pages/Websites/Partials/CreateWebsiteForm.vue";

defineProps({
    website: Object,
    script: String,
});
</script>

<template>
    <AppLayout title="Create Team">
        <template #header>
            <div class="flex flex-wrap items-center justify-between">
                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                >
                    {{ website.name }} import
                </h2>
                <Link
                    :href="route('websites.edit', website)"
                    class="flex items-center gap-2"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                        />
                    </svg>
                    Back to settings
                </Link>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-2 md:gap-10">

                </div>
            </div>
        </div>
    </AppLayout>
</template>
