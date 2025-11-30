<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';

defineProps({
    reports: Array,
    reportTypes: Array,
    customers: Array,
    products: Array,
    services: Array,
});

const $toast = useToast();

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'Report Name',
        align: 'start',
        sortable: true,
        key: 'name',
    },
    {
        title: 'Type & Filters',
        align: 'start',
        sortable: true,
        key: 'type',
    },
    {
        title: 'Generated',
        align: 'start',
        sortable: true,
        key: 'generated_at',
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

const dialog = ref(false);
const loading = ref(false);

const setReportType = (reportType) => (form.reportType = reportType);

const form = useForm({
    reportType: null,
    reportName: null,
    fromDate: null,
    toDate: null,
    productId: null,
    serviceId: null,
    customerId: null,
});

const generateReport = () => {
    form.post(route('reports.generate'), {
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
        onSuccess: () => {
            form.reportType = null;
            form.reportName = null;
            form.fromDate = null;
            form.toDate = null;
            form.productId = null;
            form.serviceId = null;
            form.customerId = null;
            dialog.value = false;
            $toast.success('Report successfully queued to be generated.');
        },
    });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'Success':
            return 'success';
        case 'Pending':
            return 'info';
        case 'Generating':
            return 'warning';
        case 'Error':
            return 'error';
        default:
            return 'secondary';
    }
};

const getStatusIcon = (status) => {
    switch (status) {
        case 'Success':
            return 'mdi-check-circle';
        case 'Pending':
            return 'mdi-clock-outline';
        case 'Generating':
            return 'mdi-loading mdi-spin';
        case 'Error':
            return 'mdi-alert-circle';
        default:
            return 'mdi-help-circle';
    }
};
</script>

<template>
    <AppLayout title="Reports">
        <template #header>
            <PageHeader 
                title="Reports" 
                subtitle="Generate and download business reports"
                icon="mdi-chart-bar"
            >
                <template #actions>
                    <v-dialog v-model="dialog" max-width="600" persistent>
                        <template #activator="{ props: activatorProps }">
                            <v-btn
                                v-bind="activatorProps"
                                prepend-icon="mdi-file-chart"
                                variant="flat"
                                color="primary"
                            >
                                Generate Report
                            </v-btn>
                        </template>

                        <v-card>
                            <v-card-title class="d-flex align-center pa-4">
                                <v-icon icon="mdi-file-chart" color="primary" class="mr-2" />
                                Generate Report
                            </v-card-title>
                            <v-divider />

                            <v-form @submit.prevent="generateReport">
                                <v-card-text class="pa-6">
                                    <!-- Report Type Selection -->
                                    <div class="mb-6">
                                        <div class="text-subtitle-2 font-weight-bold mb-3">Select Report Type</div>
                                        <div class="d-flex ga-2 flex-wrap">
                                            <v-btn
                                                v-for="reportType in reportTypes"
                                                :key="reportType"
                                                :variant="form.reportType === reportType ? 'flat' : 'outlined'"
                                                :color="form.reportType === reportType ? 'primary' : 'secondary'"
                                                size="small"
                                                @click="setReportType(reportType)"
                                            >
                                                {{ reportType }}
                                            </v-btn>
                                        </div>
                                        <div v-if="form.errors.reportType" class="text-error text-caption mt-2">
                                            {{ form.errors.reportType }}
                                        </div>
                                    </div>

                                    <!-- Report Details -->
                                    <template v-if="form.reportType">
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                    v-model="form.reportName"
                                                    label="Report Name"
                                                    placeholder="e.g. Q4 Revenue Report"
                                                    prepend-inner-icon="mdi-file-document"
                                                    :error-messages="form.errors.reportName"
                                                    clearable
                                                />
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    v-model="form.fromDate"
                                                    type="date"
                                                    label="From Date"
                                                    prepend-inner-icon="mdi-calendar"
                                                    :error-messages="form.errors.fromDate"
                                                />
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    v-model="form.toDate"
                                                    type="date"
                                                    label="To Date"
                                                    prepend-inner-icon="mdi-calendar"
                                                    :error-messages="form.errors.toDate"
                                                />
                                            </v-col>
                                        </v-row>

                                        <!-- Conditional Filters -->
                                        <v-row v-if="form.reportType === 'Revenue by Product'">
                                            <v-col cols="12">
                                                <v-select
                                                    v-model="form.productId"
                                                    :items="products"
                                                    item-value="id"
                                                    item-title="name"
                                                    label="Select Product"
                                                    prepend-inner-icon="mdi-package-variant"
                                                    :error-messages="form.errors.productId"
                                                />
                                            </v-col>
                                        </v-row>

                                        <v-row v-if="form.reportType === 'Revenue by Service'">
                                            <v-col cols="12">
                                                <v-select
                                                    v-model="form.serviceId"
                                                    :items="services"
                                                    item-value="id"
                                                    item-title="name"
                                                    label="Select Service"
                                                    prepend-inner-icon="mdi-briefcase"
                                                    :error-messages="form.errors.serviceId"
                                                />
                                            </v-col>
                                        </v-row>

                                        <v-row v-if="form.reportType === 'Revenue by Customer'">
                                            <v-col cols="12">
                                                <v-select
                                                    v-model="form.customerId"
                                                    :items="customers"
                                                    item-value="id"
                                                    item-title="name"
                                                    label="Select Customer"
                                                    prepend-inner-icon="mdi-account"
                                                    :error-messages="form.errors.customerId"
                                                />
                                            </v-col>
                                        </v-row>
                                    </template>
                                </v-card-text>

                                <v-divider />

                                <v-card-actions class="pa-4">
                                    <v-spacer />
                                    <v-btn
                                        variant="text"
                                        color="secondary"
                                        prepend-icon="mdi-close"
                                        @click="dialog = false"
                                    >
                                        Cancel
                                    </v-btn>
                                    <v-btn
                                        type="submit"
                                        variant="flat"
                                        color="primary"
                                        prepend-icon="mdi-file-chart"
                                        :disabled="!form.reportType"
                                        :loading="loading"
                                    >
                                        Generate Report
                                    </v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </template>
            </PageHeader>
        </template>

        <v-card>
            <!-- Search Bar -->
            <v-card-text>
                <v-row align="center">
                    <v-col cols="12" md="6" lg="4">
                        <v-text-field
                            v-model="search"
                            placeholder="Search reports..."
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            single-line
                            hide-details
                            density="comfortable"
                        />
                    </v-col>
                    <v-col cols="12" md="6" lg="8" class="d-flex justify-end">
                        <v-chip variant="tonal" color="primary">
                            <v-icon start icon="mdi-chart-bar" />
                            {{ reports.length }} reports
                        </v-chip>
                    </v-col>
                </v-row>
            </v-card-text>

            <!-- Data Table -->
            <v-data-table
                :items="reports"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
                multi-sort
                class="report-table"
            >
                <template #item.name="{ item }">
                    <div class="d-flex align-center py-2">
                        <v-avatar color="primary" variant="tonal" size="36" class="mr-3">
                            <v-icon icon="mdi-file-chart" size="small" />
                        </v-avatar>
                        <div class="font-weight-medium">{{ item.name }}</div>
                    </div>
                </template>

                <template #item.type="{ item }">
                    <div>
                        <div class="font-weight-medium">{{ item.type }}</div>
                        <div class="text-caption text-medium-emphasis">
                            {{ item.from_date }} to {{ item.to_date }}
                        </div>
                        <div v-if="item.product" class="text-caption text-medium-emphasis">
                            <v-icon icon="mdi-package-variant" size="x-small" /> {{ item.product.name }}
                        </div>
                        <div v-if="item.service" class="text-caption text-medium-emphasis">
                            <v-icon icon="mdi-briefcase" size="x-small" /> {{ item.service.name }}
                        </div>
                        <div v-if="item.customer" class="text-caption text-medium-emphasis">
                            <v-icon icon="mdi-account" size="x-small" /> {{ item.customer.name }}
                        </div>
                    </div>
                </template>

                <template #item.generated_at="{ item }">
                    <span v-if="item.generated_at">
                        {{ new Date(item.generated_at).toLocaleDateString() }}
                    </span>
                    <span v-else class="text-medium-emphasis">—</span>
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
                        <template v-if="item.status === 'Success'">
                            <v-btn
                                :href="route('reports.download', item.id)"
                                icon="mdi-download"
                                size="small"
                                variant="text"
                                color="primary"
                            >
                                <v-icon icon="mdi-download" />
                                <v-tooltip activator="parent" location="top">Download</v-tooltip>
                            </v-btn>
                            <v-btn
                                :href="route('reports.delete', item.id)"
                                icon="mdi-trash-can"
                                size="small"
                                variant="text"
                                color="error"
                            >
                                <v-icon icon="mdi-trash-can" />
                                <v-tooltip activator="parent" location="top">Delete</v-tooltip>
                            </v-btn>
                        </template>
                    </div>
                </template>

                <!-- Empty State -->
                <template #no-data>
                    <div class="text-center py-12">
                        <v-icon icon="mdi-chart-bar" size="64" color="primary" class="mb-4" />
                        <h3 class="text-h6 mb-2">No reports yet</h3>
                        <p class="text-body-2 text-medium-emphasis mb-4">
                            Generate your first report to get insights
                        </p>
                        <v-btn
                            color="primary"
                            variant="flat"
                            prepend-icon="mdi-file-chart"
                            @click="dialog = true"
                        >
                            Generate Report
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.report-table :deep(th) {
    white-space: nowrap;
}
</style>
