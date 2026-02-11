<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import { ref, watchEffect } from 'vue';
import SyncPrices from '@/components/SyncPrices.vue';

const backendOk = ref(false);
const dbOk = ref(false);

watchEffect(async () => {
    const res = await fetch('http://localhost:8000/api/health');
    const resJson = await res.json();

    backendOk.value = resJson.status === 'ok';
    dbOk.value = resJson.db === 'ok';
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div
            v-if="backendOk === false"
            class="justify-center text-white"
        >
            Problem with backend
        </div>
        <div
            v-if="dbOk === false"
            class="justify-center text-white"
        >
            Problem with db
        </div>
        <div v-else class=" justify-center text-white">
            Backend OK
        </div>
        <SyncPrices />
    </div>
</template>
