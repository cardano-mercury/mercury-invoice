<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from "@inertiajs/vue3";

defineProps({
    webhook: Object
});

</script>

<template>
    <app-layout title="Customers">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Webhook Logs
            </h2>
            <div class="flex justify-between">
                <span class="badge">
                    {{ webhook.url }}
                </span>
                <Link
                    :href="route('webhooks.index')"
                    class="text-blue-600"
                >
                    Go Back
                </Link>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">UTC Date & Time</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Event</th>
                                <th scope="col" class="px-6 py-3">Attempts</th>
                                <th scope="col" class="px-6 py-3">Payload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="webhookLog in webhook.logs" :key="webhookLog.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ webhookLog.formatted_datetime.datetime }}
                                    <span class="ml-2 sm-badge">{{ webhookLog.formatted_datetime.diff }}</span>
                                </th>
                                <td class="px-6 py-4">{{ webhookLog.status }}</td>
                                <td class="px-6 py-4">{{ webhookLog.event_name }}</td>
                                <td class="px-6 py-4">{{ webhookLog.attempts }}</td>
                                <td class="px-6 py-4">
                                    <textarea
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm"
                                    >{{ webhookLog.payload }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </app-layout>
</template>
