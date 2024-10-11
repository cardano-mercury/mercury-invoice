<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, customer: Object})

const form = useForm(props.customer);
</script>
<template>
    <app-layout :title="'Customer: ' + customer.name">
        <template #header>
            <h1>Customer: {{ customer.name }}</h1>
            <div>
                <v-chip label v-if="customer.tax_number" class="me-2">
                    Tax Number: {{ customer.tax_number }}
                </v-chip>
                <v-chip label v-if="customer.tax_rate" class="me-2">
                    Tax Rate: {{ customer.tax_rate }}
                </v-chip>
            </div>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-row justify="end">
                <v-col cols="auto">
                    <v-btn :href="route('customers.edit', customer.id)"
                           variant="flat" prepend-icon="mdi-pencil">
                        Edit
                    </v-btn>
                </v-col>
                <v-col cols="auto">
                    <v-btn variant="flat" color="error"
                           prepend-icon="mdi-trash-can"
                           @click="form.delete(route('customers.destroy', customer.id))">
                        Delete
                    </v-btn>
                </v-col>
            </v-row>
        </v-sheet>
    </app-layout>
</template>
