<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    terms: String,
});
</script>

<template>
    <GuestLayout title="Terms of Service">
        <div class="pa-5" v-html="terms"></div>
    </GuestLayout>
</template>
