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
        <v-container
            class="fill-height d-flex flex-column justify-center text-center align-center">
            <v-card width="512" elevation="1" class="px-8">
                <v-card-text>
                    <div class="text-h4 text-primary font-weight-black">Sign In
                        to Mercury
                    </div>
                    <div class="text-center text-body-2 mb-4">Experience the
                        Future of Crypto Payments
                    </div>
                </v-card-text>
                <v-card-text v-if="status">
                    <v-alert icon="$success" border color="primary">
                        {{status}}
                    </v-alert>
                </v-card-text>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field id="email" v-model="form.email"
                                      type="email" required autofocus
                                      autocomplete="username"
                                      label="Type your email..."
                                      prepend-icon="mdi-email-outline"
                                      :error-messages="form.errors.email"/>
                        <v-text-field id="password" v-model="form.password"
                                      type="password" required
                                      autocomplete="current-password"
                                      prepend-icon="mdi-lock-outline"
                                      label="Type your password..."
                                      :error-messages="form.errors.password"/>
                        <div class="d-flex align-center justify-space-between">
                            <v-checkbox v-model="form.remember" label="Remember me"></v-checkbox>
                            <v-btn color="secondary" variant="text" class="mr-2"
                                   prepend-icon="mdi-lock-reset"
                                   :href="route('password.request')">
                                Forgot your password?
                            </v-btn>
                        </div>
                        <v-btn type="submit" color="primary" variant="flat" block
                               size="large" class="mt-4"
                               prepend-icon="mdi-login"
                               :loading="form.processing">Log in
                        </v-btn>
                    </v-form>
                </v-card-text>
                <v-card-text class="my-8">
                    Don't have an account?
                    <v-btn :href="route('register')" 
                          color="secondary" 
                          variant="text"
                          prepend-icon="mdi-account-plus"
                          class="font-weight-black">
                        Sign Up!
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
