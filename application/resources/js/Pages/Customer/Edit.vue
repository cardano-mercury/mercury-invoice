<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm } from '@inertiajs/vue3';

const props = defineProps({errors: Object, customer: Object})

const form = useForm(props.customer);
</script>
<template>
    <app-layout title="Update Customer">
        <template #header>
            <h1>Update Customer</h1>
        </template>

        <v-sheet class="bg-white px-4 py-12">
            <v-form @submit.prevent="form.put(route('customers.update', customer.id))">
                <v-text-field label="Customer Name" name="name"
                              autocomplete="customer_name" v-model="form.name"
                              placeholder="e.g. ACME Holding, Co."
                              required autofocus/>
                <v-text-field label="Tax Number" name="tax_number"
                              autocomplete="customer_tax_number"
                              placeholder="e.g. 12-3456789"
                              v-model="form.tax_number"/>
                <v-text-field label="Tax Rate" name="tax_rate"
                              autocomplete="customer_tax_rate"
                              placeholder="e.g. 8.75"
                              type="number" step="0.01" min="0" max="100.00"
                              v-model="form.tax_rate" />
                <v-row>
                    <v-col cols="auto">
                        <v-btn color="primary" type="submit" variant="flat">Save</v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn type="button" variant="flat">Reset</v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn :href="route('customers.show', customer.id)" color="error" variant="flat">Cancel</v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-sheet>
    </app-layout>
</template>
