<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Dashboard from '@/Components/Dashboard.vue';

defineProps({
    stats: Object
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h1>{{ $page.props.auth.user.business_name }}</h1>
            <p class="text-body-2 text-grey">Review your stats</p>
        </template>
        <Dashboard :stats="stats" />
    </AppLayout>
</template>
