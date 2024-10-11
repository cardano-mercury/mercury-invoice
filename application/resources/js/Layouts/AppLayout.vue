<script setup>
import {ref} from 'vue';
import {Head, Link, router} from '@inertiajs/vue3';
import ApplicationLogo from '@/media/mercury-logo-full.png';

import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDrawer = ref(true);
const rail = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <v-app>
        <Head :title="title"/>
        <v-app-bar color="white" elevation="0">
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click="rail = !rail"></v-app-bar-nav-icon>
            </template>
            <img :src="ApplicationLogo" height="48" width="151"
                 alt="Mercury: Invoice"/>
            <v-spacer/>
            <v-btn icon="mdi-magnify"/>
            <v-btn icon="mdi-bell"/>
            <v-menu v-if="$page.props.jetstream.hasTeamFeatures">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" append-icon="mdi-menu-down">
                        {{ $page.props.auth.user.current_team.name }}
                    </v-btn>
                </template>
                <v-list>
                    <v-list-subheader>Manage Team</v-list-subheader>
                    <v-list-item
                        :href="route('teams.show', $page.props.auth.user.current_team)">
                        Business Settings
                    </v-list-item>
                    <v-list-item v-if="$page.props.jetstream.canCreateTeam" :href="route('teams.create')">
                        Create New Business
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-btn variant="outlined" color="lightgray">Edit Profile</v-btn>
        </v-app-bar>
        <v-navigation-drawer :rail="rail" permanent
                             v-model="showingNavigationDrawer" elevation="0"
                             color="white">
            <v-list-item prepend-icon="mdi-home" title="Dashboard"
                         :href="route('dashboard')"
                         :active="route().current('dashboard')"/>
            <v-list-item prepend-icon="mdi-account-group" title="Customers"
                         :href="route('customers.index')"
                         :active="route().current('customers.*')"/>
            <v-list-item prepend-icon="mdi-clipboard" title="Products"
                         :href="route('products.index')"
                         :active="route().current('products.*')"/>
            <v-list-item prepend-icon="mdi-hammer" title="Services"
                         :href="route('services.index')"
                         :active="route().current('services.*')"/>
            <v-list-item prepend-icon="mdi-invoice-list" title="Invoices"
                         :href="route('invoices.index')"
                         :active="route().current('invoices.*')"/>
            <v-list-item prepend-icon="mdi-list-box" title="Reports"/>
            <v-divider/>
            <v-list-item prepend-icon="mdi-cog" title="Settings"/>

            <template v-slot:append v-if="!rail">
                <div class="pa-2">
                    <v-card>
                        <v-card-title>
                            <v-icon icon="mdi-face-agent"/>
                            Need Support?
                        </v-card-title>
                        <v-card-text>
                            Feel free to reach out using the button below.
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="black" variant="flat" block>Contact
                                Us
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </template>
        </v-navigation-drawer>
        <v-main>
            <v-container fluid>
                <Banner/>
                <header v-if="$slots.header">
                    <slot name="header"/>
                </header>
                <v-alert type="success" v-if="$page.props.flash.success">
                    {{ $page.props.flash.success }}
                </v-alert>
                <v-alert type="info" v-if="$page.props.flash.info">
                    {{ $page.props.flash.info }}
                </v-alert>
                <v-alert type="error" v-if="$page.props.flash.error">
                    {{ $page.props.flash.error }}
                </v-alert>
                <main>
                    <slot/>
                </main>
            </v-container>
        </v-main>
        <v-footer app>
            <v-container class="text-center">
                Cardano Mercury: Invoice v{{ $page.props.appVersion }}
            </v-container>
        </v-footer>
    </v-app>


    <!-- Teams Dropdown -->
    <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right"
              width="60">
        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{
                                                    $page.props.auth.user.current_team.name
                                                }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/>
                                                </svg>
                                            </button>
                                        </span>
        </template>

        <template #content>
            <div class="w-60">
                <!-- Team Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Manage Team
                </div>

                <!-- Team Settings -->
                <DropdownLink
                    :href="route('teams.show', $page.props.auth.user.current_team)">
                    Team Settings
                </DropdownLink>

                <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                              :href="route('teams.create')">
                    Create New Team
                </DropdownLink>

                <!-- Team Switcher -->
                <template v-if="$page.props.auth.user.all_teams.length > 1">
                    <div class="border-t border-gray-200"/>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Switch Teams
                    </div>

                    <template v-for="team in $page.props.auth.user.all_teams"
                              :key="team.id">
                        <form @submit.prevent="switchToTeam(team)">
                            <DropdownLink as="button">
                                <div class="flex items-center">
                                    <svg
                                        v-if="team.id == $page.props.auth.user.current_team_id"
                                        class="me-2 h-5 w-5 text-green-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    <div>{{ team.name }}</div>
                                </div>
                            </DropdownLink>
                        </form>
                    </template>
                </template>
            </div>
        </template>
    </Dropdown>

    <!-- Settings Dropdown -->
    <Dropdown align="right" width="48">
        <template #trigger>
            <button v-if="$page.props.jetstream.managesProfilePhotos"
                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <img class="h-8 w-8 rounded-full object-cover"
                     :src="$page.props.auth.user.profile_photo_url"
                     :alt="$page.props.auth.user.name">
            </button>

            <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                                </svg>
                                            </button>
                                        </span>
        </template>

        <template #content>
            <!-- Account Management -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                Manage Account
            </div>

            <DropdownLink :href="route('user.settings')">
                Settings
            </DropdownLink>

            <DropdownLink :href="route('profile.show')">
                Profile
            </DropdownLink>

            <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                          :href="route('api-tokens.index')">
                API Tokens
            </DropdownLink>

            <DropdownLink :href="route('webhooks.index')">
                Webhooks
            </DropdownLink>

            <div class="border-t border-gray-200"/>

            <!-- Authentication -->
            <form @submit.prevent="logout">
                <DropdownLink as="button">
                    Log Out
                </DropdownLink>
            </form>
        </template>
    </Dropdown>


    <!-- Responsive Settings Options -->
    <div class="flex items-center px-4">
        <div v-if="$page.props.jetstream.managesProfilePhotos"
             class="shrink-0 me-3">
            <img class="h-10 w-10 rounded-full object-cover"
                 :src="$page.props.auth.user.profile_photo_url"
                 :alt="$page.props.auth.user.name">
        </div>

        <div>
            <div class="font-medium text-base text-gray-800">
                {{ $page.props.auth.user.name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
                {{ $page.props.auth.user.email }}
            </div>
        </div>
    </div>

    <ResponsiveNavLink :href="route('user.settings')"
                       :active="route().current('user.settings')">
        Settings
    </ResponsiveNavLink>

    <ResponsiveNavLink :href="route('profile.show')"
                       :active="route().current('profile.show')">
        Profile
    </ResponsiveNavLink>

    <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures"
                       :href="route('api-tokens.index')"
                       :active="route().current('api-tokens.index')">
        API Tokens
    </ResponsiveNavLink>

    <ResponsiveNavLink :href="route('webhooks.index')"
                       :active="route().current('webhooks.index')">
        Webhooks
    </ResponsiveNavLink>

    <!-- Authentication -->
    <form method="POST" @submit.prevent="logout">
        <ResponsiveNavLink as="button">
            Log Out
        </ResponsiveNavLink>
    </form>

    <!-- Team Management -->
    <template v-if="$page.props.jetstream.hasTeamFeatures">
        <div class="border-t border-gray-200"/>

        <div class="block px-4 py-2 text-xs text-gray-400">
            Manage Team
        </div>

        <!-- Team Settings -->
        <ResponsiveNavLink
            :href="route('teams.show', $page.props.auth.user.current_team)"
            :active="route().current('teams.show')">
            Team Settings
        </ResponsiveNavLink>

        <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams"
                           :href="route('teams.create')"
                           :active="route().current('teams.create')">
            Create New Team
        </ResponsiveNavLink>

        <!-- Team Switcher -->
        <template v-if="$page.props.auth.user.all_teams.length > 1">
            <div class="border-t border-gray-200"/>

            <div class="block px-4 py-2 text-xs text-gray-400">
                Switch Teams
            </div>

            <template v-for="team in $page.props.auth.user.all_teams"
                      :key="team.id">
                <form @submit.prevent="switchToTeam(team)">
                    <ResponsiveNavLink as="button">
                        <div class="flex items-center">
                            <svg
                                v-if="team.id == $page.props.auth.user.current_team_id"
                                class="me-2 h-5 w-5 text-green-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>{{ team.name }}</div>
                        </div>
                    </ResponsiveNavLink>
                </form>
            </template>
        </template>
    </template>
</template>

<style>
.v-navigation-drawer--left {
    border-right-width: 0;
}

table {
    width: 100%;
}
</style>
