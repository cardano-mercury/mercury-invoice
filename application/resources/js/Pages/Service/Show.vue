<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, service: Object})

const form = useForm(props.service);
</script>

<template>
    <app-layout :title="'service: ' + service.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Service: {{ service.name }}
            </h2>
            <div>
                <span class="badge">
                    Unit Price: {{ parseFloat(service.unit_price).toFixed(2) }}
                    <span v-if="service.unit_type">({{ service.unit_type }})</span>
                </span>
                <span v-if="service.supplier" class="badge">
                    Supplier: {{ service.supplier }}
                </span>
            </div>
            <div v-if="service.description">
                {{ service.description }}
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <Link :href="route('services.edit', service.id)">
                            <button type="button" class="btn mx-4">Edit</button>
                        </Link>
                        <button type="button" class="btn mx-4" @click="form.delete(route('services.destroy', service.id))">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
