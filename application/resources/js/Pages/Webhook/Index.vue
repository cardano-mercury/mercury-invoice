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
            <h1>Webhooks</h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <WebhookManager
                :webhooks="webhooks"
                :event-target-names="eventTargetNames"
                :hmac-algorithms="hmacAlgorithms"
            />
        </v-sheet>
    </app-layout>
</template>
