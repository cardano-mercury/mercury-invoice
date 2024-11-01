<script setup>
import {useForm} from '@inertiajs/vue3';
import FormSection from "@/Components/FormSection.vue";

const props = defineProps({
    cryptoPaymentGatewayEnabled: Boolean,
    cryptoPaymentAddress: String,
    targetCardanoNetwork: String,
});

const form = useForm({
    enable_crypto_payment: props.cryptoPaymentGatewayEnabled,
    payment_address: props.cryptoPaymentAddress,
    api_key: null,
});

const updateCryptoConfig = () => {
    form.post(route('user.settings.save-crypto-config'), {
        errorBag: 'updateCryptoConfig',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateCryptoConfig">
        <template #title>
            <h2>Crypto Payment Gateway</h2>
        </template>

        <template #description>
            <p class="mb-4">
                Setup crypto payment gateway to accept payment for your invoices
                in $ADA (automatic currency conversion).
            </p>
            <p>
                Please provide your payment address and
                <a href="https://blockfrost.io/"
                   target="_blank">blockfrost.io</a>
                <strong>&nbsp;{{ targetCardanoNetwork }} API KEY</strong> (also
                known as project id).
            </p>
        </template>

        <template #form>
            <v-switch v-model="form.enable_crypto_payment" color="primary"
                      :value="true" label="Enable Crypto Payment Gateway"/>
            <template v-if="form.enable_crypto_payment">

            </template>
            <v-text-field :label="`Payment Address (${targetCardanoNetwork})`"
                          v-model="form.payment_address" type="text"
                          :placeholder="targetCardanoNetwork.indexOf('main') !== -1 ? 'e.g. addr1qrh49wd9z8yy0547e8jacdjjh77h...' : 'e.g. addr_test1qrh49wd9z8yy0547e8jacdj...'"/>
            <v-text-field label="API Key / Project ID"
                          v-model="form.api_key" type="password"
                          :placeholder="cryptoPaymentGatewayEnabled ? 'Current value hidden for security reasons - can be updated' : 'e.g. preprodjPkcH5mIFMKpvbFPWn6Rnreb17...'"/>
        </template>

        <template #actions>
            <v-btn color="primary" variant="flat" :disabled="form.processing">
                Save
            </v-btn>
        </template>
    </FormSection>
</template>
