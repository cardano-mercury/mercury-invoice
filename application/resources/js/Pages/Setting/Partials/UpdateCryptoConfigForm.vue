<script setup>
import { useForm } from '@inertiajs/vue3';
import FormSection from "@/Components/FormSection.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";

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
            Crypto Payment Gateway
        </template>

        <template #description>
            Setup crypto payment gateway to accept payment for your invoices in $ADA (automatic currency conversion).
            <br><br>
            Please provide your payment address and <a class="text-blue-600" href="https://blockfrost.io/" target="_blank">blockfrost.io</a> <strong>{{ targetCardanoNetwork }} API KEY</strong> (also known as project id).
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.enable_crypto_payment" value="true" />
                    <span class="ms-2 text-sm text-gray-600">Enable Crypto Payment Gateway</span>
                </label>
            </div>

            <div v-if="form.enable_crypto_payment" class="col-span-6 sm:col-span-4">
                <InputLabel for="payment_address" :value="`Payment Address (${targetCardanoNetwork})`" />
                <TextInput
                    id="payment_address"
                    v-model="form.payment_address"
                    type="text"
                    class="mt-1 block w-full"
                    :placeholder="targetCardanoNetwork.indexOf('main') !== -1 ? 'e.g. addr1qrh49wd9z8yy0547e8jacdjjh77h...' : 'e.g. addr_test1qrh49wd9z8yy0547e8jacdj...'"
                />
                <InputError :message="form.errors.payment_address" class="mt-2" />
            </div>

            <div v-if="form.enable_crypto_payment" class="col-span-6 sm:col-span-4">
                <InputLabel for="api_key" value="Api Key / Project ID" />
                <TextInput
                    id="api_key"
                    v-model="form.api_key"
                    type="password"
                    class="mt-1 block w-full"
                    :placeholder="cryptoPaymentGatewayEnabled ? 'Current value hidden for security reason - can be updated' : 'e.g. preprodjPkcH5mIFMKpvbFPWn6Rnreb17...'"
                />
                <InputError :message="form.errors.api_key" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
