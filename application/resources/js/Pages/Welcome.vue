<script setup>
import {Head} from '@inertiajs/vue3';
import applicationLogo from '@/media/mercury-logo-full.png'
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>
<template>
    <GuestLayout title="Welcome">
        <section id="features">Features</section>
        <section id="reviews">Reviews</section>
        <section id="pricing">Pricing</section>
    </GuestLayout>
</template>
