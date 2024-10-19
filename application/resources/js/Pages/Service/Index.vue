<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from "vue";

defineProps({
    services: Array
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Service Name',
        align: 'start',
        sortable: true,
        key: 'name'
    },
    {
        title: 'Price',
        align: 'start',
        sortable: true,
        key: 'unit_price'
    },
    {
        title: 'Supplier',
        align: 'start',
        sortable: true,
        key: 'supplier'
    },
    {
        title: '',
        align: 'end',
        sortable: false,
        key: 'actions'
    }
];

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
            <h1>Services</h1>
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
                <v-btn :href="route('services.export')" variant="flat"
                       class="me-2">
                    Export
                </v-btn>
                <v-btn :href="route('services.create')" variant="flat"
                       color="primary">Create New
                </v-btn>
            </v-row>

            <v-data-table :items="services" :headers="headers" :search="search"
                          multi-sort :items-per-page="itemsPerPage">
                <template v-slot:item.unit_price="{ item }">
                    {{ parseFloat(item.unit_price).toFixed(2) }}
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn :href="route('services.show', item.id)"
                           color="primary" class="me-2" size="small"
                           variant="flat"
                           icon="mdi-magnify" rounded="0">
                    </v-btn>
                    <v-btn :href="route('services.edit', item.id)"
                           variant="flat"
                           class="me-2" icon="mdi-pencil" size="small"
                           rounded="0">
                    </v-btn>
                    <v-btn @click="doDelete(item)" color="error" variant="flat"
                           icon="mdi-trash-can" size="small" rounded="0">
                    </v-btn>
                </template>
            </v-data-table>
        </v-sheet>
    </app-layout>
</template>
