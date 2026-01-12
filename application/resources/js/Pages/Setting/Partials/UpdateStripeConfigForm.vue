<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormCard from '@/Components/FormCard.vue';

const props = defineProps({
    stripePaymentGatewayEnabled: Boolean,
});

const showSuccessMessage = ref(false);

const form = useForm({
    enable_stripe_payment: props.stripePaymentGatewayEnabled,
    secret_key: null,
});

const updateStripeConfig = () => {
    form.post(route('user.settings.save-stripe-config'), {
        errorBag: 'updateStripeConfig',
        preserveScroll: true,
        onSuccess: () => {
            showSuccessMessage.value = true;
        },
    });
};
</script>

<template>
    <FormCard 
        title="Stripe Payment Gateway" 
        icon="mdi-credit-card"
        subtitle="Accept card payments via Stripe"
    >
        <template #actions>
            <v-chip
                :color="stripePaymentGatewayEnabled ? 'success' : 'secondary'"
                variant="tonal"
                size="small"
            >
                <v-icon start :icon="stripePaymentGatewayEnabled ? 'mdi-check-circle' : 'mdi-close-circle'" size="small" />
                {{ stripePaymentGatewayEnabled ? 'Enabled' : 'Disabled' }}
            </v-chip>
        </template>

        <template #description>
            <p class="text-body-2 text-medium-emphasis mb-2">
                Setup 
                <a class="text-primary font-weight-medium" href="https://dashboard.stripe.com/test/apikeys" target="_blank">
                    stripe.com
                    <v-icon icon="mdi-open-in-new" size="x-small" />
                </a> 
                payment gateway. Please provide your <strong>Standard keys</strong> from your account.
            </p>
        </template>

        <v-alert type="warning" variant="tonal" class="mb-6" density="compact">
            <template #prepend>
                <v-icon icon="mdi-alert" />
            </template>
            <div class="text-body-2">
                <strong>Important:</strong> Stripe only accepts whole numbers (e.g. 12) for invoice line item quantities. 
                Our app allows decimals (e.g. 12.5) for flexibility with other payment gateways. 
                Please remember this when creating invoices if you plan to enable Stripe.
            </div>
        </v-alert>

        <v-form @submit.prevent="updateStripeConfig">
            <v-row>
                <v-col cols="12">
                    <v-switch
                        v-model="form.enable_stripe_payment"
                        color="primary"
                        :value="true"
                        label="Enable Stripe Payment Gateway"
                    />
                </v-col>
            </v-row>

            <v-row v-if="form.enable_stripe_payment">
                <v-col cols="12">
                    <v-text-field
                        v-model="form.secret_key"
                        type="password"
                        label="Secret Key"
                        prepend-inner-icon="mdi-key"
                        :error-messages="form.errors.secret_key"
                        :placeholder="stripePaymentGatewayEnabled ? 'Current value hidden - enter new key to update' : 'sk_51OoswEIlfjrCFA12jYQFqHGj4uD4f'"
                        hint="Your Stripe secret key starts with sk_live_ or sk_test_"
                        persistent-hint
                    />
                </v-col>
            </v-row>

            <div class="d-flex justify-end mt-4">
                <v-snackbar v-model="showSuccessMessage" color="success" timeout="3000">
                    <v-icon icon="mdi-check-circle" class="mr-2" />
                    Stripe configuration updated successfully!
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
