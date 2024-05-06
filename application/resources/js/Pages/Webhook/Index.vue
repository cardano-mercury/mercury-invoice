<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WebhookManager from "@/Pages/Webhook/Partials/WebhookManager.vue";

defineProps({
    webhooks: Array,
    hmacAlgorithms: Array,
    eventTargetNames: Array,
});

</script>

<template>
    <app-layout title="Webhooks">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Webhooks
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <WebhookManager
                    :webhooks="webhooks"
                    :event-target-names="eventTargetNames"
                    :hmac-algorithms="hmacAlgorithms"
                />
            </div>
        </div>
    </app-layout>
</template>
