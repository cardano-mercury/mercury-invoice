<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, service: Object})

const form = useForm(props.service);
</script>

<template>
    <app-layout title="Update Service">
        <template #header>
            <h1>Update Service</h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-form @submit.prevent="form.put(route('services.update', service.id))">
                <v-text-field name="name" id="service_name" type="text"
                              label="Service Name"
                              placeholder="e.g. Software Development"
                              required v-model="form.name"/>
                <v-textarea name="description" id="product_description"
                            label="Description"
                            placeholder=".e.g. My hourly rate for software development..."
                            v-model="form.description"/>
                <v-text-field name="unit_price" id="product_unit_price"
                              type="number"
                              label="Unit Price" placeholder="e.g. 5.99"
                              v-model="form.unit_price" required step="any"
                              min="0"/>
                <v-text-field name="supplier" id="product_supplier" type="text"
                              label="Product Supplier"
                              placeholder="e.g. ACME Limited"
                              v-model="form.supplier"/>
                <v-row>
                    <v-col cols="auto">
                        <v-btn color="primary" type="submit" variant="flat">
                            Save
                        </v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn type="reset" variant="flat">Reset</v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn :href="route('services.index')" color="error"
                               variant="flat">Cancel
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-sheet>
    </app-layout>
</template>
