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
    description: null,
    unit_price: null,
    supplier: null,
});

const handleReset = () => {
    form.reset();
};
</script>

<template>
    <AppLayout title="Create Service">
        <template #header>
            <PageHeader 
                title="Create New Service" 
                subtitle="Add a new service to your offerings"
                icon="mdi-briefcase-plus"
            />
        </template>

        <v-form @submit.prevent="form.post(route('services.store'))">
            <FormCard 
                title="Service Information" 
                icon="mdi-briefcase"
                subtitle="Enter the details for this service"
            >
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.name"
                            label="Service Name"
                            placeholder="e.g. Software Development"
                            prepend-inner-icon="mdi-briefcase"
                            :error-messages="form.errors.name"
                            required
                            autofocus
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-textarea
                            v-model="form.description"
                            label="Description"
                            placeholder="e.g. Professional software development services including..."
                            prepend-inner-icon="mdi-text"
                            :error-messages="form.errors.description"
                            rows="3"
                            auto-grow
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.unit_price"
                            label="Unit Price (Hourly Rate)"
                            placeholder="e.g. 150.00"
                            prepend-inner-icon="mdi-currency-usd"
                            type="number"
                            step="0.01"
                            min="0"
                            :error-messages="form.errors.unit_price"
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.supplier"
                            label="Service Provider"
                            placeholder="e.g. Your Company Name"
                            prepend-inner-icon="mdi-domain"
                            :error-messages="form.errors.supplier"
                            hint="Optional - Service provider or contractor name"
                            persistent-hint
                        />
                    </v-col>
                </v-row>

                <template #footer>
                    <FormActions
                        :loading="form.processing"
                        :cancel-route="route('services.index')"
                        save-text="Create Service"
                        save-icon="mdi-briefcase-plus"
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
                    <strong>Tip:</strong> After creating the service, you can assign categories from the edit page.
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
