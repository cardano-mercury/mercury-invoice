<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MercuryLogo from '@/media/mercury-logo-full.png';

defineProps({
    title: String,
});
</script>

<template>
    <v-app class="invoice-layout">
        <Head :title="title" />

        <v-main class="invoice-main">
            <v-container class="py-8 py-md-12">
                <!-- Content -->
                <slot />

                <!-- Footer -->
                <div class="invoice-footer mt-12">
                    <v-divider class="mb-6" />
                    <div class="d-flex flex-column flex-sm-row align-center justify-center ga-3">
                        <span class="text-body-2 text-medium-emphasis">Powered by</span>
                        <Link :href="route('home')" target="_blank" class="d-flex align-center">
                            <img :src="MercuryLogo" alt="Cardano Mercury: Invoice" height="36" />
                        </Link>
                    </div>
                    <p class="text-center text-caption text-medium-emphasis mt-4">
                        Secure cryptocurrency payments powered by Cardano blockchain
                    </p>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.invoice-layout {
    background: linear-gradient(to bottom, rgb(var(--v-theme-background)) 0%, #f0f4f8 100%);
}

.invoice-main {
    min-height: 100vh;
}

.invoice-footer {
    text-align: center;
}
</style>
