<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    customers: Array,
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Customer Name',
        align: 'start',
        sortable: true,
        key: 'name',
    },
    {
        title: 'Tax Number',
        align: 'start',
        sortable: true,
        key: 'tax_number',
    },
    {
        title: 'Tax Rate',
        align: 'start',
        sortable: true,
        key: 'tax_rate',
    },
    {
        title: 'Actions',
        align: 'end',
        sortable: false,
        key: 'actions',
    },
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
    <AppLayout title="Customers">
        <template #header>
            <PageHeader 
                title="Customers" 
                subtitle="Manage your customer database"
                icon="mdi-account-group"
            >
                <template #actions>
                    <v-btn
                        :href="route('customers.export')"
                        variant="tonal"
                        prepend-icon="mdi-download"
                    >
                        Export
                    </v-btn>
                    <v-btn
                        :href="route('customers.create')"
                        variant="flat"
                        color="primary"
                        prepend-icon="mdi-plus"
                    >
                        Add Customer
                    </v-btn>
                </template>
            </PageHeader>
        </template>

        <v-card>
            <!-- Search Bar -->
            <v-card-text class="pb-0">
                <v-row align="center">
                    <v-col cols="12" md="6" lg="4">
                        <v-text-field
                            v-model="search"
                            placeholder="Search customers..."
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            single-line
                        />
                    </v-col>
                    <v-col cols="12" md="6" lg="8" class="d-flex justify-end">
                        <v-chip variant="tonal" color="primary">
                            <v-icon start icon="mdi-account-group" />
                            {{ customers.length }} customers
                        </v-chip>
                    </v-col>
                </v-row>
            </v-card-text>

            <!-- Data Table -->
            <v-data-table
                :items="customers"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
                multi-sort
                class="customer-table"
            >
                <template #item.name="{ item }">
                    <div class="d-flex align-center py-2">
                        <v-avatar color="primary" size="36" class="mr-3">
                            <span class="text-body-2 font-weight-bold text-white">
                                {{ item.name.charAt(0).toUpperCase() }}
                            </span>
                        </v-avatar>
                        <div>
                            <div class="font-weight-medium">{{ item.name }}</div>
                        </div>
                    </div>
                </template>

                <template #item.tax_number="{ item }">
                    <span v-if="item.tax_number" class="text-body-2">{{ item.tax_number }}</span>
                    <span v-else class="text-medium-emphasis">—</span>
                </template>

                <template #item.tax_rate="{ item }">
                    <v-chip v-if="item.tax_rate" size="small" variant="tonal" color="primary">
                        {{ item.tax_rate }}%
                    </v-chip>
                    <span v-else class="text-medium-emphasis">—</span>
                </template>

                <template #item.actions="{ item }">
                    <div class="d-flex justify-end ga-1">
                        <v-btn
                            :href="route('customers.show', item.id)"
                            icon="mdi-eye"
                            size="small"
                            variant="text"
                            color="primary"
                        >
                            <v-icon icon="mdi-eye" />
                            <v-tooltip activator="parent" location="top">View</v-tooltip>
                        </v-btn>
                        <v-btn
                            :href="route('customers.edit', item.id)"
                            icon="mdi-pencil"
                            size="small"
                            variant="text"
                            color="primary"
                        >
                            <v-icon icon="mdi-pencil" />
                            <v-tooltip activator="parent" location="top">Edit</v-tooltip>
                        </v-btn>
                        <v-btn
                            @click="doDelete(item)"
                            icon="mdi-trash-can"
                            size="small"
                            variant="text"
                            color="error"
                        >
                            <v-icon icon="mdi-trash-can" />
                            <v-tooltip activator="parent" location="top">Delete</v-tooltip>
                        </v-btn>
                    </div>
                </template>

                <!-- Empty State -->
                <template #no-data>
                    <div class="text-center py-12">
                        <v-icon icon="mdi-account-off" size="64" color="primary" class="mb-4" />
                        <h3 class="text-h6 mb-2">No customers yet</h3>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Get started by adding your first customer
                        </p>
                        <v-btn
                            :href="route('customers.create')"
                            color="primary"
                            variant="flat"
                            prepend-icon="mdi-plus"
                        >
                            Add Customer
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.customer-table :deep(th) {
    white-space: nowrap;
}
</style>
