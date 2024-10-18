<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

defineProps({
    invoices: Array
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h1>{{ $page.props.auth.user.business_name }}</h1>
            <p class="text-body-2 text-grey">Review your stats</p>
        </template>
        <Welcome :invoices="invoices" />
    </AppLayout>
</template>
