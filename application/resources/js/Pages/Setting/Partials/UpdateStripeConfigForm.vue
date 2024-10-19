<script setup>
import {useForm} from '@inertiajs/vue3';
import FormSection from "@/Components/FormSection.vue";

const props = defineProps({
    stripePaymentGatewayEnabled: Boolean,
});

const form = useForm({
    enable_stripe_payment: props.stripePaymentGatewayEnabled,
    secret_key: null,
});

const updateStripeConfig = () => {
    form.post(route('user.settings.save-stripe-config'), {
        errorBag: 'updateStripeConfig',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateStripeConfig">
        <template #title>
            <h2>Stripe Payment Gateway</h2>
        </template>

        <template #description>
            <p>
                Setup <a class="text-blue-600" href="https://stripe.com"
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
        </template>

        <template #form>
            <v-switch v-model="form.enable_stripe_payment" color="primary"
                      :value="true" label="Enable Stripe Payment Gateway"/>
            <v-text-field v-if="form.enable_stripe_payment"
                          v-model="form.secret_key" type="password"
                          :placeholder="stripePaymentGatewayEnabled ? 'Current value hidden for security reasons - can be updated' : 'sk_51OoswEIlfjrCFA12jYQFqHGj4uD4f'"></v-text-field>

        </template>

        <template #actions>
            <v-btn color="primary" variant="flat" :disabled="form.processing"
                   type="submit">
                Save
            </v-btn>
        </template>
    </FormSection>
</template>
