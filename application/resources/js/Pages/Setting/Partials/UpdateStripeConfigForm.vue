<script setup>
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

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
        }
    });
};
</script>

<template>
    <v-card class="mb-6">
        <v-card-title>Stripe Payment Gateway</v-card-title>
        <v-card-subtitle>
            Setup your Stripe payment gateway to process payments.
        </v-card-subtitle>

        <v-card-text>
            <p>
                Setup <a class="text-blue-600" href="https://dashboard.stripe.com/test/apikeys"
                         target="_blank">stripe.com</a> payment gateway.
                Please provide your <strong>Standard keys</strong> from your
                account.
            </p>
            
            <v-alert type="warning" variant="outlined" class="my-4">
                <p class="mb-4">
                    <strong>IMPORTANT:</strong> Stripe only accepts whole
                    numbers
                    (e.g. 12) for the <strong>invoice line item
                    quantity</strong>
                    field, however our app will allow you to enter decimals
                    (e.g.
                    12.5) for flexibility with other payment gateways.
                </p>
                <p>
                    Please remember this when creating invoices, if you plan to
                    enable stripe payment gateway.
                </p>
            </v-alert>

            <v-form @submit.prevent="updateStripeConfig">
                <v-switch 
                    v-model="form.enable_stripe_payment" 
                    color="primary"
                    :value="true" 
                    label="Enable Stripe Payment Gateway"
                    hide-details
                    class="mb-4"
                ></v-switch>
                
                <v-text-field 
                    v-if="form.enable_stripe_payment"
                    v-model="form.secret_key" 
                    type="password"
                    label="Secret Key"
                    :error-messages="form.errors.secret_key"
                    :placeholder="stripePaymentGatewayEnabled ? 'Current value hidden for security reasons - can be updated' : 'sk_51OoswEIlfjrCFA12jYQFqHGj4uD4f'"
                ></v-text-field>

                <div class="d-flex justify-end mt-4">
                    <v-snackbar
                        v-model="showSuccessMessage"
                        color="success"
                        timeout="3000"
                    >
                        Stripe configuration updated successfully!
                    </v-snackbar>
                    
                    <v-btn
                        color="primary"
                        variant="flat"
                        prepend-icon="mdi-content-save"
                        :loading="form.processing"
                        type="submit"
                    >
                        Save
                    </v-btn>
                </div>
            </v-form>
        </v-card-text>
    </v-card>
</template>
