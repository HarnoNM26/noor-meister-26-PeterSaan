<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref, watchEffect } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const startDate = ref<Date|null>(null);
const endDate = ref<Date|null>(null);
const filterLocation = ref('ee');

async function fetchReadings() {
    const fetchUrl = `http://localhost:8000/api/readings?start=${startDate.value}&end=${endDate.value}&location=${filterLocation.value}`;
    const res = await fetch(fetchUrl);
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex max-h-screen flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div
                class="mx-auto mb-40 flex rounded-xl border border-sidebar-border/70 px-10 py-6 md:min-h-min dark:border-sidebar-border"
            >
                <div class="flex items-start justify-center">Filter data</div>
                <form></form>
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
