<script>
import { defineComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
    data() {
        return {
            confirmingUserDeletion: false,
            form: useForm({
                password: '',
            }),
        };
    },

    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;
        },

        deleteUser() {
            this.form.delete(route('current-user.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
            });
        },

        closeModal() {
            this.confirmingUserDeletion = false;
            this.form.reset();
        },
    },
});
</script>

<template>
    <v-card rounded="lg" variant="outlined" class="border-error">
        <v-card-title class="d-flex align-center text-error">
            <v-icon icon="mdi-alert-circle" color="error" class="mr-2" />
            Delete Account
        </v-card-title>
        <v-card-subtitle>
            Permanently delete your account and all associated data.
        </v-card-subtitle>

        <v-card-text>
            <v-alert
                type="error"
                variant="tonal"
                density="compact"
                class="mb-4"
            >
                <strong>Warning:</strong> Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </v-alert>

            <v-btn
                color="error"
                variant="flat"
                prepend-icon="mdi-delete"
                @click="confirmUserDeletion"
            >
                Delete Account
            </v-btn>

            <!-- Delete Account Confirmation Modal -->
            <v-dialog v-model="confirmingUserDeletion" max-width="440" persistent>
                <v-card rounded="lg">
                    <v-card-title class="d-flex align-center text-error">
                        <v-icon icon="mdi-alert-circle" color="error" class="mr-2" />
                        Delete Account
                    </v-card-title>
                    <v-card-text>
                        <v-alert
                            type="error"
                            variant="tonal"
                            density="compact"
                            class="mb-4"
                        >
                            This action cannot be undone. All your data will be permanently deleted.
                        </v-alert>

                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>
                        
                        <v-text-field
                            v-model="form.password"
                            label="Password"
                            type="password"
                            prepend-inner-icon="mdi-lock"
                            :error-messages="form.errors.password"
                            variant="outlined"
                            density="comfortable"
                            autofocus
                            @keyup.enter="deleteUser"
                        />
                    </v-card-text>
                    <v-card-actions class="pa-4">
                        <v-spacer />
                        <v-btn
                            variant="text"
                            prepend-icon="mdi-close"
                            @click="closeModal"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="error"
                            variant="flat"
                            prepend-icon="mdi-delete-forever"
                            :loading="form.processing"
                            @click="deleteUser"
                        >
                            Delete Forever
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card-text>
    </v-card>
</template>

<style scoped>
.border-error {
    border-color: rgb(var(--v-theme-error)) !important;
}
</style>
