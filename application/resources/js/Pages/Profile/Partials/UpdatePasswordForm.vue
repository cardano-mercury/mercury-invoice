<template>
    <v-card rounded="lg">
        <v-card-title class="d-flex align-center">
            <v-icon icon="mdi-lock" color="primary" class="mr-2" />
            Update Password
        </v-card-title>
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
                    prepend-inner-icon="mdi-lock-outline"
                    :error-messages="form.errors.current_password"
                    variant="outlined"
                    density="comfortable"
                    class="mb-2"
                />

                <v-text-field
                    v-model="form.password"
                    label="New Password"
                    type="password"
                    autocomplete="new-password"
                    prepend-inner-icon="mdi-lock-plus"
                    :error-messages="form.errors.password"
                    variant="outlined"
                    density="comfortable"
                    class="mb-2"
                />

                <v-text-field
                    v-model="form.password_confirmation"
                    label="Confirm New Password"
                    type="password"
                    autocomplete="new-password"
                    prepend-inner-icon="mdi-lock-check"
                    :error-messages="form.errors.password_confirmation"
                    variant="outlined"
                    density="comfortable"
                />

                <div class="d-flex justify-end mt-6">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Password updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        type="submit"
                        variant="flat"
                        prepend-icon="mdi-content-save"
                    >
                        Update Password
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
