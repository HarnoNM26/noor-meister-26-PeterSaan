<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const startDate = ref<Date | null>(null);
const endDate = ref<Date | null>(null);
const filterLocation = ref('ee');
const loading = ref(false);
const readingsResponse = ref({});

async function fetchReadings() {
    loading.value = true;
    const fetchUrl = `http://localhost:8000/api/readings?start=${startDate.value}&end=${endDate.value}&location=${filterLocation.value}`;
    const res = await fetch(fetchUrl);

    readingsResponse.value = await res.json();
    loading.value = false;
    console.log(readingsResponse.value);
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex max-h-screen flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div
                class="mx-auto mb-40 flex flex-col rounded-xl border border-sidebar-border/70 px-10 py-6 md:min-h-min dark:border-sidebar-border"
            >
                <div class="flex items-start justify-center pb-5 font-semibold">
                    Filter data
                </div>
                <form class="flex flex-col">
                    <div class="mb-5 flex flex-col">
                        <label class="text-sm" for="start">Start date:</label>
                        <input
                            id="start"
                            type="datetime-local"
                            name="startDate"
                            v-model="startDate"
                        />
                    </div>
                    <div class="mb-5 flex flex-col">
                        <label class="text-sm" for="end">End date:</label>
                        <input
                            id="end"
                            type="datetime-local"
                            name="endDate"
                            v-model="endDate"
                        />
                    </div>
                    <div class="mb-8 flex">
                        <input
                            id="ee"
                            value="ee"
                            type="radio"
                            name="location"
                            v-model="filterLocation"
                            checked
                        />
                        <label class="me-3" for="ee">EE</label>
                        <input
                            id="lv"
                            value="lv"
                            type="radio"
                            name="location"
                            v-model="filterLocation"
                        />
                        <label class="me-3" for="lv">LV</label>
                        <input
                            id="fi"
                            value="fi"
                            type="radio"
                            name="location"
                            v-model="filterLocation"
                        />
                        <label class="me-3" for="fi">FI</label>
                    </div>
                    <div class="flex justify-center">
                        <button
                            class="cursor-pointer rounded-full border border-white px-2 py-1 text-center hover:border-transparent hover:bg-white hover:text-black disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-[#0a0a0a]"
                            type="submit"
                            @click="fetchReadings"
                            :disabled="loading"
                        >
                            {{ loading ? 'Filtering...' : 'Visualize data' }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="grid h-full auto-rows-min gap-4 md:grid-cols-4">
                <div
                    class="overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                >
                    <div class="flex justify-center">Price over time</div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                >
                    <div class="flex justify-center">
                        Daily avg price from {{ startDate }} to {{ endDate }}
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                >
                    <div class="flex justify-center">
                        Avg price in {{ filterLocation }}
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border"
                >
                    <div class="flex justify-center">Prices per location</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
