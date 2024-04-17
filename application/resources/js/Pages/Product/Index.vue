<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({
    products: Array
});

function doDelete(product) {
    const response = confirm(`Are you sure you want to delete ${product.name}?`);
    if (response) {
        const form = useForm(product);
        form.delete(route('products.destroy', product.id));
    }
}
</script>

<template>
    <app-layout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="text-right">
                            <Link :href="route('products.create')">
                                <button class="btn btn-blue">
                                    Create New
                                </button>
                            </Link>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">SKU</th>
                                <th class="text-left">Price</th>
                                <th class="text-left">Supplier</th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <td>{{ product.name }}</td>
                                <td>{{ product.sku || '-' }}</td>
                                <td>{{ (parseFloat(product.unit_price).toFixed(2) + ' ' + (product.unit_type || '')).trim() }}</td>
                                <td>{{ product.supplier || '-' }}</td>
                                <td class="text-right">
                                    <Link :href="route('products.show', product.id)">
                                        <button class="btn">
                                            View
                                        </button>
                                    </Link>
                                    <Link :href="route('products.edit', product.id)">
                                        <button class="btn">
                                            Edit
                                        </button>
                                    </Link>
                                    <button class="btn" @click="doDelete(product)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
