<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FormCard from '@/Components/FormCard.vue';

const props = defineProps({
    supportedCurrencies: Object,
});

const page = usePage();
const showSuccessMessage = ref(false);

const currencies = computed(() => {
    const val = [];
    for (const [key, value] of Object.entries(props.supportedCurrencies)) {
        val.push({
            title: value,
            value: key,
        });
    }
    return val;
});

const form = useForm({
    account_currency: page.props.auth.user.account_currency,
    business_name: page.props.auth.user.business_name,
    business_terms: page.props.auth.user.business_terms,
});

const updateBusinessInfo = () => {
    form.post(route('user.settings.save-business-info'), {
        errorBag: 'updateBusinessInfo',
        preserveScroll: true,
        onSuccess: () => {
            showSuccessMessage.value = true;
        },
    });
};
</script>

<template>
    <FormCard 
        title="Business Information" 
        icon="mdi-domain"
        subtitle="Configure your business details for invoices"
    >
        <template #description>
            <p class="text-body-2 text-medium-emphasis">
                Tell us about your business, such as what currency you want to charge your customers in, your business name and terms and conditions.
            </p>
        </template>

        <v-form @submit.prevent="updateBusinessInfo">
            <v-row>
                <v-col cols="12" md="6">
                    <v-select
                        v-model="form.account_currency"
                        :items="currencies"
                        label="Account Currency"
                        prepend-inner-icon="mdi-currency-usd"
                        :error-messages="form.errors.account_currency"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.business_name"
                        type="text"
                        autocomplete="business-name"
                        label="Business Name"
                        placeholder="e.g. ACME Corporation"
                        prepend-inner-icon="mdi-office-building"
                        :error-messages="form.errors.business_name"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-textarea
                        v-model="form.business_terms"
                        label="Business Terms & Conditions"
                        placeholder="Enter your terms and conditions that will appear on invoices..."
                        prepend-inner-icon="mdi-file-document-outline"
                        :error-messages="form.errors.business_terms"
                        rows="4"
                        auto-grow
                    />
                </v-col>
            </v-row>

            <div class="d-flex justify-end mt-4">
                <v-snackbar v-model="showSuccessMessage" color="success" timeout="3000">
                    <v-icon icon="mdi-check-circle" class="mr-2" />
                    Business information updated successfully!
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
