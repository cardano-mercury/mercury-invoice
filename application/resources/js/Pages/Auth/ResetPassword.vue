<script setup>
import {useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout title="Reset Password">
        <v-container class="fill-height d-flex flex-column justify-center align-center py-12">
            <v-card width="100%" max-width="440" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0 text-center">
                    <v-avatar color="primary" size="64" class="mb-4">
                        <v-icon icon="mdi-lock-reset" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Reset Password</h1>
                    <p class="text-body-2 text-medium-emphasis mb-4">
                        Enter your new password below
                    </p>
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
                            autocomplete="new-password"
                            prepend-inner-icon="mdi-lock-outline"
                            label="New Password"
                            placeholder="Create a strong password"
                            :error-messages="form.errors.password"
                            variant="outlined"
                            density="comfortable"
                            class="mb-2"
                        />
                        <v-text-field 
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password" 
                            required
                            autocomplete="new-password"
                            prepend-inner-icon="mdi-lock-check-outline"
                            label="Confirm New Password"
                            placeholder="Confirm your password"
                            :error-messages="form.errors.password_confirmation"
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
                            prepend-icon="mdi-lock-reset"
                            :disabled="form.processing"
                            :loading="form.processing"
                            class="mt-4"
                        >
                            Reset Password
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
