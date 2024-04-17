<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({
    services: Array
});

function doDelete(service) {
    const response = confirm(`Are you sure you want to delete ${service.name}?`);
    if (response) {
        const form = useForm(service);
        form.delete(route('services.destroy', service.id));
    }
}
</script>

<template>
    <app-layout title="Services">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="text-right">
                            <Link :href="route('services.create')">
                                <button class="btn btn-blue">
                                    Create New
                                </button>
                            </Link>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Price</th>
                                <th class="text-left">Supplier</th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="service in services" :key="service.id">
                                <td>{{ service.name }}</td>
                                <td>{{ parseFloat(service.unit_price).toFixed(2) }}</td>
                                <td>{{ service.supplier || '-' }}</td>
                                <td class="text-right">
                                    <Link :href="route('services.show', service.id)">
                                        <button class="btn">
                                            View
                                        </button>
                                    </Link>
                                    <Link :href="route('services.edit', service.id)">
                                        <button class="btn">
                                            Edit
                                        </button>
                                    </Link>
                                    <button class="btn" @click="doDelete(service)">
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
