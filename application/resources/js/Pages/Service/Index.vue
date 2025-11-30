<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    services: Array,
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Service Name',
        align: 'start',
        sortable: true,
        key: 'name',
    },
    {
        title: 'Price',
        align: 'start',
        sortable: true,
        key: 'unit_price',
    },
    {
        title: 'Provider',
        align: 'start',
        sortable: true,
        key: 'supplier',
    },
    {
        title: 'Actions',
        align: 'end',
        sortable: false,
        key: 'actions',
    },
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
    <AppLayout title="Services">
        <template #header>
            <PageHeader 
                title="Services" 
                subtitle="Manage your service offerings"
                icon="mdi-briefcase"
            >
                <template #actions>
                    <v-btn
                        :href="route('services.export')"
                        variant="tonal"
                        prepend-icon="mdi-download"
                    >
                        Export
                    </v-btn>
                    <v-btn
                        :href="route('services.create')"
                        variant="flat"
                        color="primary"
                        prepend-icon="mdi-plus"
                    >
                        Add Service
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
                            placeholder="Search services..."
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            single-line
                        />
                    </v-col>
                    <v-col cols="12" md="6" lg="8" class="d-flex justify-end">
                        <v-chip variant="tonal" color="primary">
                            <v-icon start icon="mdi-briefcase" />
                            {{ services.length }} services
                        </v-chip>
                    </v-col>
                </v-row>
            </v-card-text>

            <!-- Data Table -->
            <v-data-table
                :items="services"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
                multi-sort
                class="service-table"
            >
                <template #item.name="{ item }">
                    <div class="d-flex align-center py-2">
                        <v-avatar color="primary" variant="tonal" size="36" class="mr-3">
                            <v-icon icon="mdi-briefcase" size="small" />
                        </v-avatar>
                        <div>
                            <div class="font-weight-medium">{{ item.name }}</div>
                            <div v-if="item.description" class="text-caption text-medium-emphasis text-truncate" style="max-width: 250px;">
                                {{ item.description }}
                            </div>
                        </div>
                    </div>
                </template>

                <template #item.unit_price="{ item }">
                    <span class="font-weight-medium">
                        {{ parseFloat(item.unit_price).toFixed(2) }}
                    </span>
                    <span class="text-caption text-medium-emphasis"> / hour</span>
                </template>

                <template #item.supplier="{ item }">
                    <span v-if="item.supplier">{{ item.supplier }}</span>
                    <span v-else class="text-medium-emphasis">—</span>
                </template>

                <template #item.actions="{ item }">
                    <div class="d-flex justify-end ga-1">
                        <v-btn
                            :href="route('services.show', item.id)"
                            icon="mdi-eye"
                            size="small"
                            variant="text"
                            color="primary"
                        >
                            <v-icon icon="mdi-eye" />
                            <v-tooltip activator="parent" location="top">View</v-tooltip>
                        </v-btn>
                        <v-btn
                            :href="route('services.edit', item.id)"
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
                        <v-icon icon="mdi-briefcase-off" size="64" color="primary" class="mb-4" />
                        <h3 class="text-h6 mb-2">No services yet</h3>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Get started by adding your first service
                        </p>
                        <v-btn
                            :href="route('services.create')"
                            color="primary"
                            variant="flat"
                            prepend-icon="mdi-plus"
                        >
                            Add Service
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.service-table :deep(th) {
    white-space: nowrap;
}
</style>
