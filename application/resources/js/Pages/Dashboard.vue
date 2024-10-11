<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h1>Dashboard</h1>
        </template>

        <v-sheet class="bg-white px-4 py-12">
            <Welcome/>
        </v-sheet>
    </AppLayout>
</template>
