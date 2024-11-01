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
        <v-container
            class="fill-height d-flex flex-column justify-center text-center align-center">
            <v-card width="512" elevation="1" class="px-8">
                <v-card-text>
                    <div class="text-h4 text-primary font-weight-black">Sign Up
                    </div>
                    <div class="text-center text-body-2 mb-4">
                        Fill in the details to create an account
                    </div>
                </v-card-text>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field id="name" v-model="form.name" type="text"
                                      required autofocus autocomplete="name"
                                      label="Name..."
                                      prepend-icon="mdi-account"
                                      :error-messages="form.errors.name"/>
                        <v-text-field id="email" v-model="form.email"
                                      type="email" required autofocus
                                      autocomplete="username"
                                      label="Email..."
                                      prepend-icon="mdi-email-outline"
                                      :error-messages="form.errors.email"/>
                        <v-text-field id="password" v-model="form.password"
                                      type="password" required
                                      autocomplete="new-password"
                                      prepend-icon="mdi-lock-outline"
                                      label="Password..."
                                      :error-messages="form.errors.password"/>
                        <v-text-field id="password_confirmation"
                                      v-model="form.password_confirmation"
                                      type="password" required
                                      autocomplete="new-password"
                                      prepend-icon="mdi-lock-outline"
                                      label="Confirm password..."
                                      :error-messages="form.errors.password_confirmation"/>
                        <v-checkbox v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" v-model="form.terms"
                                    name="terms"
                                    required
                                    :error-messages="form.errors.terms">
                            <template v-slot:label>
                                I agree to the&nbsp;
                                <Link target="_blank" :href="route('terms.show')">
                                    Terms of Service
                                </Link>&nbsp;and&nbsp;
                                <Link target="_blank" :href="route('policy.show')">
                                    Privacy Policy
                                </Link>
                            </template>
                        </v-checkbox>
                        <v-btn type="submit" color="black" variant="flat"
                               size="large" block>Sign Up
                        </v-btn>
                    </v-form>
                </v-card-text>
                <v-card-text class="my-8">
                    Already registered?
                    <Link :href="route('login')" class="font-weight-black">
                        Sign In!
                    </Link>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
