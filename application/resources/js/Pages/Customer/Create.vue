<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import FormCard from '@/Components/FormCard.vue';
import FormActions from '@/Components/FormActions.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    errors: Object,
});

const form = useForm({
    name: null,
    tax_number: null,
    tax_rate: null,
});

const handleReset = () => {
    form.reset();
};
</script>

<template>
    <AppLayout title="Create Customer">
        <template #header>
            <PageHeader 
                title="Create New Customer" 
                subtitle="Add a new customer to your database"
                icon="mdi-account-plus"
            />
        </template>

        <v-form @submit.prevent="form.post(route('customers.store'))">
            <FormCard 
                title="Customer Information" 
                icon="mdi-account"
                subtitle="Enter the basic details for this customer"
            >
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.name"
                            label="Customer Name"
                            placeholder="e.g. ACME Holding, Co."
                            prepend-inner-icon="mdi-domain"
                            :error-messages="form.errors.name"
                            required
                            autofocus
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.tax_number"
                            label="Tax Number"
                            placeholder="e.g. 12-3456789"
                            prepend-inner-icon="mdi-file-document"
                            :error-messages="form.errors.tax_number"
                            hint="Optional - Tax identification number"
                            persistent-hint
                        />
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.tax_rate"
                            label="Tax Rate (%)"
                            placeholder="e.g. 8.75"
                            prepend-inner-icon="mdi-percent"
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            :error-messages="form.errors.tax_rate"
                            hint="Optional - Default tax rate for this customer"
                            persistent-hint
                        />
                    </v-col>
                </v-row>

                <template #footer>
                    <FormActions
                        :loading="form.processing"
                        :cancel-route="route('customers.index')"
                        save-text="Create Customer"
                        save-icon="mdi-account-plus"
                        @reset="handleReset"
                    />
                </template>
            </FormCard>
        </v-form>

        <!-- Help Card -->
        <v-card variant="tonal" class="mt-6">
            <v-card-text class="d-flex align-center">
                <v-icon icon="mdi-information" color="info" class="mr-3" />
                <div>
                    <strong>Tip:</strong> After creating the customer, you can add email addresses, phone numbers, and addresses from the edit page.
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
