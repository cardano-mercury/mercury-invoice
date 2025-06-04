<script setup>
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});
</script>

<template>
    <AppLayout title="API Tokens">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </template>

        <v-container>
            <v-row>
                <v-col cols="12" md="8">
                    <ApiTokenManager
                        :tokens="tokens"
                        :available-permissions="availablePermissions"
                        :default-permissions="defaultPermissions"
                    />
                </v-col>
            </v-row>
        </v-container>
    </AppLayout>
</template>
