<script setup>
import {computed, ref, watch} from "vue";
import {Line} from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LinearScale,
    TimeScale,
    LineElement,
    PointElement,
} from 'chart.js';
import 'chartjs-adapter-moment';
import axios from "axios";

ChartJS.register(
    Title, Tooltip, Legend, LineElement, TimeScale, LinearScale, PointElement
);

const props = defineProps({
    stats: Object,
});

const dashboardStats = ref(props.stats);

const loading = ref(false);

const salesByTimeOptions = computed(() => {
    let timeUnit = 'day';
    switch (timeframe.value) {
        case 1: timeUnit = 'hour'; break;
        case 7: timeUnit = 'day'; break;
        case 30: timeUnit = 'week'; break;
        case 365: timeUnit = 'month'; break;
    }
    return {
        datasets: {
            line: {
                tension: 0.4,
            }
        },
        responsive: true,
        legend: {
            display: false,
        },
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: timeUnit,
                },
            },
            y: {
                ticks: {
                    // callback: function (value, index, ticks) {
                    //     return new Intl.NumberFormat('en-US', {
                    //         style: 'currency',
                    //         currency: 'USD'
                    //     }).format(value);
                    // }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    // label: (context) => {
                    //     let label = context.dataset.label || '';
                    //     if (label) {
                    //         label += ': ';
                    //     }
                    //     if (context.parsed.y !== null) {
                    //         label += new Intl.NumberFormat('en-US', {
                    //             style: 'currency',
                    //             currency: 'USD'
                    //         }).format(context.parsed.y);
                    //     }
                    //     return label;
                    // }
                }
            }
        }
    }
});

const salesByTimeData = computed(() => {
    const parsedDataset = {};
    dashboardStats.value.invoices
        .filter(invoice => invoice.status === 'Paid')
        .forEach(invoice => {
            if (parsedDataset[invoice.issue_date]) {
                parsedDataset[invoice.issue_date] += parseFloat(invoice.total).toFixed(2);
            } else {
                parsedDataset[invoice.issue_date] = parseFloat(invoice.total).toFixed(2);
            }
        });
    return {
        labels: Object.keys(parsedDataset),
        datasets: [
            {
                borderColor: '#1ED980',
                backgroundColor: '#1ED980',
                label: 'Revenue',
                data: Object.values(parsedDataset),
            }
        ],
    }
});

const timeframe = ref(7);

const invoiceStatusFilter = ref('paid');

const invoicesHeaders = [
    {
        title: 'Status',
        align: 'start',
        sortable: true,
        key: 'status',
    },
    {
        title: 'Customer',
        align: 'start',
        sortable: true,
        key: 'customer.name',
    },
    {
        title: 'Due',
        align: 'start',
        sortable: true,
        key: 'due_date',
        value: item => `${new Date(item.due_date).toLocaleDateString()}`,
    },
    {
        title: 'Total',
        align: 'start',
        sortable: true,
        key: 'total',
        value: item => `${parseFloat(item.total).toFixed(2)} ${item.currency}`,
    }
];

const filteredInvoices = computed(() => {
    switch (invoiceStatusFilter.value) {
        case 'paid': return dashboardStats.value.invoices.filter(invoice => invoice.status === 'Paid');
        case 'unpaid': return dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === false && invoice.status === 'Published');
        case 'late': return dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === true && invoice.status === 'Published');
        default: return dashboardStats.value.invoices;
    }
});

watch(timeframe, (newTimeframe) => {
    loading.value = true;
    axios.get(route('dashboard.stats') + '?timeframe=' + newTimeframe).then((response) => {
        dashboardStats.value = response.data;
        loading.value = false;
    });
});

</script>

<template>
    <v-btn-toggle variant="outlined" v-model="timeframe" mandatory class="mb-8"
                  density="compact" divided rounded="xl">
        <v-btn :value="1">24H</v-btn>
        <v-btn :value="7">7D</v-btn>
        <v-btn :value="30">1M</v-btn>
        <v-btn :value="365">12M</v-btn>
    </v-btn-toggle>

    <v-skeleton-loader
        :loading="loading"
        type="table"
        class="bg-transparent"
    >
        <v-row align="stretch">
            <v-col cols="4" xl="2">
                <v-card elevation="1">
                    <v-card-text class="text-center">
                        <p class="text-h2">{{ dashboardStats.counts.customers }}</p>
                        <p class="text-h4">Customer{{ dashboardStats.counts.customers > 1 ? 's' : '' }}</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4" xl="2">
                <v-card elevation="1">
                    <v-card-text class="text-center">
                        <p class="text-h2">{{ dashboardStats.counts.products }}</p>
                        <p class="text-h4">Product{{ dashboardStats.counts.products > 1 ? 's' : '' }}</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4" xl="2">
                <v-card elevation="1">
                    <v-card-text class="text-center">
                        <p class="text-h2">{{ dashboardStats.counts.services }}</p>
                        <p class="text-h4">Service{{ dashboardStats.counts.services > 1 ? 's' : '' }}</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4" xl="2">
                <v-card elevation="1">
                    <v-card-text class="text-center">
                        <p class="text-h2">{{ dashboardStats.counts.invoices }}</p>
                        <p class="text-h4">Invoice{{ dashboardStats.counts.invoices > 1 ? 's' : '' }}</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="8" xl="4">
                <v-card elevation="1">
                    <v-row>
                        <v-col>
                            <v-card-text class="text-center">
                                <p class="text-h2 text-green">{{ dashboardStats.counts.paid_invoices }}</p>
                                <p class="text-h4">Paid Invoice{{ dashboardStats.counts.paid_invoices > 1 ? 's' : '' }}</p>
                            </v-card-text>
                        </v-col>
                        <v-col cols="auto" style="width: 1px"
                               class="bg-grey-lighten-3 pa-0 my-1"></v-col>
                        <v-col>
                            <v-card-text class="text-center">
                                <p class="text-h2 text-red">{{ dashboardStats.counts.unpaid_invoices }}</p>
                                <p class="text-h4">Unpaid Invoice{{ dashboardStats.counts.unpaid_invoices > 1 ? 's' : '' }}</p>
                            </v-card-text>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>

        <v-row class="mb-8">
            <v-col cols="12" lg="6">
                <v-card elevation="1">
                    <v-card-title>
                        <v-icon icon="mdi-chart-line-variant"
                                color="primary"></v-icon>
                        Revenue Generated
                    </v-card-title>
                    <v-card-text>
                        <Line :data="salesByTimeData"
                              :options="salesByTimeOptions"/>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" lg="6">
                <v-card elevation="1">
                    <v-card-title>
                        <v-icon icon="mdi-invoice-check" color="primary"/>
                        Recent Invoices
                    </v-card-title>
                    <v-divider/>
                    <v-card-text>
                        <v-btn-toggle v-model="invoiceStatusFilter" variant="outlined" block rounded="xl" mandatory divided>
                            <v-btn value="all">
                                All
                                <v-badge color="primary" content="X" inline />
                            </v-btn>
                            <v-btn value="paid">
                                Paid
                                <v-badge color="primary" content="X" inline />
                            </v-btn>
                            <v-btn value="unpaid">
                                Unpaid
                                <v-badge color="primary" content="X" inline />
                            </v-btn>
                            <v-btn value="late">
                                Late
                                <v-badge color="primary" content="X" inline />
                            </v-btn>
                        </v-btn-toggle>
                    </v-card-text>
                    <v-card-text>
                        <v-data-table :items="filteredInvoices" :headers="invoicesHeaders">
                        </v-data-table>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn :href="route('invoices.index')" append-icon="mdi-chevron-right">
                            See All
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-skeleton-loader>
</template>
