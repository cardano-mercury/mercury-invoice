<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Dashboard from '@/Components/Dashboard.vue';
import PageHeader from '@/Components/PageHeader.vue';

defineProps({
    stats: Object
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <PageHeader 
                :title="$page.props.auth.user.business_name || 'Dashboard'" 
                subtitle="Review your business performance and key metrics"
                icon="mdi-view-dashboard"
            />
        </template>
        <Dashboard :stats="stats" />
    </AppLayout>
</template>
