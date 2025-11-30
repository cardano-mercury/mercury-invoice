<script setup>
import {computed} from 'vue';
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout title="Email Verification">
        <v-container class="fill-height d-flex flex-column justify-center align-center py-12">
            <v-card width="100%" max-width="480" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0 text-center">
                    <v-avatar color="info" size="64" class="mb-4">
                        <v-icon icon="mdi-email-check" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Verify Your Email</h1>
                    <p class="text-body-2 text-medium-emphasis mb-4">
                        Before continuing, please verify your email address by clicking the link we sent you. 
                        If you didn't receive the email, we can send another.
                    </p>
                </v-card-text>

                <v-card-text v-if="verificationLinkSent" class="pb-0">
                    <v-alert 
                        type="success" 
                        variant="tonal" 
                        density="compact"
                        class="mb-4"
                    >
                        <v-icon start icon="mdi-check-circle" />
                        A new verification link has been sent to your email address.
                    </v-alert>
                </v-card-text>

                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat"
                            size="large" 
                            block 
                            rounded="lg"
                            prepend-icon="mdi-email-send"
                            :disabled="form.processing" 
                            :loading="form.processing"
                        >
                            Resend Verification Email
                        </v-btn>
                    </v-form>
                </v-card-text>

                <v-divider class="my-4" />

                <v-card-text class="pt-0">
                    <v-row dense>
                        <v-col cols="12" sm="6">
                            <v-btn 
                                :href="route('profile.show')" 
                                color="secondary" 
                                variant="text"
                                prepend-icon="mdi-account-edit"
                                block
                            >
                                Edit Profile
                            </v-btn>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <Link :href="route('logout')" method="post" class="d-block">
                                <v-btn 
                                    color="error" 
                                    variant="text"
                                    prepend-icon="mdi-logout"
                                    block
                                >
                                    Log Out
                                </v-btn>
                            </Link>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
