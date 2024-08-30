<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import FormSection from "@/Components/FormSection.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    supportedCurrencies: Array,
});

const page = usePage();

const form = useForm({
    account_currency: page.props.auth.user.account_currency,
    business_name: page.props.auth.user.business_name,
    business_terms: page.props.auth.user.business_terms,
});

const updateBusinessInfo = () => {
    form.post(route('user.settings.save-business-info'), {
        errorBag: 'updateBusinessInfo',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateBusinessInfo">
        <template #title>
            Update Business Info
        </template>

        <template #description>
            Tell us about your business, such as what currency you want to charge your customers in, your business name and terms and conditions.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="account_currency" value="Account Currency" />
                <select
                    id="account_currency"
                    v-model="form.account_currency"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                >
                    <option v-for="(name, currency) in supportedCurrencies" :value="currency">{{ name }}</option>
                </select>
                <InputError :message="form.errors.account_currency" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="business_name" value="Business Name" />
                <TextInput
                    id="business_name"
                    v-model="form.business_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="business-name"
                />
                <InputError :message="form.errors.business_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="business_terms" value="Business Terms & Conditions" />
                <textarea
                    id="business_terms"
                    v-model="form.business_terms"
                    rows="3"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                />
                <InputError :message="form.errors.business_terms" class="mt-2" />
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
