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
    sku: null,
    description: null,
    unit_type: null,
    unit_price: null,
    supplier: null,
});

const handleReset = () => {
    form.reset();
};
</script>

<template>
    <AppLayout title="Create Product">
        <template #header>
            <PageHeader 
                title="Create New Product" 
                subtitle="Add a new product to your inventory"
                icon="mdi-package-variant-plus"
            />
        </template>

        <v-form @submit.prevent="form.post(route('products.store'))">
            <FormCard 
                title="Product Information" 
                icon="mdi-package-variant"
                subtitle="Enter the details for this product"
            >
                <v-row>
                    <v-col cols="12" md="8">
                        <v-text-field
                            v-model="form.name"
                            label="Product Name"
                            placeholder="e.g. ACME Widget Pro"
                            prepend-inner-icon="mdi-package-variant"
                            :error-messages="form.errors.name"
                            required
                            autofocus
                        />
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="form.sku"
                            label="SKU"
                            placeholder="e.g. ACME-001"
                            prepend-inner-icon="mdi-barcode"
                            :error-messages="form.errors.sku"
                            required
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-textarea
                            v-model="form.description"
                            label="Description"
                            placeholder="e.g. High-quality widget for professional use..."
                            prepend-inner-icon="mdi-text"
                            :error-messages="form.errors.description"
                            rows="3"
                            auto-grow
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="form.unit_type"
                            label="Unit Type"
                            placeholder="e.g. piece, kg, liter"
                            prepend-inner-icon="mdi-scale"
                            :error-messages="form.errors.unit_type"
                            hint="Optional - How this product is measured"
                            persistent-hint
                        />
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="form.unit_price"
                            label="Unit Price"
                            placeholder="e.g. 29.99"
                            prepend-inner-icon="mdi-currency-usd"
                            type="number"
                            step="0.01"
                            min="0"
                            :error-messages="form.errors.unit_price"
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="form.supplier"
                            label="Supplier"
                            placeholder="e.g. ACME Corp"
                            prepend-inner-icon="mdi-truck-delivery"
                            :error-messages="form.errors.supplier"
                            hint="Optional - Product supplier name"
                            persistent-hint
                        />
                    </v-col>
                </v-row>

                <template #footer>
                    <FormActions
                        :loading="form.processing"
                        :cancel-route="route('products.index')"
                        save-text="Create Product"
                        save-icon="mdi-package-variant-plus"
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
                    <strong>Tip:</strong> After creating the product, you can assign categories from the edit page.
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
