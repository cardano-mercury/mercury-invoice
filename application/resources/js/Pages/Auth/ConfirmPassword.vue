<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

const form = useForm({
    password: '',
});

const passwordInput = ref(null);

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
            passwordInput.value.focus();
        },
    });
};
</script>

<template>
    <GuestLayout title="Secure Area">
        <v-container class="fill-height d-flex flex-column justify-center align-center py-12">
            <v-card width="100%" max-width="440" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0 text-center">
                    <v-avatar color="warning" size="64" class="mb-4">
                        <v-icon icon="mdi-shield-lock" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Secure Area</h1>
                    <p class="text-body-2 text-medium-emphasis mb-4">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>
                </v-card-text>

                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field 
                            id="password" 
                            v-model="form.password"
                            ref="passwordInput"
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
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat"
                            size="large" 
                            block 
                            rounded="lg"
                            prepend-icon="mdi-lock-check"
                            :loading="form.processing"
                            class="mt-2"
                        >
                            Confirm
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
