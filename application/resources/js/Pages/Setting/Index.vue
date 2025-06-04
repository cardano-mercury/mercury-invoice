<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
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

        <v-container>
            <v-row>
                <v-col cols="12" md="8">
                    <!-- Business Information Settings -->
                    <UpdateBusinessInfoForm :supportedCurrencies="supportedCurrencies" />

                    <!-- Stripe Configuration Settings -->
                    <UpdateStripeConfigForm :stripePaymentGatewayEnabled="stripePaymentGatewayEnabled" />

                    <!-- Crypto Configuration Settings -->
                    <UpdateCryptoConfigForm
                        :cryptoPaymentGatewayEnabled="cryptoPaymentGatewayEnabled"
                        :cryptoPaymentAddress="cryptoPaymentAddress"
                        :targetCardanoNetwork="targetCardanoNetwork"
                    />
                </v-col>
            </v-row>
        </v-container>
    </AppLayout>
</template>
