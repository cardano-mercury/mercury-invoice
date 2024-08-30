<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import UpdateBusinessInfoForm from '@/Pages/Setting/Partials/UpdateBusinessInfoForm.vue';
import UpdateStripeConfigForm from "@/Pages/Setting/Partials/UpdateStripeConfigForm.vue";
import UpdateCryptoConfigForm from "@/Pages/Setting/Partials/UpdateCryptoConfigForm.vue";

defineProps({
    supportedCurrencies: Array,
    stripePaymentGatewayEnabled: Boolean,
    cryptoPaymentGatewayEnabled: Boolean,
    cryptoPaymentAddress: String,
    targetCardanoNetwork: String,
});
</script>

<template>
    <AppLayout title="Settings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <UpdateBusinessInfoForm :supportedCurrencies="supportedCurrencies" />

                    <SectionBorder />
                </div>

                <div>
                    <UpdateStripeConfigForm :stripePaymentGatewayEnabled="stripePaymentGatewayEnabled" />

                    <SectionBorder />
                </div>

                <div>
                    <UpdateCryptoConfigForm
                        :cryptoPaymentGatewayEnabled="cryptoPaymentGatewayEnabled"
                        :cryptoPaymentAddress="cryptoPaymentAddress"
                        :targetCardanoNetwork="targetCardanoNetwork" />

                    <SectionBorder />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
