<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

export default {
    props: ['sessions'],
    
    components: {
        AppLayout,
        DeleteUserForm,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
};
</script>

<template>
    <app-layout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>

        <v-container>
            <v-row>
                <v-col cols="12" md="8">
                    <update-profile-information-form
                        :user="$page.props.auth.user"
                    />

                    <update-password-form class="mt-10 sm:mt-0" />

                    <two-factor-authentication-form class="mt-10 sm:mt-0" />

                    <logout-other-browser-sessions-form
                        :sessions="sessions"
                        class="mt-10 sm:mt-0"
                    />

                    <delete-user-form
                        v-if="$page.props.jetstream.hasAccountDeletionFeatures"
                        class="mt-10 sm:mt-0"
                    />
                </v-col>
            </v-row>
        </v-container>
    </app-layout>
</template>
