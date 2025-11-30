<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import dashboard from "@/media/dashboard.png";
import featuresBg from "@/media/features-2-bg.png";
import txSuccess from "@/media/txn-success.png";
import featuresColumn from "@/media/features-column.png";

import logo1 from "@/media/customer-logo-1.png";
import logo2 from "@/media/customer-logo-2.png";
import logo3 from "@/media/customer-logo-3.png";
import logo4 from "@/media/customer-logo-4.png";
import logo5 from "@/media/customer-logo-5.png";
import {ref} from "vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const isYearly = ref(true);
</script>

<template>
    <GuestLayout title="Welcome">
        <!-- Hero Section -->
        <v-container id="hero" class="d-flex flex-column align-center py-16 mt-8">
            <v-chip variant="tonal" color="primary" class="mb-8" size="large">
                <v-icon start icon="mdi-rocket-launch" />
                <span class="font-weight-medium">X+ merchants sell with Mercury!</span>
            </v-chip>
            <h1 class="text-center mb-4">Empower Your Business with Crypto Payments</h1>
            <p class="text-h6 text-medium-emphasis text-center mb-8" style="max-width: 600px;">
                Start accepting cryptocurrency and reach a global audience with our secure, modern invoicing platform.
            </p>
            <div class="mb-12 d-flex ga-3">
                <v-btn 
                    variant="outlined" 
                    color="primary"
                    size="large"
                    prepend-icon="mdi-information"
                    rounded="lg"
                >
                    Learn More
                </v-btn>
                <v-btn 
                    color="primary" 
                    variant="flat"
                    size="large"
                    prepend-icon="mdi-rocket-launch"
                    rounded="lg"
                    :href="route('register')"
                >
                    Get Started
                </v-btn>
            </div>
            <v-card elevation="8" rounded="xl" class="dashboard-preview">
                <img :src="dashboard" alt="Dashboard Preview" style="width: 100%; display: block;" />
            </v-card>
        </v-container>

        <!-- Features Section -->
        <v-parallax :src="featuresBg" id="features">
            <v-container class="py-16" max-width="1280">
                <v-row align="center" class="mb-12">
                    <v-col cols="12" md="7">
                        <v-chip variant="tonal" color="primary" class="mb-4" size="small">
                            <v-icon start icon="mdi-star" size="small" />
                            Features
                        </v-chip>
                        <h2 class="mb-4">Experience the Future of Payment Processing</h2>
                        <p class="text-body-1 text-medium-emphasis mb-8">
                            Our SaaS platform provides secure, easy-to-use, and low transaction fee solutions for
                            accepting cryptocurrency payments.
                        </p>
                        <v-row class="mb-8">
                            <v-col cols="12" sm="6">
                                <v-card variant="tonal" class="pa-4 h-100" rounded="lg">
                                    <v-icon icon="mdi-shield-check" color="primary" size="32" class="mb-3" />
                                    <h3 class="text-h6 mb-2">Security</h3>
                                    <p class="text-body-2 text-medium-emphasis">
                                        Protect your transactions with our advanced security measures and encryption
                                        technology.
                                    </p>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-card variant="tonal" class="pa-4 h-100" rounded="lg">
                                    <v-icon icon="mdi-puzzle" color="primary" size="32" class="mb-3" />
                                    <h3 class="text-h6 mb-2">Ease of Use</h3>
                                    <p class="text-body-2 text-medium-emphasis">
                                        Our user-friendly interface makes it simple to integrate and manage cryptocurrency
                                        payments.
                                    </p>
                                </v-card>
                            </v-col>
                        </v-row>
                        <v-btn 
                            color="primary" 
                            variant="flat"
                            size="large"
                            prepend-icon="mdi-rocket-launch"
                            rounded="lg"
                            :href="route('register')"
                        >
                            Get Started
                        </v-btn>
                    </v-col>
                    <v-col cols="12" md="5" class="d-none d-md-flex justify-center">
                        <v-img :src="txSuccess" max-width="400" />
                    </v-col>
                </v-row>

                <v-row align="center" class="mt-8">
                    <v-col cols="12" md="7">
                        <v-card variant="flat" rounded="xl" class="pa-6 bg-white">
                            <div class="mb-6">
                                <div class="d-flex align-center mb-3">
                                    <v-avatar color="primary" size="40" class="mr-3">
                                        <v-icon icon="mdi-connection" />
                                    </v-avatar>
                                    <h3 class="text-h6">Seamless Integration</h3>
                                </div>
                                <p class="text-body-2 text-medium-emphasis pl-13">
                                    Designed with simplicity in mind, Mercury boasts an intuitive interface that requires no
                                    prior blockchain knowledge, making it accessible for all team members.
                                </p>
                            </div>
                            <v-divider class="my-4" />
                            <div class="mb-6">
                                <div class="d-flex align-center mb-3">
                                    <v-avatar color="primary" size="40" class="mr-3">
                                        <v-icon icon="mdi-chart-line" />
                                    </v-avatar>
                                    <h3 class="text-h6">Detailed Statistics</h3>
                                </div>
                                <p class="text-body-2 text-medium-emphasis pl-13">
                                    Get access to real-time analytics and get to know your customer with our streamlined
                                    workflows.
                                </p>
                            </div>
                            <v-divider class="my-4" />
                            <div>
                                <div class="d-flex align-center mb-3">
                                    <v-avatar color="primary" size="40" class="mr-3">
                                        <v-icon icon="mdi-headset" />
                                    </v-avatar>
                                    <h3 class="text-h6">Customer Support and Service</h3>
                                </div>
                                <p class="text-body-2 text-medium-emphasis pl-13">
                                    We are committed to supporting users with a dedicated team that ensures businesses can
                                    maximize the platform's capabilities.
                                </p>
                            </div>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="5" class="d-none d-md-flex justify-center">
                        <v-img :src="featuresColumn" max-width="350" />
                    </v-col>
                </v-row>
            </v-container>
        </v-parallax>

        <!-- Logos & Reviews Section -->
        <v-container fluid id="reviews" class="py-16 bg-grey-lighten-5">
            <v-row class="mb-12 text-center" align="center" justify="center">
                <v-col cols="6" sm="4" md="2">
                    <img :src="logo1" alt="Customer Logo" class="customer-logo" />
                </v-col>
                <v-col cols="6" sm="4" md="2">
                    <img :src="logo2" alt="Customer Logo" class="customer-logo" />
                </v-col>
                <v-col cols="6" sm="4" md="2">
                    <img :src="logo3" alt="Customer Logo" class="customer-logo" />
                </v-col>
                <v-col cols="6" sm="4" md="2">
                    <img :src="logo4" alt="Customer Logo" class="customer-logo" />
                </v-col>
                <v-col cols="6" sm="4" md="2">
                    <img :src="logo5" alt="Customer Logo" class="customer-logo" />
                </v-col>
            </v-row>

            <v-row justify="center" align="center">
                <v-col cols="12" lg="8" class="text-center">
                    <v-card variant="flat" class="bg-white pa-8" rounded="xl" elevation="2">
                        <v-carousel 
                            show-arrows="hover" 
                            continuous 
                            hide-delimiters 
                            height="280"
                            :cycle="true"
                            interval="6000"
                        >
                            <v-carousel-item>
                                <div class="d-flex fill-height justify-center align-center flex-column px-8">
                                    <v-rating color="primary" :model-value="5" readonly class="mb-4" />
                                    <p class="text-body-1 text-medium-emphasis mb-6" style="max-width: 600px;">
                                        «Mercury has revolutionized how we handle transactions at TechForward Inc.
                                        Its support for multiple cryptocurrencies has expanded our customer base.
                                        I highly recommend Mercury to any business looking to integrate secure and
                                        efficient crypto payments»
                                    </p>
                                    <div class="d-flex align-center">
                                        <v-avatar size="48" class="mr-4">
                                            <v-img :src="logo4" />
                                        </v-avatar>
                                        <div class="text-start">
                                            <div class="font-weight-bold">Jessica Lin</div>
                                            <div class="text-caption text-medium-emphasis">CTO, TechForward Inc.</div>
                                        </div>
                                    </div>
                                </div>
                            </v-carousel-item>
                        </v-carousel>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <!-- Pricing Section -->
        <v-container id="pricing" max-width="960" class="py-16 text-center">
            <v-chip variant="tonal" color="primary" class="mb-4" size="small">
                <v-icon start icon="mdi-tag" size="small" />
                Pricing
            </v-chip>
            <h2 class="mb-2">Pricing Options</h2>
            <p class="text-body-1 text-medium-emphasis mb-8">
                Choose the plan that best meets your needs
            </p>
            <v-btn-toggle 
                color="primary" 
                v-model="isYearly" 
                density="comfortable" 
                class="mb-10"
                rounded="lg"
            >
                <v-btn :value="true" variant="flat">
                    <v-icon start icon="mdi-calendar" />
                    Yearly
                    <v-chip size="x-small" color="success" class="ml-2">Save 20%</v-chip>
                </v-btn>
                <v-btn :value="false" variant="flat">
                    <v-icon start icon="mdi-calendar-month" />
                    Monthly
                </v-btn>
            </v-btn-toggle>

            <v-row align-content="stretch" justify="center">
                <v-col cols="12" md="5">
                    <v-card 
                        class="text-center h-100 d-flex flex-column" 
                        variant="outlined"
                        rounded="xl"
                    >
                        <v-card-title class="text-body-1 pt-6">Free Plan</v-card-title>
                        <v-card-text class="pb-0">
                            <div class="text-h2 font-weight-bold text-primary">$0</div>
                            <div class="text-caption text-medium-emphasis">per month</div>
                        </v-card-text>
                        <v-card-text class="text-start flex-grow-1">
                            <v-list density="compact" class="bg-transparent">
                                <v-list-item prepend-icon="mdi-check-circle" class="px-0">
                                    <v-list-item-title>Secure Payment Processing</v-list-item-title>
                                </v-list-item>
                                <v-list-item prepend-icon="mdi-check-circle" class="px-0">
                                    <v-list-item-title>Easy Integration Options</v-list-item-title>
                                </v-list-item>
                                <v-list-item prepend-icon="mdi-check-circle" class="px-0">
                                    <v-list-item-title>Basic Reporting</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-card-text>
                        <v-card-text class="pt-0 pb-6">
                            <v-btn 
                                block 
                                variant="outlined"
                                color="primary"
                                size="large"
                                rounded="lg"
                                :href="route('register')"
                            >
                                Sign Up Free
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="5">
                    <v-card 
                        class="text-center h-100 d-flex flex-column pricing-featured" 
                        variant="flat"
                        rounded="xl"
                        color="primary"
                    >
                        <v-chip 
                            color="white" 
                            variant="flat" 
                            class="mx-auto mt-n3"
                            size="small"
                        >
                            <v-icon start icon="mdi-star" size="small" />
                            Most Popular
                        </v-chip>
                        <v-card-title class="text-body-1 pt-4">Enterprise Plan</v-card-title>
                        <v-card-text class="pb-0">
                            <div class="text-h2 font-weight-bold">{{ isYearly ? '$39' : '$49' }}</div>
                            <div class="text-caption opacity-70">per month</div>
                        </v-card-text>
                        <v-card-text class="text-start flex-grow-1">
                            <v-list density="compact" class="bg-transparent">
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon icon="mdi-check-circle" />
                                    </template>
                                    <v-list-item-title>Dedicated Account Manager</v-list-item-title>
                                </v-list-item>
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon icon="mdi-check-circle" />
                                    </template>
                                    <v-list-item-title>Custom API Integration</v-list-item-title>
                                </v-list-item>
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon icon="mdi-check-circle" />
                                    </template>
                                    <v-list-item-title>Fraud Detection and Prevention</v-list-item-title>
                                </v-list-item>
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon icon="mdi-check-circle" />
                                    </template>
                                    <v-list-item-title>24/7 Support</v-list-item-title>
                                </v-list-item>
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon icon="mdi-check-circle" />
                                    </template>
                                    <v-list-item-title>Advanced Reporting</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-card-text>
                        <v-card-text class="pt-0 pb-6">
                            <v-btn 
                                block 
                                variant="flat"
                                color="white"
                                size="large"
                                rounded="lg"
                                class="text-primary"
                                :href="route('register')"
                            >
                                Get Started
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <!-- CTA Section -->
        <v-container fluid class="py-16 bg-primary">
            <v-row justify="center" class="text-center">
                <v-col cols="12" md="8" lg="6">
                    <h2 class="text-white mb-4">Ready to Get Started?</h2>
                    <p class="text-white opacity-80 mb-8">
                        Join thousands of businesses already using Mercury for their crypto payments.
                    </p>
                    <v-btn 
                        color="white" 
                        variant="flat"
                        size="x-large"
                        prepend-icon="mdi-rocket-launch"
                        rounded="lg"
                        class="text-primary"
                        :href="route('register')"
                    >
                        Start Free Trial
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </GuestLayout>
</template>

<style scoped>
.dashboard-preview {
    max-width: 1000px;
    overflow: hidden;
}

.customer-logo {
    max-height: 40px;
    opacity: 0.6;
    filter: grayscale(100%);
    transition: all 0.3s ease;
}

.customer-logo:hover {
    opacity: 1;
    filter: grayscale(0%);
}

.pricing-featured {
    transform: scale(1.02);
    box-shadow: 0 8px 32px rgba(22, 193, 112, 0.3);
}

.pl-13 {
    padding-left: 52px;
}
</style>
