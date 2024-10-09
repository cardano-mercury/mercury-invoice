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
            Stripe Payment Gateway
        </template>

        <template #description>
            Setup <a class="text-blue-600" href="https://stripe.com" target="_blank">stripe.com</a> payment gateway.
            Please provide your <strong>Standard keys</strong> from your account.
            <br><br>
            <div class="px-2 py-1 bg-amber-100 text-amber-700 rounded">
                <strong>IMPORTANT:</strong> Stripe only accepts whole numbers (e.g. 12) for the <strong>invoice line item quantity</strong> field, however our app will allow you to enter decimals (e.g. 12.5) for flexibility with other payment gateways.
                <br><br>
                Please remember this when creating invoices, if you plan to enable stripe payment gateway.
            </div>
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.enable_stripe_payment" value="true" />
                    <span class="ms-2 text-sm text-gray-600">Enable Stripe Payment Gateway</span>
                </label>
            </div>

            <div v-if="form.enable_stripe_payment" class="col-span-6 sm:col-span-4">
                <InputLabel for="secret_key" value="Secret Key" />
                <TextInput
                    id="secret_key"
                    v-model="form.secret_key"
                    type="password"
                    class="mt-1 block w-full"
                    :placeholder="stripePaymentGatewayEnabled ? 'Current value hidden for security reason - can be updated' : 'e.g. sk_51OoswEIlfjrCFA12jYQFqHGj4uD4f...'"
                />
                <InputError :message="form.errors.secret_key" class="mt-2" />
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
