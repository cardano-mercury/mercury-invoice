<template>
    <v-card class="mb-6">
        <v-card-title>Browser Sessions</v-card-title>
        <v-card-subtitle>
            Manage and log out your active sessions on other browsers and devices.
        </v-card-subtitle>

        <v-card-text>
            <div class="max-w-xl text-sm text-gray-600">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </div>

            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="mt-5 space-y-6">
                <div v-for="(session, i) in sessions" :key="i" class="d-flex items-center">
                    <div>
                        <v-icon v-if="session.agent.is_desktop" icon="mdi-desktop-mac" class="mr-2"></v-icon>
                        <v-icon v-else icon="mdi-cellphone" class="mr-2"></v-icon>
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span v-if="session.is_current_device" class="text-green-500 font-semibold">This device</span>
                                <span v-else>Last active {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-end mt-5">
                <v-snackbar
                    v-model="showSuccessMessage"
                    color="success"
                    timeout="3000"
                >
                    Other browser sessions have been logged out.
                </v-snackbar>
                
                <v-btn
                    color="error"
                    variant="flat"
                    prepend-icon="mdi-logout-variant"
                    @click="confirmLogout"
                >
                    Log Out Other Browser Sessions
                </v-btn>
            </div>

            <!-- Logout Other Devices Confirmation Modal -->
            <v-dialog v-model="confirmingLogout" max-width="500px">
                <v-card>
                    <v-card-title>Log Out Other Browser Sessions</v-card-title>
                    <v-card-text>
                        <p>Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.</p>
                        
                        <v-text-field
                            v-model="form.password"
                            label="Password"
                            type="password"
                            class="mt-4"
                            :error-messages="form.errors.password"
                            @keyup.enter="logoutOtherBrowserSessions"
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
                            prepend-icon="mdi-logout-variant"
                            :loading="form.processing"
                            @click="logoutOtherBrowserSessions"
                        >
                            Log Out Other Browser Sessions
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
    props: ['sessions'],

    data() {
        return {
            confirmingLogout: false,
            form: useForm({
                password: '',
            }),
            showSuccessMessage: false,
        };
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true;
        },

        logoutOtherBrowserSessions() {
            this.form.delete(route('other-browser-sessions.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal();
                    this.showSuccessMessage = true;
                },
                onError: () => this.$refs.password.focus(),
            });
        },

        closeModal() {
            this.confirmingLogout = false;
            this.form.reset();
        },
    },
});
</script>
