<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import UpdateBusinessInfoForm from '@/Pages/Setting/Partials/UpdateBusinessInfoForm.vue';
import UpdateStripeConfigForm from '@/Pages/Setting/Partials/UpdateStripeConfigForm.vue';
import UpdateCryptoConfigForm from '@/Pages/Setting/Partials/UpdateCryptoConfigForm.vue';

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
            <PageHeader 
                title="Settings" 
                subtitle="Configure your account and payment options"
                icon="mdi-cog"
            />
        </template>

        <v-row>
            <v-col cols="12" lg="8">
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

            <v-col cols="12" lg="4">
                <!-- Quick Links -->
                <v-card>
                    <v-card-title class="d-flex align-center">
                        <v-icon icon="mdi-lightning-bolt" class="mr-2" color="primary" />
                        Quick Links
                    </v-card-title>
                    <v-divider />
                    <v-list density="comfortable">
                        <v-list-item
                            :href="route('profile.show')"
                            prepend-icon="mdi-account"
                            title="Profile Settings"
                            subtitle="Update your account details"
                        />
                        <v-list-item
                            :href="route('webhooks.index')"
                            prepend-icon="mdi-webhook"
                            title="Webhooks"
                            subtitle="Configure webhook integrations"
                        />
                        <v-list-item
                            href="https://dashboard.stripe.com/test/apikeys"
                            target="_blank"
                            prepend-icon="mdi-credit-card"
                            title="Stripe Dashboard"
                            subtitle="Manage your Stripe account"
                            append-icon="mdi-open-in-new"
                        />
                        <v-list-item
                            href="https://blockfrost.io/dashboard"
                            target="_blank"
                            prepend-icon="mdi-bitcoin"
                            title="Blockfrost Dashboard"
                            subtitle="Manage your Blockfrost project"
                            append-icon="mdi-open-in-new"
                        />
                    </v-list>
                </v-card>

                <!-- Help Card -->
                <v-card variant="tonal" color="info" class="mt-6">
                    <v-card-text>
                        <div class="d-flex align-start">
                            <v-icon icon="mdi-help-circle" class="mr-3 mt-1" />
                            <div>
                                <h4 class="text-subtitle-1 font-weight-bold mb-1">Need Help?</h4>
                                <p class="text-body-2 mb-0">
                                    Check out our documentation or contact support if you need assistance setting up your payment gateways.
                                </p>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </AppLayout>
</template>
