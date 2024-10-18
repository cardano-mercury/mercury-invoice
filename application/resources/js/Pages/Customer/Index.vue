<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {ref} from "vue";

defineProps({
    customers: Array
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Customer Name',
        align: 'start',
        sortable: true,
        key: 'name'
    },
    {
        title: 'Tax Number',
        align: 'start',
        sortable: true,
        key: 'tax_number'
    },
    {
        title: 'Tax Rate',
        align: 'start',
        sortable: true,
        key: 'tax_rate'
    },
    {
        title: '',
        align: 'end',
        sortable: false,
        key: 'actions'
    }
];

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
            <h1>Customers</h1>
        </template>

        <v-sheet class="bg-white px-4 py-12">
            <v-row class="mb-4 px-4">
                <v-text-field
                    v-model="search"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="outlined"
                    hide-details
                    single-line
                ></v-text-field>
                <v-spacer/>
                <v-btn :href="route('customers.export')" variant="flat"
                       class="me-2">
                    Export Customers
                </v-btn>
                <v-btn :href="route('customers.create')" variant="flat"
                       color="primary">Create New
                </v-btn>
            </v-row>

            <v-data-table :items="customers" :headers="headers" :search="search"
                          multi-sort :items-per-page="itemsPerPage">
                <template v-slot:item.actions="{ item }">
                    <v-btn :href="route('customers.show', item.id)"
                           color="primary" class="me-2"
                           prepend-icon="mdi-magnify">
                        View
                    </v-btn>
                    <v-btn :href="route('customers.edit', item.id)"
                           class="me-2" prepend-icon="mdi-pencil">
                        Edit
                    </v-btn>
                    <v-btn @click="doDelete(item)" color="error"
                           prepend-icon="mdi-trash-can">
                        Delete
                    </v-btn>
                </template>
            </v-data-table>
        </v-sheet>
    </app-layout>
</template>
