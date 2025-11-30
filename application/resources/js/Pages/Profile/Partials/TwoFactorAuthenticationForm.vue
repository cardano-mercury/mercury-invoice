<template>
    <v-card rounded="lg">
        <v-card-title class="d-flex align-center">
            <v-icon icon="mdi-two-factor-authentication" color="primary" class="mr-2" />
            Two Factor Authentication
        </v-card-title>
        <v-card-subtitle>
            Add additional security to your account using two factor authentication.
        </v-card-subtitle>

        <v-card-text>
            <v-alert
                v-if="twoFactorEnabled"
                type="success"
                variant="tonal"
                density="compact"
                class="mb-4"
            >
                <v-icon start icon="mdi-shield-check" />
                Two factor authentication is enabled
            </v-alert>

            <v-alert
                v-else
                type="warning"
                variant="tonal"
                density="compact"
                class="mb-4"
            >
                <v-icon start icon="mdi-shield-off" />
                Two factor authentication is not enabled
            </v-alert>

            <p class="text-body-2 text-medium-emphasis mb-4">
                When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
            </p>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <v-alert type="info" variant="tonal" density="compact" class="mb-4">
                        <strong>Scan this QR code</strong> using your phone's authenticator application.
                    </v-alert>

                    <v-card variant="outlined" class="pa-4 mb-4 d-inline-block">
                        <div v-html="qrCode"></div>
                    </v-card>
                </div>

                <div v-if="recoveryCodes.length > 0">
                    <v-alert type="warning" variant="tonal" density="compact" class="mb-4">
                        <strong>Save these recovery codes</strong> in a secure password manager. They can be used to recover access if your device is lost.
                    </v-alert>

                    <v-card variant="outlined" class="pa-4 mb-4">
                        <div class="d-flex flex-wrap ga-2">
                            <v-chip
                                v-for="code in recoveryCodes"
                                :key="code"
                                variant="tonal"
                                color="primary"
                                class="font-mono"
                            >
                                {{ code }}
                            </v-chip>
                        </div>
                    </v-card>
                </div>
            </div>

            <div class="d-flex flex-wrap ga-2 mt-4">
                <v-snackbar
                    v-model="showSuccessMessage"
                    color="success"
                    timeout="3000"
                >
                    {{ successMessage }}
                </v-snackbar>
                
                <template v-if="!twoFactorEnabled">
                    <v-btn
                        color="primary"
                        variant="flat"
                        prepend-icon="mdi-shield-check"
                        @click="confirmEnableTwoFactorAuthentication"
                    >
                        Enable 2FA
                    </v-btn>
                </template>
                <template v-else>
                    <v-btn
                        v-if="recoveryCodes.length > 0"
                        color="secondary"
                        variant="tonal"
                        prepend-icon="mdi-refresh"
                        @click="confirmRegenerateRecoveryCodes"
                    >
                        Regenerate Codes
                    </v-btn>

                    <v-btn
                        v-if="recoveryCodes.length === 0"
                        color="secondary"
                        variant="tonal"
                        prepend-icon="mdi-eye"
                        @click="showRecoveryCodes"
                    >
                        Show Recovery Codes
                    </v-btn>

                    <v-btn
                        color="error"
                        variant="tonal"
                        prepend-icon="mdi-shield-off"
                        @click="confirmDisableTwoFactorAuthentication"
                    >
                        Disable 2FA
                    </v-btn>
                </template>
            </div>

            <!-- Password Confirmation Modal -->
            <v-dialog v-model="confirmingPassword" max-width="440" persistent>
                <v-card rounded="lg">
                    <v-card-title class="d-flex align-center">
                        <v-icon icon="mdi-lock" color="primary" class="mr-2" />
                        {{ confirmPasswordTitle }}
                    </v-card-title>
                    <v-card-text>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            For your security, please confirm your password to continue.
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
                            @keyup.enter="confirmPassword"
                        />
                    </v-card-text>
                    <v-card-actions class="pa-4">
                        <v-spacer />
                        <v-btn
                            variant="text"
                            prepend-icon="mdi-close"
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

<style scoped>
.font-mono {
    font-family: 'Source Code Pro', monospace;
}
</style>
