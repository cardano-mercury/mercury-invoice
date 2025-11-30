<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout title="Log In">
        <v-container class="fill-height d-flex flex-column justify-center text-center align-center py-12">
            <v-card width="100%" max-width="440" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0">
                    <v-avatar color="primary" size="64" class="mb-4">
                        <v-icon icon="mdi-login" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Welcome Back</h1>
                    <p class="text-body-2 text-medium-emphasis mb-6">
                        Sign in to continue to Mercury
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
                            class="mb-2"
                        />
                        <v-text-field 
                            id="password" 
                            v-model="form.password"
                            type="password" 
                            required
                            autocomplete="current-password"
                            prepend-inner-icon="mdi-lock-outline"
                            label="Password"
                            placeholder="Enter your password"
                            :error-messages="form.errors.password"
                            variant="outlined"
                            density="comfortable"
                        />
                        <div class="d-flex align-center justify-space-between mb-4">
                            <v-checkbox 
                                v-model="form.remember" 
                                label="Remember me"
                                color="primary"
                                density="compact"
                                hide-details
                            />
                            <v-btn 
                                color="primary" 
                                variant="text" 
                                size="small"
                                :href="route('password.request')"
                            >
                                Forgot password?
                            </v-btn>
                        </div>
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat" 
                            block
                            size="large" 
                            rounded="lg"
                            prepend-icon="mdi-login"
                            :loading="form.processing"
                        >
                            Sign In
                        </v-btn>
                    </v-form>
                </v-card-text>

                <v-divider class="my-4" />

                <v-card-text class="pt-0 text-center">
                    <span class="text-medium-emphasis">Don't have an account?</span>
                    <v-btn 
                        :href="route('register')" 
                        color="primary" 
                        variant="text"
                        class="font-weight-bold ml-1"
                    >
                        Sign Up
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
