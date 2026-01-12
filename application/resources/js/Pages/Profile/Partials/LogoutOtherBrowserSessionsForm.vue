<template>
    <v-card rounded="lg">
        <v-card-title class="d-flex align-center">
            <v-icon icon="mdi-devices" color="primary" class="mr-2" />
            Browser Sessions
        </v-card-title>
        <v-card-subtitle>
            Manage and log out your active sessions on other browsers and devices.
        </v-card-subtitle>

        <v-card-text>
            <p class="text-body-2 text-medium-emphasis mb-4">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </p>

            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="mb-4">
                <v-list density="compact" class="bg-transparent">
                    <v-list-item
                        v-for="(session, i) in sessions"
                        :key="i"
                        class="px-0"
                    >
                        <template #prepend>
                            <v-avatar color="primary" variant="tonal" size="40" class="mr-3">
                                <v-icon 
                                    :icon="session.agent.is_desktop ? 'mdi-desktop-mac' : 'mdi-cellphone'" 
                                    size="small"
                                />
                            </v-avatar>
                        </template>

                        <v-list-item-title class="font-weight-medium">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </v-list-item-title>

                        <v-list-item-subtitle>
                            <span class="font-mono">{{ session.ip_address }}</span>
                            <span class="mx-1">•</span>
                            <v-chip
                                v-if="session.is_current_device"
                                color="success"
                                size="x-small"
                                variant="tonal"
                            >
                                This device
                            </v-chip>
                            <span v-else class="text-medium-emphasis">Last active {{ session.last_active }}</span>
                        </v-list-item-subtitle>
                    </v-list-item>
                </v-list>
            </div>

            <div class="d-flex justify-end">
                <v-snackbar
                    v-model="showSuccessMessage"
                    color="success"
                    timeout="3000"
                >
                    Other browser sessions have been logged out.
                </v-snackbar>
                
                <v-btn
                    color="error"
                    variant="tonal"
                    prepend-icon="mdi-logout-variant"
                    @click="confirmLogout"
                >
                    Log Out Other Sessions
                </v-btn>
            </div>

            <!-- Logout Other Devices Confirmation Modal -->
            <v-dialog v-model="confirmingLogout" max-width="440" persistent>
                <v-card rounded="lg">
                    <v-card-title class="d-flex align-center">
                        <v-icon icon="mdi-logout-variant" color="error" class="mr-2" />
                        Log Out Other Sessions
                    </v-card-title>
                    <v-card-text>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.
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
                            @keyup.enter="logoutOtherBrowserSessions"
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
                            prepend-icon="mdi-logout-variant"
                            :loading="form.processing"
                            @click="logoutOtherBrowserSessions"
                        >
                            Log Out Sessions
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

<style scoped>
.font-mono {
    font-family: 'Source Code Pro', monospace;
}
</style>
