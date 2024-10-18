<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import UpdateBusinessInfoForm
    from '@/Pages/Setting/Partials/UpdateBusinessInfoForm.vue';
import UpdateStripeConfigForm
    from "@/Pages/Setting/Partials/UpdateStripeConfigForm.vue";
import UpdateCryptoConfigForm
    from "@/Pages/Setting/Partials/UpdateCryptoConfigForm.vue";

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
            <h1>Settings</h1>
        </template>

        <v-sheet class="bg-white px-4 py-12">
            <UpdateBusinessInfoForm :supportedCurrencies="supportedCurrencies"/>
            <SectionBorder/>
            <UpdateStripeConfigForm
                :stripePaymentGatewayEnabled="stripePaymentGatewayEnabled"/>
            <SectionBorder/>
            <UpdateCryptoConfigForm
                :cryptoPaymentGatewayEnabled="cryptoPaymentGatewayEnabled"
                :cryptoPaymentAddress="cryptoPaymentAddress"
                :targetCardanoNetwork="targetCardanoNetwork"/>
        </v-sheet>
    </AppLayout>
</template>
