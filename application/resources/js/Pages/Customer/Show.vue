<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, customer: Object})

const form = useForm(props.customer);
</script>
<template>
    <app-layout :title="'Customer: '+customer.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer: {{ customer.name }} </h2>
            <p>
                <span v-if="customer.tax_number">
                    Tax Number: {{ customer.tax_number }}
                </span> <span v-if="customer.tax_rate">
                    Tax Rate: {{ customer.tax_rate }}
                </span>
            </p>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <Link :href="route('customers.edit', customer.id)">
                            <button type="button" class="btn mx-4">Edit</button>
                        </Link>
                        <button type="button" class="btn mx-4" @click="form.delete(route('customers.destroy', customer.id))">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
