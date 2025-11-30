<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <PageHeader 
                title="Profile Settings" 
                subtitle="Manage your account settings, security, and preferences"
                icon="mdi-account-cog"
            />
        </template>

        <v-container>
            <v-row>
                <v-col cols="12" lg="8">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />

                    <UpdatePasswordForm class="mt-6" />

                    <TwoFactorAuthenticationForm class="mt-6" />

                    <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-6" />

                    <DeleteUserForm
                        v-if="$page.props.jetstream.hasAccountDeletionFeatures"
                        class="mt-6"
                    />
                </v-col>
            </v-row>
        </v-container>
    </AppLayout>
</template>
