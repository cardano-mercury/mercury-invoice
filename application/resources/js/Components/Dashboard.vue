<script setup>
import {ref} from "vue";
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

ChartJS.register(
    Title, Tooltip, Legend, LineElement, TimeScale, LinearScale, PointElement
);

const props = defineProps({
    invoices: Array
});

const sales_by_time_options = {
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
                unit: 'day'
            }
        },
        y: {
            ticks: {
                callback: function (value, index, ticks) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(value);
                }
            }
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: (context) => {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        label += new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(context.parsed.y);
                    }
                    return label;
                }
            }
        }
    }
}

const sales_by_time_data = {
    labels: [
        '2024-09-30',
        '2024-10-01',
        '2024-10-02',
        '2024-10-03',
        '2024-10-04',
        '2024-10-05',
        '2024-10-06'
    ],
    datasets: [
        {
            borderColor: '#1ED980',
            backgroundColor: '#1ED980',
            label: 'Revenue',
            data: [
                123.45,
                56.78,
                901.23,
                456.78,
                90.1,
                234.56,
                789.01
            ]
        }
    ]
}

const timeperiod = ref(7);
const invoiceStatus = ref('paid');

const invoices_headers = [
    {
        title: 'Status',
        align: 'start',
        sortable: false,
        key: 'status'
    },
    {
        title: 'Customer',
        align: 'start',
        sortable: true,
        key: 'customer.name'
    },
    {
        title: 'Due',
        align: 'start',
        sortable: true,
        key: 'due_date'
    },
    {
        title: 'Total',
        align: 'start',
        sortable: true,
        key: 'total'
    }
];

</script>

<template>
    <v-btn-toggle variant="outlined" v-model="timeperiod" mandatory class="mb-8"
                  density="compact" divided rounded="xl">
        <v-btn :value="1">24H</v-btn>
        <v-btn :value="7">7D</v-btn>
        <v-btn :value="30">1M</v-btn>
        <v-btn :value="365">12M</v-btn>
    </v-btn-toggle>
    <v-row align="stretch">
        <v-col cols="4" xl="2">
            <v-card elevation="1">
                <v-card-text class="text-center">
                    <p class="text-h2">XXX</p>
                    <p class="text-h4">Customers</p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="4" xl="2">
            <v-card elevation="1">
                <v-card-text class="text-center">
                    <p class="text-h2">XXX</p>
                    <p class="text-h4">Products</p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="4" xl="2">
            <v-card elevation="1">
                <v-card-text class="text-center">
                    <p class="text-h2">XXX</p>
                    <p class="text-h4">Services</p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="4" xl="2">
            <v-card elevation="1">
                <v-card-text class="text-center">
                    <p class="text-h2">XXX</p>
                    <p class="text-h4">Invoices</p>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="8" xl="4">
            <v-card elevation="1">
                <v-row>
                    <v-col>
                        <v-card-text class="text-center">
                            <p class="text-h2">XXX</p>
                            <p class="text-h6">Customer Categories</p>
                        </v-card-text>
                    </v-col>
                    <v-col cols="auto" style="width: 1px"
                           class="bg-grey-lighten-3 pa-0 my-1"></v-col>
                    <v-col>
                        <v-card-text class="text-center">
                            <p class="text-h2">XXX</p>
                            <p class="text-h6">Product Categories</p>
                        </v-card-text>
                    </v-col>
                    <v-col cols="auto" style="width: 1px"
                           class="bg-grey-lighten-3 pa-0 my-1"></v-col>
                    <v-col>
                        <v-card-text class="text-center">
                            <p class="text-h2">XXX</p>
                            <p class="text-h6">Service Categories</p>
                        </v-card-text>
                    </v-col>
                </v-row>
            </v-card>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" lg="6">
            <v-card elevation="1">
                <v-card-title>
                    <v-icon icon="mdi-chart-line-variant"
                            color="primary"></v-icon>
                    Revenue Generated
                </v-card-title>
                <v-card-text>
                    <Line :data="sales_by_time_data"
                          :options="sales_by_time_options"/>
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
                    <v-btn-toggle v-model="invoiceStatus" variant="outlined" block rounded="xl" mandatory divided>
                        <v-btn value="">
                            All
                            <v-badge color="primary" content="X" inline />
                        </v-btn>
                        <v-btn :value="'paid'">
                            Paid
                            <v-badge color="primary" content="X" inline />
                        </v-btn>
                        <v-btn :value="'unpaid'">
                            Unpaid
                            <v-badge color="primary" content="X" inline />
                        </v-btn>
                        <v-btn :value="'late'">
                            Late
                            <v-badge color="primary" content="X" inline />
                        </v-btn>
                    </v-btn-toggle>
                </v-card-text>
                <v-card-text>
                    <v-data-table :items="invoices" hide-default-footer :headers="invoices_headers">

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
</template>
