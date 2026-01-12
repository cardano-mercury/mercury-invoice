<script setup>
import {nextTick, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = '';
    } else {
        codeInput.value.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <GuestLayout title="Two-factor Confirmation">
        <v-container class="fill-height d-flex flex-column justify-center align-center py-12">
            <v-card width="100%" max-width="440" class="pa-6" rounded="xl" elevation="4">
                <v-card-text class="pb-0 text-center">
                    <v-avatar color="primary" size="64" class="mb-4">
                        <v-icon icon="mdi-two-factor-authentication" size="32" />
                    </v-avatar>
                    <h1 class="text-h5 font-weight-bold mb-1">Two-Factor Authentication</h1>
                    <p class="text-body-2 text-medium-emphasis mb-4">
                        <template v-if="!recovery">
                            Enter the authentication code from your authenticator app.
                        </template>
                        <template v-else>
                            Enter one of your emergency recovery codes.
                        </template>
                    </p>
                </v-card-text>

                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <template v-if="!recovery">
                            <v-text-field 
                                id="code" 
                                ref="codeInput"
                                v-model="form.code" 
                                type="text"
                                inputmode="numeric" 
                                autofocus
                                autocomplete="one-time-code"
                                label="Authentication Code"
                                placeholder="000000"
                                prepend-inner-icon="mdi-cellphone-key"
                                :error-messages="form.errors.code"
                                variant="outlined"
                                density="comfortable"
                            />
                        </template>
                        <template v-else>
                            <v-text-field 
                                id="recovery_code"
                                ref="recoveryCodeInput"
                                v-model="form.recovery_code"
                                type="text"
                                autofocus
                                autocomplete="one-time-code"
                                label="Recovery Code"
                                placeholder="xxxxxxxx-xxxxxxxx"
                                prepend-inner-icon="mdi-key"
                                :error-messages="form.errors.recovery_code"
                                variant="outlined"
                                density="comfortable"
                            />
                        </template>

                        <v-btn 
                            type="button" 
                            color="secondary" 
                            block 
                            rounded="lg"
                            variant="outlined"
                            size="large"
                            @click="toggleRecovery"
                            class="mb-4"
                        >
                            <v-icon start :icon="recovery ? 'mdi-cellphone-key' : 'mdi-key'" />
                            {{ recovery ? 'Use authentication code' : 'Use recovery code' }}
                        </v-btn>

                        <v-btn 
                            type="submit" 
                            color="primary" 
                            variant="flat"
                            size="large" 
                            block 
                            rounded="lg"
                            prepend-icon="mdi-login"
                            :disabled="form.processing" 
                            :loading="form.processing"
                        >
                            Verify & Login
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-container>
    </GuestLayout>
</template>
