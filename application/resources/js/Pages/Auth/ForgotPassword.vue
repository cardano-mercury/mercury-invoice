<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout title="Forgot Password">
        <v-container class="fill-height d-flex flex-column justify-center align-center py-12">
            <v-card width="100%" max-width="440" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0 text-center">
                    <v-avatar color="primary" size="64" class="mb-4">
                        <v-icon icon="mdi-lock-reset" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Forgot Password?</h1>
                    <p class="text-body-2 text-medium-emphasis mb-4">
                        No problem. Enter your email address and we'll send you a password reset link.
                    </p>
                </v-card-text>

                <v-card-text v-if="status" class="pb-0">
                    <v-alert 
                        type="success" 
                        variant="tonal" 
                        density="compact"
                        class="mb-4"
                    >
                        {{ status }}
                    </v-alert>
                </v-card-text>

                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field 
                            id="email" 
                            v-model="form.email"
                            type="email" 
                            required 
                            autofocus
                            autocomplete="username"
                            label="Email Address"
                            placeholder="you@example.com"
                            prepend-inner-icon="mdi-email-outline"
                            :error-messages="form.errors.email"
                            variant="outlined"
                            density="comfortable"
                        />
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat"
                            size="large" 
                            block 
                            rounded="lg"
                            prepend-icon="mdi-email-send"
                            :loading="form.processing"
                            class="mt-2"
                        >
                            Send Reset Link
                        </v-btn>
                    </v-form>
                </v-card-text>

                <v-divider class="my-4" />

                <v-card-text class="pt-0 text-center">
                    <span class="text-medium-emphasis">Remember your password?</span>
                    <v-btn 
                        :href="route('login')" 
                        color="primary" 
                        variant="text"
                        class="font-weight-bold ml-1"
                    >
                        Sign In
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
