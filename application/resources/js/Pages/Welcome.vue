<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
//import heroBg from "@/media/hero-bg.jpg";
import dashboard from "@/media/dashboard.png";
import featuresBg from "@/media/features-2-bg.png";
import txSuccess from "@/media/txn-success.png";
import featuresColumn from "@/media/features-column.png";

import logo1 from "@/media/customer-logo-1.png";
import logo2 from "@/media/customer-logo-2.png";
import logo3 from "@/media/customer-logo-3.png";
import logo4 from "@/media/customer-logo-4.png";
import logo5 from "@/media/customer-logo-5.png";
import {reactive, ref} from "vue";

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
<!--        <v-parallax :src="heroBg" scale="0.6">-->
            <v-container id="hero" class="d-flex flex-column align-center py-16 mt-16">
                <v-chip variant="outlined" class="mb-8">
                    <span class="text-red">X+&nbsp;</span>
                    merchants sell with Mercury!
                </v-chip>
                <h1>Empower Your Business with Crypto Payments</h1>
                <p>
                    Start accepting cryptocurrency and reach a global audience
                </p>
                <div class="my-8">
                    <v-btn elevation="0" class="me-2">Learn More</v-btn>
                    <v-btn color="black" dark>Get Started</v-btn>
                </div>
                <img :src="dashboard" width="80%" />
            </v-container>
<!--        </v-parallax>-->
        <v-parallax :src="featuresBg" id="features">
            <v-container class="py-16" max-width="1280">
                <v-row align="center" class="mb-8">
                    <v-col>
                        <h2>Experience the Future of Payment Processing</h2>
                        <p>
                            Our SaaS platform provides secure, easy-to-use, and low transaction fee solutions for
                            accepting cryptocurrency payments.
                        </p>
                        <v-row class="my-6">
                            <v-col>
                                <h3>Security</h3>
                                <p>
                                    Protect your transactions with our advanced security measures and encryption
                                    technology.
                                </p>
                            </v-col>
                            <v-col>
                                <h3>Ease of Use</h3>
                                <p>
                                    Our user-friendly interface makes it simple to integrate and manage cryptocurrency
                                    payments.
                                </p>
                            </v-col>
                        </v-row>
                        <v-btn color="black" elevation="0">Get Started</v-btn>

                    </v-col>
                    <v-col cols="4">
                        <v-img :src="txSuccess"/>
                    </v-col>
                </v-row>
                <v-row align="center">
                    <v-col>
                        <v-card variant="tonal" rounded="xl" elevation="0" class="py-4">
                            <v-card-title>Seamless Integration</v-card-title>
                            <v-card-text>
                                Designed with simplicity in mind, Mercury boasts an intuitive interface that requires no
                                prior blockchain knowledge, making it accessible for all team members.
                            </v-card-text>
                            <v-divider class="my-4"/>
                            <v-card-title>Detailed Statistics</v-card-title>
                            <v-card-text>
                                Get access to real-time analytics and get to know your customer with our streamlined
                                workflows.
                            </v-card-text>
                            <v-divider class="my-4"/>
                            <v-card-title>
                                Customer Support and Service
                            </v-card-title>
                            <v-card-text>
                                We are committed to supporting users with a dedicated team that ensures businesses can
                                maximize the platform’s capabilities.
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="4">
                        <v-img :src="featuresColumn"/>
                    </v-col>
                </v-row>
            </v-container>
        </v-parallax>
        <v-container fluid fill-height id="reviews">
            <v-row class="my-8 text-center" align="center" justify="center">
                <v-col>
                    <img :src="logo1" alt=""/>
                </v-col>
                <v-col>
                    <img :src="logo2" alt=""/>
                </v-col>
                <v-col>
                    <img :src="logo3" alt=""/>
                </v-col>
                <v-col>
                    <img :src="logo4" alt=""/>
                </v-col>
                <v-col>
                    <img :src="logo5" alt=""/>
                </v-col>
            </v-row>
            <v-row justify="center" align="center" class="bg-white">
                <v-col cols="12" lg="8" class="text-center">
                    <v-carousel show-arrows="hover" continuous hide-delimiters height="300">
                        <v-carousel-item>

                            <div class="d-flex fill-height justify-center align-center flex-column px-16">
                                <v-row align="center" justify="center">
                                    <v-col cols="10" lg="8">
                                        <v-rating color="primary" :model-value="5" class="flex-0-0"/>
                                        <p class="mt-4 mb-8">
                                            «Mercury has revolutionized how we handle transactions at TechForward Inc.
                                            Its
                                            support
                                            for multiple cryptocurrencies has expanded our customer base.
                                            I highly recommend Mercury to any business looking to integrate secure and
                                            efficient
                                            crypto payments»
                                        </p>
                                        <v-row align="center" justify="center" class="flex-0-0">
                                            <v-col cols="auto">
                                                <v-row>
                                                    <v-col cols="auto">
                                                        <v-avatar size="36">
                                                            <v-img :src="logo4"/>
                                                        </v-avatar>
                                                    </v-col>
                                                    <v-col class="text-start">
                                                        Jessica Lin<br/>
                                                        CTO, TechForward Inc.
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                            <v-col cols="auto">
                                                <img :src="logo5" height="36" alt="Company Name"/>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-carousel-item>
                    </v-carousel>
                </v-col>
            </v-row>
        </v-container>
        <v-container fill-height id="pricing" max-width="768" class="text-center">
            <h2>Pricing Options</h2>
            <p>
                Choose the plan that best meets your needs
            </p>
            <v-btn-toggle color="primary" v-model="isYearly" density="compact" class="my-8">
                <v-btn :value="true">Yearly</v-btn>
                <v-btn :value="false">Monthly</v-btn>
            </v-btn-toggle>
            <v-row align-content="stretch" justify="center">
                <v-col>
                    <v-card class="text-center fill-height d-flex flex-column flex-nowrap align-center justify-start" elevation="0">
                        <v-card-title class="text-body-1">Free Plan</v-card-title>
                        <v-card-text class="text-h2 flex-0-0">$0/mo</v-card-text>
                        <v-card-text class="text-start">
                            <v-list>
                                <v-list-item prepend-icon="mdi-check">Secure Payment Processing</v-list-item>
                                <v-list-item prepend-icon="mdi-check">Easy Integration Options</v-list-item>
                            </v-list>
                        </v-card-text>
                        <v-card-text class="flex-0-0 w-100">
                            <v-btn block color="black">Sign Up</v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col>
                    <v-card class="text-center fill-height d-flex flex-column flex-nowrap align-center justify-start" elevation="0">
                        <v-card-title class="text-body-1">Enterprise Plan</v-card-title>
                        <v-card-text class="text-h2 flex-0-0">$49/mo</v-card-text>
                        <v-card-text class="text-start">
                            <v-list>
                                <v-list-item>
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-check" color="primary" />
                                    </template>
                                    Dedicated Account Manager
                                </v-list-item>
                                <v-list-item>
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-check" color="primary" />
                                    </template>
                                    Custom API Integration
                                </v-list-item>
                                <v-list-item>
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-check" color="primary" />
                                    </template>
                                    Fraud Detection and Prevention
                                </v-list-item>
                                <v-list-item>
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-check" color="primary" />
                                    </template>
                                    24/7 Support
                                </v-list-item>
                                <v-list-item>
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-check" color="primary" />
                                    </template>
                                    Advanced Reporting
                                </v-list-item>
                            </v-list>
                        </v-card-text>
                        <v-card-text class="flex-0-0 w-100">
                            <v-btn color="primary" block>Get Started</v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </GuestLayout>
</template>
<style>

</style>
