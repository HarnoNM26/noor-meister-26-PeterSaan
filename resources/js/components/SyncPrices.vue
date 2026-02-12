<script setup lang="ts">
import { ref } from 'vue';

const loading = ref(false);
const startDate = ref<Date | null>(null);
const endDate = ref<Date | null>(null);
const location = ref('ee');
async function syncPrices() {
    loading.value = true;
    const fetchBody = {
        startDate: startDate.value,
        endDate: endDate.value,
        location: location.value,
    };

    try {
        const res = await fetch('http://localhost:8000/api/sync/prices', {
            method: 'POST',
            body: fetchBody,
        });
    } catch (e) {
        loading.value = false;
        return;
    }

    loading.value = false;
}
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center p-6 lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div
            class="inline-flex flex-col items-center justify-center text-white"
        >
            <form class="rounded-xl border border-white p-3">
                <div class="mb-5 flex flex-col">
                    <label for="start">Start date:</label>
                    <input
                        id="start"
                        type="datetime-local"
                        name="startDate"
                        v-model="startDate"
                    />
                </div>
                <div class="mb-5 flex flex-col">
                    <label for="end">End date:</label>
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
                        checked
                        v-model="location"
                    />
                    <label class="me-3" for="ee">EE</label>
                    <input
                        id="lv"
                        value="lv"
                        type="radio"
                        name="location"
                        v-model="location"
                    />
                    <label class="me-3" for="lv">LV</label>
                    <input
                        id="fi"
                        value="fi"
                        type="radio"
                        name="location"
                        v-model="location"
                    />
                    <label class="me-3" for="fi">FI</label>
                </div>
                <div class="flex justify-center">
                    <button
                        class="cursor-pointer rounded-full border border-white px-2 py-1 text-center hover:border-transparent hover:bg-white hover:text-black disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-[#0a0a0a]"
                        type="submit"
                        :disabled="loading"
                        @click="syncPrices"
                    >
                        {{ loading ? 'Syncing...' : 'Sync prices' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
