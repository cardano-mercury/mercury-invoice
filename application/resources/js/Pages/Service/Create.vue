<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({errors: Object})

const form = useForm({
    name: null,
    description: null,
    unit_price: null,
    supplier: null,
});
</script>

<template>
    <app-layout title="Create Service">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Service
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <form @submit.prevent="form.post(route('services.store'))">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Service Name
                                </label>
                                <input placeholder="e.g. Software Development"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="name" v-model="form.name" required />
                                <div v-if="errors.name" class="text-red-600">{{ errors.name }}</div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Description
                                </label>
                                <textarea placeholder="e.g. My hourly rate for software development..."
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                          rows="4"
                                          id="description" v-model="form.description" />
                                <div v-if="errors.description" class="text-red-600">{{ errors.description }}</div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="unit_price">
                                    Unit Price
                                </label>
                                <input placeholder="e.g. 40.49"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       type="number" step="0.01" min="0"
                                       id="unit_price" v-model="form.unit_price" required />
                                <div v-if="errors.unit_price" class="text-red-600">{{ errors.unit_price }}</div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="supplier">
                                    Supplier
                                </label>
                                <input placeholder="e.g. ACME Limited"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="supplier" v-model="form.supplier"/>
                                <div v-if="errors.supplier" class="text-red-600">{{ errors.supplier }}</div>
                            </div>
                            <div class="mb-4">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit"
                                >
                                    Save
                                </button>
                                <button class="btn font-bold py-2 px-4" type="reset">Reset</button>
                                <Link :href="route('services.index')">
                                    <button
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                        type="button"
                                    >
                                        Cancel
                                    </button>
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
