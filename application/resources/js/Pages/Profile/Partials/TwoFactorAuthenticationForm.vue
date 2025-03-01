<template>
    <v-card class="mb-6">
        <v-card-title>Two Factor Authentication</v-card-title>
        <v-card-subtitle>
            Add additional security to your account using two factor authentication.
        </v-card-subtitle>

        <v-card-text>
            <h3 v-if="twoFactorEnabled" class="text-lg font-medium text-gray-900">
                You have enabled two factor authentication.
            </h3>

            <h3 v-else class="text-lg font-medium text-gray-900">
                You have not enabled two factor authentication.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                        </p>
                    </div>

                    <div class="mt-4" v-html="qrCode"></div>
                </div>

                <div v-if="recoveryCodes.length > 0">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <v-snackbar
                    v-model="showSuccessMessage"
                    color="success"
                    timeout="3000"
                >
                    {{ successMessage }}
                </v-snackbar>
                
                <div v-if="!twoFactorEnabled">
                    <v-btn
                        color="primary"
                        variant="flat"
                        prepend-icon="mdi-shield-check"
                        @click="confirmEnableTwoFactorAuthentication"
                    >
                        Enable
                    </v-btn>
                </div>
                <div v-else>
                    <v-btn
                        v-if="recoveryCodes.length > 0"
                        color="secondary"
                        variant="flat"
                        prepend-icon="mdi-refresh"
                        class="mr-3"
                        @click="confirmRegenerateRecoveryCodes"
                    >
                        Regenerate Recovery Codes
                    </v-btn>

                    <v-btn
                        v-if="recoveryCodes.length === 0"
                        color="secondary"
                        variant="flat"
                        prepend-icon="mdi-eye"
                        class="mr-3"
                        @click="showRecoveryCodes"
                    >
                        Show Recovery Codes
                    </v-btn>

                    <v-btn
                        color="error"
                        variant="flat"
                        prepend-icon="mdi-shield-off"
                        @click="confirmDisableTwoFactorAuthentication"
                    >
                        Disable
                    </v-btn>
                </div>
            </div>

            <!-- Password Confirmation Modal -->
            <v-dialog v-model="confirmingPassword" max-width="500px">
                <v-card>
                    <v-card-title>{{ confirmPasswordTitle }}</v-card-title>
                    <v-card-text>
                        <p>For your security, please confirm your password to continue.</p>
                        
                        <v-text-field
                            v-model="form.password"
                            label="Password"
                            type="password"
                            class="mt-4"
                            :error-messages="form.errors.password"
                            @keyup.enter="confirmPassword"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="secondary"
                            variant="flat"
                            prepend-icon="mdi-close"
                            class="mr-2"
                            @click="closeConfirmationModal"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="primary"
                            variant="flat"
                            prepend-icon="mdi-check"
                            :loading="form.processing"
                            @click="confirmPassword"
                        >
                            Confirm
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card-text>
    </v-card>
</template>

<script>
import { defineComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default defineComponent({
    data() {
        return {
            enabling: false,
            disabling: false,
            qrCode: null,
            recoveryCodes: [],
            showSuccessMessage: false,
            successMessage: '',
            confirmingPassword: false,
            confirmPasswordTitle: '',
            confirmPasswordAction: null,
            form: useForm({
                password: '',
            }),
        };
    },

    computed: {
        twoFactorEnabled() {
            return !this.enabling && this.$page.props.auth.user?.two_factor_enabled;
        },
    },

    methods: {
        confirmEnableTwoFactorAuthentication() {
            this.confirmPasswordTitle = 'Enable Two Factor Authentication';
            this.confirmPasswordAction = this.enableTwoFactorAuthentication;
            this.confirmingPassword = true;
        },

        confirmRegenerateRecoveryCodes() {
            this.confirmPasswordTitle = 'Regenerate Recovery Codes';
            this.confirmPasswordAction = this.regenerateRecoveryCodes;
            this.confirmingPassword = true;
        },

        confirmDisableTwoFactorAuthentication() {
            this.confirmPasswordTitle = 'Disable Two Factor Authentication';
            this.confirmPasswordAction = this.disableTwoFactorAuthentication;
            this.confirmingPassword = true;
        },

        confirmPassword() {
            this.$inertia.post(route('password.confirm'), {
                password: this.form.password,
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeConfirmationModal();
                    this.confirmPasswordAction();
                },
                onError: (errors) => {
                    this.form.errors = errors;
                },
            });
        },

        closeConfirmationModal() {
            this.confirmingPassword = false;
            this.form.reset();
        },

        enableTwoFactorAuthentication() {
            this.enabling = true;

            this.$inertia.post('/user/two-factor-authentication', {}, {
                preserveScroll: true,
                onSuccess: () => Promise.all([
                    this.showQrCode(),
                    this.showRecoveryCodes(),
                ]).then(() => {
                    this.enabling = false;
                    this.successMessage = 'Two factor authentication has been enabled.';
                    this.showSuccessMessage = true;
                }),
                onError: (errors) => {
                    this.enabling = false;
                    console.error(errors);
                }
            });
        },

        showQrCode() {
            return axios.get('/user/two-factor-qr-code').then(response => {
                this.qrCode = response.data.svg;
            });
        },

        showRecoveryCodes() {
            return axios.get('/user/two-factor-recovery-codes').then(response => {
                this.recoveryCodes = response.data;
            });
        },

        regenerateRecoveryCodes() {
            axios.post('/user/two-factor-recovery-codes').then(() => {
                this.showRecoveryCodes();
                this.successMessage = 'Recovery codes have been regenerated.';
                this.showSuccessMessage = true;
            });
        },

        disableTwoFactorAuthentication() {
            this.disabling = true;

            this.$inertia.delete('/user/two-factor-authentication', {
                preserveScroll: true,
                onSuccess: () => {
                    this.disabling = false;
                    this.qrCode = null;
                    this.recoveryCodes = [];
                    this.successMessage = 'Two factor authentication has been disabled.';
                    this.showSuccessMessage = true;
                },
                onError: (errors) => {
                    this.disabling = false;
                    console.error(errors);
                }
            });
        },
    },
});
</script>
