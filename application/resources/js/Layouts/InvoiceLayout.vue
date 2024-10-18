<script setup>
import {Head, Link} from "@inertiajs/vue3";
import MercuryLogo from "@/media/mercury-logo-full.png";

defineProps({
    title: String,
});
</script>

<template>
    <v-app>
        <Head :title="title" />
        <v-container class="my-8 mb-12">
            <slot />
            <p class="text-center d-flex align-center justify-center">
                Powered by
                <Link :href="route('home')" target="_blank">
                    <img :src="MercuryLogo" alt="Cardano Mercury: Invoice" height="64" />
                </Link>
            </p>
        </v-container>
    </v-app>
</template>

<style>
</style>
