<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, product: Object})

const form = useForm(props.product);
</script>
<template>
    <app-layout :title="'Product: ' + product.name">
        <template #header>
            <h1>Product: {{ product.name }}</h1>
            <div class="mb-2">
                <v-chip label v-if="product.sku" class="me-2">SKU: {{ product.sku }}</v-chip>
                <v-chip label v-if="product.unit_price" class="me-2">
                    Unit Price: {{ parseFloat(product.unit_price).toFixed(2) }}
                    <span v-if="product.unit_type">/{{ product.unit_type }}</span>
                </v-chip>
                <v-chip label v-if="product.supplier" class="me-2">
                    Supplier: {{ product.supplier }}
                </v-chip>
            </div>
            <p v-if="product.description" class="text-body-2">
                {{ product.description }}
            </p>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-row justify="end">
                <v-col cols="auto">
                    <v-btn :href="route('products.edit', product.id)"
                           variant="flat" prepend-icon="mdi-pencil">
                        Edit
                    </v-btn>
                </v-col>
                <v-col cols="auto">
                    <v-btn variant="flat" color="error"
                           prepend-icon="mdi-trash-can"
                           @click="form.delete(route('products.destroy', product.id))">
                        Delete
                    </v-btn>
                </v-col>
            </v-row>
        </v-sheet>
    </app-layout>
</template>
