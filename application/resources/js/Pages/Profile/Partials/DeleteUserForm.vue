<script>
import { defineComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

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
    <v-card>
        <v-card-title class="bg-error text-white">Delete Account</v-card-title>
        <v-card-subtitle class="pt-4">
            Permanently delete your account.
        </v-card-subtitle>

        <v-card-text>
            <div class="max-w-xl text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <v-btn
                    color="error"
                    variant="flat"
                    prepend-icon="mdi-delete"
                    @click="confirmUserDeletion"
                >
                    Delete Account
                </v-btn>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <v-dialog v-model="confirmingUserDeletion" max-width="500px">
                <v-card>
                    <v-card-title>Delete Account</v-card-title>
                    <v-card-text>
                        <p>Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        
                        <v-text-field
                            v-model="form.password"
                            label="Password"
                            type="password"
                            class="mt-4"
                            :error-messages="form.errors.password"
                            @keyup.enter="deleteUser"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="secondary"
                            variant="flat"
                            prepend-icon="mdi-close"
                            class="mr-2"
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
                            Delete Account
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card-text>
    </v-card>
</template>
