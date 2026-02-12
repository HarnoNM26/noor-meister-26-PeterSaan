<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center p-6 lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div
            class="inline-flex flex-col items-center justify-center text-white"
        >
            <Form
                class="rounded-xl border border-white p-3"
                action="/api/sync/prices"
                method="post"
                #default="{ errors, processing }"
            >
                <div class="mb-5 flex flex-col">
                    <label for="start">Start date:</label>
                    <input id="start" type="datetime-local" name="startDate" />
                </div>
                <div class="mb-5 flex flex-col">
                    <label for="end">End date:</label>
                    <input id="end" type="datetime-local" name="endDate" />
                </div>
                <div class="mb-8 flex">
                    <input id="ee" value="ee" type="radio" name="location" checked />
                    <label class="me-3" for="ee">EE</label>
                    <input id="lv" value="lv" type="radio" name="location" />
                    <label class="me-3" for="lv">LV</label>
                    <input id="fi" value="fi" type="radio" name="location" />
                    <label class="me-3" for="fi">FI</label>
                </div>
                <div class="flex justify-center">
                    <button
                        class="cursor-pointer rounded-full border border-white py-1 px-2 text-center hover:bg-white hover:text-black hover:border-transparent disabled:cursor-not-allowed disabled:bg-gray-500 disabled:border-transparent disabled:text-[#0a0a0a]"
                        type="submit"
                        :disabled="processing"
                    >
                        {{ processing ? 'Syncing...' : 'Sync prices' }}
                    </button>
                </div>
            </Form>
        </div>
    </div>
</template>
