<template>
    <v-card class="mb-6">
        <v-card-title>Update Password</v-card-title>
        <v-card-subtitle>
            Ensure your account is using a long, random password to stay secure.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="updatePassword">
                <v-text-field
                    v-model="form.current_password"
                    label="Current Password"
                    type="password"
                    autocomplete="current-password"
                    :error-messages="form.errors.current_password"
                ></v-text-field>

                <v-text-field
                    v-model="form.password"
                    label="New Password"
                    type="password"
                    autocomplete="new-password"
                    :error-messages="form.errors.password"
                ></v-text-field>

                <v-text-field
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    type="password"
                    autocomplete="new-password"
                    :error-messages="form.errors.password_confirmation"
                ></v-text-field>

                <div class="d-flex justify-end mt-4">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Password updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="success"
                        :loading="form.processing"
                        type="submit"
                    >
                        Save
                    </v-btn>
                </div>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
import { defineComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
    data() {
        return {
            form: useForm({
                current_password: '',
                password: '',
                password_confirmation: '',
            }),
            showSuccessMessage: false,
        };
    },

    methods: {
        updatePassword() {
            this.form.put(route('user-password.update'), {
                errorBag: 'updatePassword',
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.showSuccessMessage = true;
                },
            });
        },
    },
});
</script>
