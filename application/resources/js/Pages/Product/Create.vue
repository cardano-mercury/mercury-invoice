<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({errors: Object})

const form = useForm({
    name: null,
    sku: null,
    description: null,
    unit_type: null,
    unit_price: null,
    supplier: null,
});
</script>

<template>
    <app-layout title="Create Product">
        <template #header>
            <h1>Create New Product</h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-form @submit.prevent="form.post(route('products.store'))">
                <v-text-field name="name" id="product_name" type="text"
                              label="Product Name" placeholder="e.g. ACME Product"
                              required v-model="form.name" />
                <v-text-field name="sku" id="product_sku" type="text"
                              label="Product SKU" placeholder="e.g. ABCD-1234"
                              required v-model="form.sku" />
                <v-textarea name="description" id="product_description"
                            label="Description" placeholder=".e.g. ACME Product is our latest product..."
                            v-model="form.description" />
                <v-text-field name="unit_type" id="product_unit_type" type="text"
                              label="Unit Type" placeholder="e.g. ounce"
                              v-model="form.unit_type" />
                <v-text-field name="unit_price" id="product_unit_price" type="number"
                              label="Unit Price" placeholder="e.g. 5.99"
                              v-model="form.unit_price" required step="any" min="0" />
                <v-text-field name="supplier" id="product_supplier" type="text"
                              label="Product Supplier" placeholder="e.g. ACME Limited"
                              v-model="form.supplier" />
                <v-row>
                    <v-col cols="auto">
                        <v-btn color="primary" type="submit" variant="flat">Save</v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn type="reset" variant="flat">Reset</v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn :href="route('products.index')" color="error" variant="flat">Cancel</v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-sheet>
    </app-layout>
</template>
