<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormCard from '@/Components/FormCard.vue';

const props = defineProps({
    cryptoPaymentGatewayEnabled: Boolean,
    cryptoPaymentAddress: String,
    targetCardanoNetwork: String,
});

const showSuccessMessage = ref(false);

const form = useForm({
    enable_crypto_payment: props.cryptoPaymentGatewayEnabled,
    payment_address: props.cryptoPaymentAddress,
    api_key: null,
});

const updateCryptoConfig = () => {
    form.post(route('user.settings.save-crypto-config'), {
        errorBag: 'updateCryptoConfig',
        preserveScroll: true,
        onSuccess: () => {
            showSuccessMessage.value = true;
        },
    });
};

const isMainnet = props.targetCardanoNetwork.toLowerCase().indexOf('main') !== -1;
</script>

<template>
    <FormCard 
        title="Crypto Payment Gateway" 
        icon="mdi-bitcoin"
        subtitle="Accept payments in ADA cryptocurrency"
    >
        <template #actions>
            <v-chip
                :color="cryptoPaymentGatewayEnabled ? 'success' : 'secondary'"
                variant="tonal"
                size="small"
            >
                <v-icon start :icon="cryptoPaymentGatewayEnabled ? 'mdi-check-circle' : 'mdi-close-circle'" size="small" />
                {{ cryptoPaymentGatewayEnabled ? 'Enabled' : 'Disabled' }}
            </v-chip>
        </template>

        <template #description>
            <p class="text-body-2 text-medium-emphasis mb-2">
                Setup crypto payment gateway to accept payment for your invoices in <strong>$ADA</strong> with automatic currency conversion.
            </p>
            <p class="text-body-2 text-medium-emphasis">
                Please provide your payment address and 
                <a class="text-primary font-weight-medium" href="https://blockfrost.io/" target="_blank">
                    blockfrost.io
                    <v-icon icon="mdi-open-in-new" size="x-small" />
                </a>
                <v-chip size="x-small" :color="isMainnet ? 'success' : 'warning'" variant="tonal" class="ml-1">
                    {{ targetCardanoNetwork }}
                </v-chip>
                API KEY (also known as project id).
            </p>
        </template>

        <v-form @submit.prevent="updateCryptoConfig">
            <v-row>
                <v-col cols="12">
                    <v-switch
                        v-model="form.enable_crypto_payment"
                        color="primary"
                        :value="true"
                        label="Enable Crypto Payment Gateway"
                    />
                </v-col>
            </v-row>

            <template v-if="form.enable_crypto_payment">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.payment_address"
                            type="text"
                            :label="`Payment Address (${targetCardanoNetwork})`"
                            prepend-inner-icon="mdi-wallet"
                            :error-messages="form.errors.payment_address"
                            :placeholder="isMainnet ? 'addr1qrh49wd9z8yy0547e8jacdjjh77h...' : 'addr_test1qrh49wd9z8yy0547e8jacdj...'"
                            hint="Your Cardano wallet address where payments will be received"
                            persistent-hint
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.api_key"
                            type="password"
                            label="API Key / Project ID"
                            prepend-inner-icon="mdi-key"
                            :error-messages="form.errors.api_key"
                            :placeholder="cryptoPaymentGatewayEnabled ? 'Current value hidden - enter new key to update' : 'preprodjPkcH5mIFMKpvbFPWn6Rnreb17...'"
                            hint="Your Blockfrost project ID for the selected network"
                            persistent-hint
                        />
                    </v-col>
                </v-row>
            </template>

            <div class="d-flex justify-end mt-4">
                <v-snackbar v-model="showSuccessMessage" color="success" timeout="3000">
                    <v-icon icon="mdi-check-circle" class="mr-2" />
                    Crypto payment configuration updated successfully!
                </v-snackbar>

                <v-btn
                    color="primary"
                    variant="flat"
                    prepend-icon="mdi-content-save"
                    :loading="form.processing"
                    type="submit"
                >
                    Save Changes
                </v-btn>
            </div>
        </v-form>
    </FormCard>
</template>
