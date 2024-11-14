<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref} from "vue";

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Reference',
        align: 'start',
        sortable: true,
        key: 'invoice_reference'
    },
    {
        title: 'Customer',
        align: 'start',
        sortable: true,
        key: 'customer.name'
    },
    {
        title: 'Issue Date',
        align: 'start',
        sortable: true,
        key: 'issue_date'
    },
    {
        title: 'Due Date',
        align: 'start',
        sortable: true,
        key: 'due_date'
    },
    {
        title: 'Total',
        align: 'start',
        sortable: true,
        key: 'total'
    },
    {
        title: 'Status',
        align: 'start',
        sortable: true,
        key: 'status'
    },
    {
        title: '',
        align: 'end',
        sortable: false,
        key: 'actions'
    }
];

defineProps({
    invoices: Array
});
</script>

<template>
    <app-layout title="Invoices">
        <template #header>
            <h1>Invoices</h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-row class="mb-4 px-4" align="center">
                <v-text-field
                    v-model="search"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="outlined"
                    hide-details
                    single-line
                ></v-text-field>
                <v-spacer/>
                <v-btn :href="route('invoices.export')" variant="flat"
                       class="me-2">
                    Export
                </v-btn>
                <v-btn :href="route('invoices.create')" variant="flat"
                       color="primary">Create New
                </v-btn>
            </v-row>

            <v-data-table :items="invoices" :headers="headers" :search="search"
                          multi-sort :items-per-page="itemsPerPage">
                <template v-slot:item.due_date="{ item }">
                    {{ item.due_date }}
                    <v-chip color="error" v-if="item.is_overdue" class="ms-2">LATE</v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn :href="route('invoices.show', item.id)"
                           class="me-2" size="small"
                           variant="flat" icon="mdi-magnify" rounded="0">
                    </v-btn>
                </template>
            </v-data-table>
        </v-sheet>
    </app-layout>
</template>
