<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({
    customers: Array
});

function doDelete(customer) {
    const response = confirm(`Are you sure you want to delete ${customer.name}?`);
    if (response) {
        const form = useForm(customer);
        form.delete(route('customers.destroy', customer.id));
    }
}
</script>

<template>
    <app-layout title="Customers">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customers
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="flex gap-6 justify-end mb-6">
                            <a target="_blank" :href="route('customers.export')" class="btn btn-gray">
                                Export Customers
                            </a>
                            <Link :href="route('customers.create')">
                                <button class="btn btn-blue">
                                    Create New
                                </button>
                            </Link>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Tax Number</th>
                                    <th class="text-left">Tax Rate</th>
                                    <th class="text-right">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="customer in customers" :key="customer.id">
                                    <td>{{ customer.name }}</td>
                                    <td>{{ customer.tax_number }}</td>
                                    <td>{{ customer.tax_rate }}</td>
                                    <td class="text-right">
                                        <Link :href="route('customers.show', customer.id)">
                                            <button class="btn">
                                                View
                                            </button>
                                        </Link>
                                        <Link :href="route('customers.edit', customer.id)">
                                            <button class="btn">
                                                Edit
                                            </button>
                                        </Link>
                                        <button class="btn" @click="doDelete(customer)">
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
