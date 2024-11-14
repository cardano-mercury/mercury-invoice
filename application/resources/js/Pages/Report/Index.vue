<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toast-notification";

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
        title: 'Name',
        align: 'start',
        sortable: true,
        key: 'name'
    },
    {
        title: 'Type',
        align: 'start',
        sortable: true,
        key: 'type'
    },
    {
        title: 'Date Generated',
        align: 'start',
        sortable: true,
        key: 'generated_at'
    },
    {
        title: '',
        align: 'end',
        sortable: false,
        key: 'actions'
    },
];

const dialog = ref(false);
const loading = ref(false);

const setReportType = (reportType) => form.reportType = reportType;

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
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
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
        }
    });
};

</script>

<template>
    <AppLayout title="Reports">
        <template #header>
            <h1>Reports</h1>
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

                <v-dialog
                    v-model="dialog"
                    max-width="600"
                    persistent
                >
                    <template v-slot:activator="{ props: activatorProps }">
                        <v-btn
                            class="text-none font-weight-regular"
                            prepend-icon="mdi-list-box"
                            text="Generate Report"
                            variant="flat"
                            color="primary"
                            v-bind="activatorProps"
                        ></v-btn>
                    </template>

                    <v-card prepend-icon="">
                        <v-form @submit.prevent="generateReport">
                            <v-card-text>

                                <v-row class="mt-6 mb-3" dense>
                                    <v-col cols="12" class="text-center">
                                        <v-icon
                                            icon="mdi-content-paste"
                                            size="64"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row class="mb-3" dense>
                                    <v-col cols="12" class="text-center">
                                        <div><h3>Generate Report</h3></div>
                                        <div class="text-disabled">Choose report type to create</div>
                                    </v-col>
                                </v-row>

                                <v-row class="mb-6" dense>
                                    <v-col cols="12">
                                        <div class="d-flex ga-4 flex-wrap justify-center">
                                            <v-btn
                                                v-for="reportType in reportTypes"
                                                key="reportType"
                                                variant="flat"
                                                color="primary"
                                                size="large"
                                                :value="reportType"
                                                @click="setReportType(reportType)"
                                                :active="form.reportType === reportType"
                                                :disabled="form.reportType === reportType"
                                            >
                                                {{ reportType }}
                                            </v-btn>
                                        </div>
                                        <div v-if="form.errors.reportType" class="mt-6 text-red">
                                            {{ form.errors.reportType }}
                                        </div>
                                    </v-col>
                                </v-row>

                                <v-row v-if="form.reportType" dense>
                                    <v-col cols="12">
                                        <v-text-field
                                            type="text"
                                            label="Report Name"
                                            clearable
                                            v-model="form.reportName"
                                            :error-messages="form.errors.reportName"
                                        />
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            type="date"
                                            label="From Date"
                                            v-model="form.fromDate"
                                            :error-messages="form.errors.fromDate"
                                        />
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            type="date"
                                            label="To Date"
                                            v-model="form.toDate"
                                            :error-messages="form.errors.toDate"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row v-if="form.reportType === 'Revenue by Product'" dense>
                                    <v-col cols="12">
                                        <v-select
                                            :items="products"
                                            item-value="id"
                                            item-title="name"
                                            label="Product"
                                            v-model="form.productId"
                                            :error-messages="form.errors.productId"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row v-if="form.reportType === 'Revenue by Service'" dense>
                                    <v-col cols="12">
                                        <v-select
                                            :items="services"
                                            item-value="id"
                                            item-title="name"
                                            label="Service"
                                            v-model="form.serviceId"
                                            :error-messages="form.errors.serviceId"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row v-if="form.reportType === 'Revenue by Customer'" dense>
                                    <v-col cols="12">
                                        <v-select
                                            :items="customers"
                                            item-value="id"
                                            item-title="name"
                                            label="Customer"
                                            v-model="form.customerId"
                                            :error-messages="form.errors.customerId"
                                        />
                                    </v-col>
                                </v-row>

                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn
                                    text="Cancel"
                                    variant="flat"
                                    @click="dialog = false"
                                ></v-btn>

                                <v-btn
                                    type="submit"
                                    variant="flat"
                                    color="primary"
                                    text="Generate Report"
                                    :disabled="!form.reportType"
                                    :loading="loading"
                                ></v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
            </v-row>

            <v-data-table
                multi-sort
                :items="reports"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
            >
                <template v-slot:item.type="{ item }">
                    <strong>{{item.type }}</strong>
                    <br>
                    <span class="text-disabled">From: <strong>{{ item.from_date }} to {{ item.to_date }}</strong></span>
                    <div v-if="item.product" class="text-disabled">Product: <strong>{{ item.product.name }}</strong></div>
                    <div v-if="item.service" class="text-disabled">Service: <strong>{{ item.service.name }}</strong></div>
                    <div v-if="item.customer" class="text-disabled">Customer: <strong>{{ item.customer.name }}</strong></div>
                </template>
                <template v-slot:item.generated_at="{ item }">
                    <span v-if="item.generated_at">{{ new Date(item.generated_at).toDateString() }}</span>
                    <span v-else>-</span>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div v-if="item.status === 'Success'">
                        <v-btn
                            :href="route('reports.download', item.id)"
                            class="me-2"
                            variant="flat" icon="mdi-download" rounded="0"
                        />
                        <v-btn
                            :href="route('reports.delete', item.id)"
                            class="me-2"
                            variant="flat" icon="mdi-delete" rounded="0"
                        />
                    </div>
                    <div v-else-if="item.status === 'Pending'">
                        <v-btn type="flat" prepend-icon="mdi-play" size="small" class="text-none font-weight-regular" color="primary">
                            {{ item.status }}
                        </v-btn>
                    </div>
                    <div v-else-if="item.status === 'Generating'">
                        <v-btn type="flat" prepend-icon="mdi-pause" size="small" class="text-none font-weight-regular" color="warning">
                            {{ item.status }}
                        </v-btn>
                    </div>
                    <div v-else-if="item.status === 'Error'">
                        <v-btn type="flat" prepend-icon="mdi-alert-circle-outline" size="small" class="text-none font-weight-regular" color="error">
                            {{ item.status }}
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-sheet>
    </AppLayout>
</template>
