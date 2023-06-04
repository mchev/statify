<script setup>
import { watch, ref, onMounted } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import Card from "@/Components/Card.vue";
import CreateWebsiteForm from "@/Pages/Websites/Partials/CreateWebsiteForm.vue";
import StatSummary from "@/Pages/Websites/Partials/StatSummary.vue";
import VisitsViewsChart from "@/Pages/Websites/Partials/VisitsViewsChart.vue";
import PageStats from "@/Pages/Websites/Partials/PageStats.vue";
import RefererStats from "@/Pages/Websites/Partials/RefererStats.vue";
import StatModule from "@/Pages/Websites/Partials/StatModule.vue";
import EventStats from "@/Pages/Websites/Partials/EventStats.vue";
import pickBy from "lodash/pickBy";
import throttle from "lodash/throttle";
import {
    endOfMonth,
    endOfYear,
    startOfMonth,
    startOfYear,
    subMonths,
    addDays,
} from "date-fns";

import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
    website: Object,
    dates: Object,
    stats: Object,
    filters: Object,
});

const form = useForm({
    range: props.filters.range,
    search: props.filters.search,
});

const presetRanges = ref([
    { label: "Today", range: [new Date(), new Date()] },
    {
        label: "This month",
        range: [startOfMonth(new Date()), endOfMonth(new Date())],
    },
    {
        label: "Last month",
        range: [
            startOfMonth(subMonths(new Date(), 1)),
            endOfMonth(subMonths(new Date(), 1)),
        ],
    },
    {
        label: "This year",
        range: [startOfYear(new Date()), endOfYear(new Date())],
    },
    {
        label: "This year (slot)",
        range: [startOfYear(new Date()), endOfYear(new Date())],
        slot: "yearly",
    },
]);

watch(
    () => form.range,
    throttle(() => {
        console.log(form.range)
        form.get(route("websites.show", props.website), pickBy(form), {
            preserveState: false,
        });
    }, 150),
    { deep: true }
);
</script>

<template>
    <AppLayout :title="website.name">
        <template #header>
            <div
                class="flex flex-wrap items-center gap-2 justify-between text-gray-800 dark:text-gray-200"
            >
                <div class="flex flex-col gap-1">
                    <h2
                        class="font-semibold text-xl leading flex items-center gap-3"
                    >
                        <div class="flex h-3 w-3 rounded-full bg-teal-500" />
                        <a
                            class="flex gap-1 items-center"
                            :href="`//${website.domain}`"
                            target="_blank"
                            :title="`Visit ${website.domain}`"
                        >
                            {{ website.domain }}
                        </a>
                    </h2>
                </div>
                <div class="flex items-center gap-2 md:w-1/2">
                    <VueDatePicker
                        v-model="form.range"
                        locale="fr"
                        :dark="true"
                        :enable-time-picker="false"
                        :ignore-time-validation="true"
                        :max-date="new Date()"
                        range
                        multi-calendars
                        auto-apply 
                        :preset-ranges="presetRanges"
                    >
                        <template #yearly="{ label, range, presetDateRange }">
                            <span @click="presetDateRange(range)">{{
                                label
                            }}</span>
                        </template>
                    </VueDatePicker>

                    <Link :href="route('websites.edit', website)">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <title>Settings</title>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </Link>
                </div>
            </div>
        </template>

        <div>
            <div
                class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 dark:text-gray-200"
            >
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <StatSummary :summary="stats.summary"/>
                    <Card class="h-96">
                        <VisitsViewsChart
                            :dates="dates"
                            :visitors="stats.visitors"
                            :views="stats.views"
                        />
                    </Card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <Card>
                        <PageStats :pages="stats.pages" />
                    </Card>
                    <Card>
                        <RefererStats
                            :referers_visitors="stats.referers_visitors"
                            :referers_views="stats.referers_views"
                        />
                    </Card>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4"
                >
                    <Card>
                        <StatModule title="Devices" :data="stats.devices" />
                    </Card>
                    <Card>
                        <StatModule title="Browsers" :data="stats.browsers" />
                    </Card>
                    <Card>
                        <StatModule title="Operating systems" :data="stats.os" />
                    </Card>
                    <Card>
                        <StatModule title="Languages" :data="stats.languages" />
                    </Card>
                    <Card>
                        <StatModule title="Countries" :data="stats.countries" />
                    </Card>
                    <Card>
                        <StatModule title="Cities" :data="stats.cities" />
                    </Card>
                </div>

                <div class="grid grid-cols-1 gap-4 mb-4">
                    <Card class="h-96">
                        <EventStats
                            :dates="dates"
                            :events="stats.events"
                        />
                    </Card>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
