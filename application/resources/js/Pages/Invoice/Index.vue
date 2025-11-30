<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { ref } from 'vue';

defineProps({
    invoices: Array,
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Reference',
        align: 'start',
        sortable: true,
        key: 'invoice_reference',
    },
    {
        title: 'Customer',
        align: 'start',
        sortable: true,
        key: 'customer.name',
    },
    {
        title: 'Issue Date',
        align: 'start',
        sortable: true,
        key: 'issue_date',
    },
    {
        title: 'Due Date',
        align: 'start',
        sortable: true,
        key: 'due_date',
    },
    {
        title: 'Total',
        align: 'end',
        sortable: true,
        key: 'total',
    },
    {
        title: 'Status',
        align: 'center',
        sortable: true,
        key: 'status',
    },
    {
        title: 'Actions',
        align: 'end',
        sortable: false,
        key: 'actions',
    },
];

const getStatusColor = (status) => {
    switch (status) {
        case 'Draft':
            return 'secondary';
        case 'Published':
            return 'info';
        case 'Payment Processing':
            return 'warning';
        case 'Paid':
            return 'success';
        case 'Voided':
            return 'error';
        default:
            return 'secondary';
    }
};

const getStatusIcon = (status) => {
    switch (status) {
        case 'Draft':
            return 'mdi-file-document-outline';
        case 'Published':
            return 'mdi-send';
        case 'Payment Processing':
            return 'mdi-clock-outline';
        case 'Paid':
            return 'mdi-check-circle';
        case 'Voided':
            return 'mdi-cancel';
        default:
            return 'mdi-file-document';
    }
};
</script>

<template>
    <AppLayout title="Invoices">
        <template #header>
            <PageHeader 
                title="Invoices" 
                subtitle="Manage and track your invoices"
                icon="mdi-file-document-multiple"
            >
                <template #actions>
                    <v-btn
                        :href="route('invoices.export')"
                        variant="tonal"
                        prepend-icon="mdi-download"
                    >
                        Export
                    </v-btn>
                    <v-btn
                        :href="route('invoices.create')"
                        variant="flat"
                        color="primary"
                        prepend-icon="mdi-plus"
                    >
                        Create Invoice
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
                            placeholder="Search invoices..."
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            single-line
                        />
                    </v-col>
                    <v-col cols="12" md="6" lg="8" class="d-flex justify-end">
                        <v-chip variant="tonal" color="primary">
                            <v-icon start icon="mdi-file-document-multiple" />
                            {{ invoices.length }} invoices
                        </v-chip>
                    </v-col>
                </v-row>
            </v-card-text>

            <!-- Data Table -->
            <v-data-table
                :items="invoices"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
                multi-sort
                class="invoice-table"
            >
                <template #item.invoice_reference="{ item }">
                    <div class="d-flex align-center py-2">
                        <v-avatar :color="getStatusColor(item.status)" variant="tonal" size="36" class="mr-3">
                            <v-icon :icon="getStatusIcon(item.status)" size="small" />
                        </v-avatar>
                        <div>
                            <div class="font-weight-bold font-mono">{{ item.invoice_reference }}</div>
                            <div v-if="item.customer_reference" class="text-caption text-medium-emphasis">
                                Ref: {{ item.customer_reference }}
                            </div>
                        </div>
                    </div>
                </template>

                <template #item.customer.name="{ item }">
                    <span class="font-weight-medium">{{ item.customer?.name || '—' }}</span>
                </template>

                <template #item.issue_date="{ item }">
                    <span v-if="item.issue_date">{{ item.issue_date }}</span>
                    <span v-else class="text-medium-emphasis">—</span>
                </template>

                <template #item.due_date="{ item }">
                    <div class="d-flex align-center ga-2">
                        <span v-if="item.due_date">{{ item.due_date }}</span>
                        <span v-else class="text-medium-emphasis">—</span>
                        <v-chip
                            v-if="item.is_overdue"
                            color="error"
                            size="x-small"
                            variant="flat"
                        >
                            OVERDUE
                        </v-chip>
                    </div>
                </template>

                <template #item.total="{ item }">
                    <span class="font-weight-bold">{{ item.total }}</span>
                </template>

                <template #item.status="{ item }">
                    <v-chip
                        :color="getStatusColor(item.status)"
                        size="small"
                        variant="tonal"
                    >
                        <v-icon start :icon="getStatusIcon(item.status)" size="x-small" />
                        {{ item.status }}
                    </v-chip>
                </template>

                <template #item.actions="{ item }">
                    <div class="d-flex justify-end ga-1">
                        <v-btn
                            :href="route('invoices.show', item.id)"
                            icon="mdi-eye"
                            size="small"
                            variant="text"
                            color="primary"
                        >
                            <v-icon icon="mdi-eye" />
                            <v-tooltip activator="parent" location="top">View</v-tooltip>
                        </v-btn>
                        <v-btn
                            v-if="item.status === 'Draft'"
                            :href="route('invoices.edit', item.id)"
                            icon="mdi-pencil"
                            size="small"
                            variant="text"
                            color="primary"
                        >
                            <v-icon icon="mdi-pencil" />
                            <v-tooltip activator="parent" location="top">Edit</v-tooltip>
                        </v-btn>
                    </div>
                </template>

                <!-- Empty State -->
                <template #no-data>
                    <div class="text-center py-12">
                        <v-icon icon="mdi-file-document-remove" size="64" color="primary" class="mb-4" />
                        <h3 class="text-h6 mb-2">No invoices yet</h3>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Get started by creating your first invoice
                        </p>
                        <v-btn
                            :href="route('invoices.create')"
                            color="primary"
                            variant="flat"
                            prepend-icon="mdi-plus"
                        >
                            Create Invoice
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.invoice-table :deep(th) {
    white-space: nowrap;
}

.font-mono {
    font-family: 'Source Code Pro', monospace;
}
</style>
