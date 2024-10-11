<script setup>
import {Head} from '@inertiajs/vue3';
import applicationLogo from '@/media/mercury-logo-full.png'

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
    <Head title="Welcome"/>

    <v-app>
        <v-app-bar color="white" elevation="0" class="px-8">
            <img :src="applicationLogo" alt="Mercury: Invoice" height="48"
                 width="151"/>
            <v-spacer/>
            <div>
                <v-btn size="large" variant="text" href="#features">Features</v-btn>
                <v-btn size="large" variant="text" href="#reviews">Reviews</v-btn>
                <v-btn size="large" variant="text" href="#pricing">Pricing</v-btn>
            </div>
            <v-spacer/>
            <v-btn color="primary" variant="flat" size="large"
                   :href="route('dashboard')"
                   prepend-icon="mdi-greater-than">Launch App
            </v-btn>
        </v-app-bar>
        <v-main class="d-flex flex-column">
            <section id="features">Features</section>
            <section id="reviews">Reviews</section>
            <section id="pricing">Pricing</section>
        </v-main>
        <v-footer app>
            <v-container class="text-center">
                Cardano Mercury: Invoice v{{ $page.props.appVersion }}
            </v-container>
        </v-footer>
    </v-app>
</template>
