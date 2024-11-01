<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    policy: String,
});
</script>

<template>
    <GuestLayout title="Privacy Policy">
        <div class="pa-5" v-html="policy"></div>
    </GuestLayout>
</template>
