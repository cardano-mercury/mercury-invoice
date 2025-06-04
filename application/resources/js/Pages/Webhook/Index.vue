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
            <p class="text-body-2 text-grey">Manage your webhook integrations</p>
        </template>
        <v-container>
            <v-row>
                <v-col cols="12" md="8">
                    <WebhookManager
                        :webhooks="webhooks"
                        :event-target-names="eventTargetNames"
                        :hmac-algorithms="hmacAlgorithms"
                    />
                </v-col>
            </v-row>
        </v-container>
    </app-layout>
</template>
