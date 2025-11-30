<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout title="Register">
        <v-container class="fill-height d-flex flex-column justify-center text-center align-center py-12">
            <v-card width="100%" max-width="480" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0">
                    <v-avatar color="primary" size="64" class="mb-4">
                        <v-icon icon="mdi-account-plus" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Create Account</h1>
                    <p class="text-body-2 text-medium-emphasis mb-6">
                        Start your journey with Mercury
                    </p>
                </v-card-text>

                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field 
                            id="name" 
                            v-model="form.name" 
                            type="text"
                            required 
                            autofocus 
                            autocomplete="name"
                            label="Full Name"
                            placeholder="John Doe"
                            prepend-inner-icon="mdi-account-outline"
                            :error-messages="form.errors.name"
                            variant="outlined"
                            density="comfortable"
                            class="mb-2"
                        />
                        <v-text-field 
                            id="email" 
                            v-model="form.email"
                            type="email" 
                            required
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
                            autocomplete="new-password"
                            prepend-inner-icon="mdi-lock-outline"
                            label="Password"
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
                            label="Confirm Password"
                            placeholder="Confirm your password"
                            :error-messages="form.errors.password_confirmation"
                            variant="outlined"
                            density="comfortable"
                            class="mb-2"
                        />
                        <v-checkbox 
                            v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" 
                            v-model="form.terms"
                            name="terms"
                            required
                            color="primary"
                            :error-messages="form.errors.terms"
                            class="mb-2"
                        >
                            <template #label>
                                <span class="text-body-2">
                                    I agree to the
                                    <Link 
                                        target="_blank" 
                                        :href="route('terms.show')"
                                        class="text-primary font-weight-medium"
                                    >
                                        Terms of Service
                                    </Link>
                                    and
                                    <Link 
                                        target="_blank" 
                                        :href="route('policy.show')"
                                        class="text-primary font-weight-medium"
                                    >
                                        Privacy Policy
                                    </Link>
                                </span>
                            </template>
                        </v-checkbox>
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat"
                            size="large" 
                            block 
                            rounded="lg"
                            prepend-icon="mdi-account-plus"
                            :loading="form.processing"
                        >
                            Create Account
                        </v-btn>
                    </v-form>
                </v-card-text>

                <v-divider class="my-4" />

                <v-card-text class="pt-0 text-center">
                    <span class="text-medium-emphasis">Already have an account?</span>
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
