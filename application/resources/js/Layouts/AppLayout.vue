<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/media/mercury-logo-full.png';
import Banner from '@/Components/Banner.vue';

defineProps({
    title: String,
});

const showingNavigationDrawer = ref(true);
const rail = ref(false);

const switchToTeam = (team) => {
    router.put(
        route('current-team.update'),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route('logout'));
};

const navItems = [
    { icon: 'mdi-view-dashboard', title: 'Dashboard', route: 'dashboard', pattern: 'dashboard' },
    { icon: 'mdi-account-group', title: 'Customers', route: 'customers.index', pattern: 'customers.*', countKey: 'customers' },
    { icon: 'mdi-package-variant', title: 'Products', route: 'products.index', pattern: 'products.*', countKey: 'products' },
    { icon: 'mdi-briefcase', title: 'Services', route: 'services.index', pattern: 'services.*', countKey: 'services' },
    { icon: 'mdi-file-document-multiple', title: 'Invoices', route: 'invoices.index', pattern: 'invoices.*', countKey: 'invoices' },
    { icon: 'mdi-chart-bar', title: 'Reports', route: 'reports.index', pattern: 'reports.*', countKey: 'reports' },
];
</script>

<template>
    <v-app>
        <Head :title="title" />

        <!-- App Bar -->
        <v-app-bar color="surface" elevation="0" class="app-bar">
            <template #prepend>
                <v-btn
                    icon
                    variant="text"
                    @click="rail = !rail"
                    class="ml-1"
                >
                    <v-icon :icon="rail ? 'mdi-menu' : 'mdi-menu-open'" />
                </v-btn>
            </template>

            <Link :href="route('dashboard')" class="d-flex align-center">
                <img :src="ApplicationLogo" height="40" alt="Mercury: Invoice" />
            </Link>

            <v-spacer />

            <!-- Search Button -->
            <v-btn icon variant="text" class="mr-1">
                <v-icon>mdi-magnify</v-icon>
                <v-tooltip activator="parent" location="bottom">Search</v-tooltip>
            </v-btn>

            <!-- Notifications -->
            <v-btn icon variant="text" class="mr-1">
                <v-badge color="primary" dot>
                    <v-icon>mdi-bell-outline</v-icon>
                </v-badge>
                <v-tooltip activator="parent" location="bottom">Notifications</v-tooltip>
            </v-btn>

            <!-- Team Switcher -->
            <v-menu v-if="$page.props.jetstream.hasTeamFeatures">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        variant="tonal"
                        color="primary"
                        class="mr-2"
                        append-icon="mdi-chevron-down"
                    >
                        <v-icon start icon="mdi-domain" />
                        {{ $page.props.auth.user.current_team.name }}
                    </v-btn>
                </template>
                <v-list density="compact" min-width="220">
                    <v-list-subheader>Manage Business</v-list-subheader>
                    <v-list-item
                        :href="route('teams.show', $page.props.auth.user.current_team)"
                        prepend-icon="mdi-cog"
                        title="Business Settings"
                    />
                    <v-list-item
                        v-if="$page.props.jetstream.canCreateTeam"
                        :href="route('teams.create')"
                        prepend-icon="mdi-plus"
                        title="Create New Business"
                    />
                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                        <v-divider class="my-2" />
                        <v-list-subheader>Switch Business</v-list-subheader>
                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                            <v-list-item
                                @click="switchToTeam(team)"
                                :prepend-icon="team.id === $page.props.auth.user.current_team_id ? 'mdi-check-circle' : 'mdi-circle-outline'"
                                :title="team.name"
                                :active="team.id === $page.props.auth.user.current_team_id"
                            />
                        </template>
                    </template>
                </v-list>
            </v-menu>

            <!-- User Menu -->
            <v-menu>
                <template #activator="{ props }">
                    <v-btn icon v-bind="props" variant="text">
                        <v-avatar
                            v-if="$page.props.jetstream.managesProfilePhotos"
                            :image="$page.props.auth.user.profile_photo_url"
                            size="36"
                        />
                        <v-avatar v-else color="primary" size="36">
                            <v-icon icon="mdi-account" />
                        </v-avatar>
                    </v-btn>
                </template>
                <v-list density="compact" min-width="200">
                    <v-list-item class="px-4 py-3">
                        <div class="text-subtitle-2 font-weight-bold">{{ $page.props.auth.user.name }}</div>
                        <div class="text-caption text-medium-emphasis">{{ $page.props.auth.user.email }}</div>
                    </v-list-item>
                    <v-divider />
                    <v-list-subheader>Manage Account</v-list-subheader>
                    <v-list-item
                        :href="route('user.settings')"
                        prepend-icon="mdi-cog"
                        title="Settings"
                    />
                    <v-list-item
                        :href="route('profile.show')"
                        prepend-icon="mdi-account"
                        title="Profile"
                    />
                    <v-list-item
                        v-if="$page.props.jetstream.hasApiFeatures"
                        :href="route('api-tokens.index')"
                        prepend-icon="mdi-key"
                        title="API Tokens"
                    />
                    <v-list-item
                        :href="route('webhooks.index')"
                        prepend-icon="mdi-webhook"
                        title="Webhooks"
                    />
                    <v-divider class="my-2" />
                    <Link :href="route('logout')" method="post">
                        <v-list-item prepend-icon="mdi-logout" title="Log Out" />
                    </Link>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Navigation Drawer -->
        <v-navigation-drawer
            :rail="rail"
            permanent
            v-model="showingNavigationDrawer"
            elevation="0"
            color="surface"
            class="nav-drawer"
        >
            <v-list density="comfortable" nav class="pa-2">
                <v-list-item
                    v-for="item in navItems"
                    :key="item.route"
                    :prepend-icon="item.icon"
                    :title="item.title"
                    :href="route(item.route)"
                    :active="route().current(item.pattern)"
                    rounded="lg"
                    class="mb-1"
                >
                    <template v-if="item.countKey && $page.props.count[item.countKey]" #append>
                        <v-chip
                            size="x-small"
                            color="primary"
                            variant="tonal"
                            class="font-weight-bold"
                        >
                            {{ $page.props.count[item.countKey] }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-list>

            <template #append v-if="!rail">
                <div class="pa-3">
                    <v-card variant="tonal" color="primary" class="support-card">
                        <v-card-text class="text-center py-4">
                            <v-icon icon="mdi-lifebuoy" size="32" class="mb-2" />
                            <h4 class="text-subtitle-2 font-weight-bold mb-1">Need Help?</h4>
                            <p class="text-caption text-medium-emphasis mb-3">
                                We're here to assist you
                            </p>
                            <v-btn
                                href="https://github.com/cardano-mercury/mercury-invoice/issues"
                                target="_blank"
                                color="primary"
                                variant="flat"
                                block
                                size="small"
                                prepend-icon="mdi-github"
                            >
                                Get Support
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </div>
            </template>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-main class="main-content">
            <v-container fluid class="pa-6">
                <!-- Page Header -->
                <header v-if="$slots.header" class="page-header">
                    <slot name="header" />
                </header>

                <!-- Banner -->
                <Banner />

                <!-- Flash Messages -->
                <v-alert
                    v-if="$page.props.flash.success"
                    type="success"
                    variant="tonal"
                    closable
                    class="mb-6"
                >
                    <template #prepend>
                        <v-icon icon="mdi-check-circle" />
                    </template>
                    {{ $page.props.flash.success }}
                </v-alert>

                <v-alert
                    v-if="$page.props.flash.info"
                    type="info"
                    variant="tonal"
                    closable
                    class="mb-6"
                >
                    <template #prepend>
                        <v-icon icon="mdi-information" />
                    </template>
                    {{ $page.props.flash.info }}
                </v-alert>

                <v-alert
                    v-if="$page.props.flash.error"
                    type="error"
                    variant="tonal"
                    closable
                    class="mb-6"
                >
                    <template #prepend>
                        <v-icon icon="mdi-alert-circle" />
                    </template>
                    {{ $page.props.flash.error }}
                </v-alert>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </v-container>
        </v-main>

        <!-- Footer -->
        <v-footer app class="app-footer">
            <v-container class="d-flex flex-wrap align-center justify-center justify-sm-space-between ga-2 py-3">
                <div class="text-body-2 text-medium-emphasis">
                    Mercury: Invoice v{{ $page.props.appVersion }}
                </div>
                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="d-flex flex-wrap ga-1">
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
            </v-container>
        </v-footer>
    </v-app>
</template>

<style scoped>
.app-bar {
    border-bottom: 1px solid rgb(var(--v-theme-surface-variant)) !important;
}

.nav-drawer {
    border-right: none !important;
}

.nav-drawer :deep(.v-list-item--active) {
    background: rgba(var(--v-theme-primary), 0.12) !important;
}

.nav-drawer :deep(.v-list-item--active .v-list-item__prepend .v-icon) {
    color: rgb(var(--v-theme-primary)) !important;
}

.nav-drawer :deep(.v-list-item--active .v-list-item-title) {
    color: rgb(var(--v-theme-primary)) !important;
    font-weight: 600;
}

.main-content {
    background: rgb(var(--v-theme-background));
}

.page-header {
    margin-bottom: 1.5rem;
}

.support-card {
    border-radius: 12px !important;
}

.app-footer {
    border-top: 1px solid rgb(var(--v-theme-surface-variant)) !important;
    background: rgb(var(--v-theme-surface)) !important;
}
</style>
