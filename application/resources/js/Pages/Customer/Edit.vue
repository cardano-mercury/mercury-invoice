<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm } from '@inertiajs/vue3';

const props = defineProps({errors: Object, customer: Object})

const form = useForm(props.customer);
</script>
<template>
    <app-layout title="Update Customer">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Update Customer </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <form @submit.prevent="form.put(route('customers.update', customer.id))">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Customer Name
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="ACME Holding, Co." id="name" v-model="form.name" required/>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="tax_number">Tax Number
                                </label>
                                <input placeholder="12-3456789"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="tax_number" v-model="form.tax_number"/>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="tax_rate">Tax Rate
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="8.75" type="number" step="0.01" min="0" max="100.00"
                                    v-model="form.tax_rate"/>
                            </div>
                            <div class="mb-4">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit">Save
                                </button>
                                <button class="btn font-bold py-2 px-4" type="reset">Reset</button>
                                <Link :href="route('customers.index')">
                                    <button
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                        type="button">Cancel
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
