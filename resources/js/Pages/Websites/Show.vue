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
import DeviceStats from "@/Pages/Websites/Partials/DeviceStats.vue";
import BrowserStats from "@/Pages/Websites/Partials/BrowserStats.vue";
import OsStats from "@/Pages/Websites/Partials/OsStats.vue";
import LanguageStats from "@/Pages/Websites/Partials/LanguageStats.vue";
import CountryStats from "@/Pages/Websites/Partials/CountryStats.vue";
import CityStats from "@/Pages/Websites/Partials/CityStats.vue";
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
    <AppLayout title="Create Team">
        <template #header>
            <div
                class="flex flex-wrap items-center gap-2 justify-between text-gray-800 dark:text-gray-200"
            >
                <div class="flex flex-col gap-1">
                    <h2
                        class="font-semibold text-xl leading flex items-center gap-3"
                    >
                        <div class="flex h-3 w-3 rounded-full bg-teal-500" />
                        {{ website.name }}
                    </h2>
                    <div class="flex flex-wrap gap-4 items-center">
                        <a
                            class="flex gap-1 items-center"
                            :href="`//${website.domain}`"
                            target="_blank"
                            :title="`Visit ${website.domain}`"
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
                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"
                                />
                            </svg>
                            {{ website.domain }}
                        </a>
                        <a
                            class="flex gap-1 items-center"
                            :href="`//${website.domain}`"
                            target="_blank"
                            :title="`Visit ${website.domain}`"
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
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                />
                            </svg>
                            <span class="font-semibold">Team</span>
                            {{ website.team.name }}
                        </a>
                        <a
                            class="flex gap-1 items-center"
                            :href="`//${website.domain}`"
                            target="_blank"
                            :title="`Visit ${website.domain}`"
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
                                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"
                                />
                            </svg>
                            <span class="font-semibold">Site ID</span>
                            {{ website.id }}
                        </a>
                    </div>
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
                    <Card>
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
                        <DeviceStats :devices="stats.devices" />
                    </Card>
                    <Card>
                        <BrowserStats :browsers="stats.browsers" />
                    </Card>
                    <Card>
                        <OsStats :os="stats.os" />
                    </Card>
                    <Card>
                        <LanguageStats :languages="stats.languages" />
                    </Card>
                    <Card>
                        <CountryStats :countries="stats.countries" />
                    </Card>
                    <Card>
                        <CityStats :cities="stats.cities" />
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
