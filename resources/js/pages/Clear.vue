<script setup lang="ts">
import { ref } from 'vue';

const loading = ref(false);
const deletionResponse = ref('Feel free to press the button');

async function deleteReadings() {
    loading.value = true;

    const res = await fetch('http://localhost:8000/api/readings?source=UPLOAD', {
        method: 'DELETE'
    });

    if (res.status === 404) {
        loading.value = false;
        deletionResponse.value = 'No UPLOAD records found.';
        return;
    } else if (res.status === 500) {
        loading.value = false;
        deletionResponse.value = 'Cleanup failed. Please try again.';
        return;
    }

    const resJson = await res.json();

    deletionResponse.value = `Deleted ${resJson.amount} uploaded records`;
    loading.value = false;
}
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <div class="flex w-full justify-center mt-auto text-5xl">{{deletionResponse}}</div>
        <div class="flex w-full my-auto justify-center">
            <button
                class="cursor-pointer rounded-full border text-3xl border-white px-4 py-3 text-center hover:border-transparent hover:bg-white hover:text-black disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-[#0a0a0a]"
                type="submit"
                :disabled="loading"
                @click="deleteReadings"
            >
                Delete uploads
            </button>
        </div>
    </div>
</template>
