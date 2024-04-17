<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, product: Object})

const form = useForm(props.product);
</script>
<template>
    <app-layout :title="'Product: ' + product.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Product: {{ product.name }}
            </h2>
            <div>
                <span v-if="product.sku" class="badge">
                    SKU: {{ product.sku }}
                </span>
                <span class="badge">
                    Unit Price: {{ parseFloat(product.unit_price).toFixed(2) }}
                    <span v-if="product.unit_type">({{ product.unit_type }})</span>
                </span>
                <span v-if="product.supplier" class="badge">
                    Supplier: {{ product.supplier }}
                </span>
            </div>
            <div v-if="product.description">
                {{ product.description }}
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <Link :href="route('products.edit', product.id)">
                            <button type="button" class="btn mx-4">Edit</button>
                        </Link>
                        <button type="button" class="btn mx-4" @click="form.delete(route('products.destroy', product.id))">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
