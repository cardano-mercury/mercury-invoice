<script setup>
import { Head, Link } from '@inertiajs/vue3';
import applicationLogo from '@/media/mercury-logo-full.png';

defineProps({
    title: String,
});

const isHomePage = () => {
    return window.location.pathname === '/' || window.location.hash.startsWith('#');
};
</script>

<template>
    <v-app>
        <Head :title="title" />

        <!-- App Bar -->
        <v-app-bar color="surface" elevation="0" class="guest-app-bar">
            <v-container class="d-flex align-center">
                <Link :href="route('home')" class="d-flex align-center">
                    <img :src="applicationLogo" alt="Mercury: Invoice" height="40" />
                </Link>

                <v-spacer />

                <!-- Navigation Links (Home page only) -->
                <div v-if="$page.url === '/' || $page.url.startsWith('/#')" class="d-none d-md-flex align-center">
                    <v-btn variant="text" href="#features" class="nav-link">
                        Features
                    </v-btn>
                    <v-btn variant="text" href="#reviews" class="nav-link">
                        Reviews
                    </v-btn>
                    <v-btn variant="text" href="#pricing" class="nav-link">
                        Pricing
                    </v-btn>
                </div>

                <v-spacer />

                <!-- CTA Buttons -->
                <div class="d-flex align-center ga-2">
                    <template v-if="$page.url === '/'">
                        <v-btn
                            :href="route('login')"
                            variant="text"
                            class="d-none d-sm-flex"
                        >
                            Sign In
                        </v-btn>
                        <v-btn
                            color="primary"
                            variant="flat"
                            :href="route('dashboard')"
                            prepend-icon="mdi-rocket-launch"
                        >
                            Launch App
                        </v-btn>
                    </template>
                    <template v-else>
                        <v-btn
                            variant="text"
                            :href="route('home')"
                            prepend-icon="mdi-arrow-left"
                        >
                            Back to Homepage
                        </v-btn>
                    </template>
                </div>
            </v-container>
        </v-app-bar>

        <!-- Main Content -->
        <v-main class="guest-main">
            <slot />
        </v-main>

        <!-- Footer -->
        <v-footer class="guest-footer">
            <v-container>
                <v-row align="center" justify="space-between">
                    <v-col cols="12" md="auto" class="text-center text-md-left">
                        <div class="d-flex align-center justify-center justify-md-start ga-2">
                            <img :src="applicationLogo" alt="Mercury: Invoice" height="28" />
                            <span class="text-body-2 text-medium-emphasis">
                                v{{ $page.props.appVersion }}
                            </span>
                        </div>
                    </v-col>
                    <v-col cols="12" md="auto" class="text-center text-md-right">
                        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="d-flex flex-wrap justify-center justify-md-end ga-1">
                            <v-btn
                                variant="text"
                                size="small"
                                target="_blank"
                                :href="route('terms.show')"
                                class="text-medium-emphasis"
                            >
                                Terms
                            </v-btn>
                            <v-btn
                                variant="text"
                                size="small"
                                target="_blank"
                                :href="route('policy.show')"
                                class="text-medium-emphasis"
                            >
                                Privacy
                            </v-btn>
                            <v-btn
                                variant="text"
                                size="small"
                                target="_blank"
                                href="/docs/api"
                                class="text-medium-emphasis"
                            >
                                API Docs
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>

<style scoped>
.guest-app-bar {
    border-bottom: 1px solid rgb(var(--v-theme-surface-variant)) !important;
}

.guest-main {
    background: rgb(var(--v-theme-background));
}

.nav-link {
    font-weight: 500;
    letter-spacing: 0;
}

.guest-footer {
    border-top: 1px solid rgb(var(--v-theme-surface-variant)) !important;
    background: rgb(var(--v-theme-surface)) !important;
    padding: 1.5rem 0;
}
</style>
