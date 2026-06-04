<script setup>
import {computed, ref, watch, onMounted} from "vue";
import {Line, Pie, Bar} from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LinearScale,
    TimeScale,
    LineElement,
    PointElement,
    CategoryScale,
    ArcElement,
    BarElement,
} from 'chart.js';
import 'chartjs-adapter-moment';
import axios from "axios";

ChartJS.register(
    Title, Tooltip, Legend, LineElement, TimeScale, LinearScale, PointElement,
    CategoryScale, ArcElement, BarElement
);

const props = defineProps({
    stats: Object,
});

const dashboardStats = ref(props.stats);
const loading = ref(false);
const chartKey = ref(0);

const fetchDashboardData = async (selectedTimeframe) => {
    try {
        loading.value = true;
        const response = await axios.get(route('dashboard.stats') + '?timeframe=' + selectedTimeframe);
        dashboardStats.value = response.data;
        chartKey.value++;
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
    } finally {
        loading.value = false;
    }
};

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
                spanGaps: true,
                borderWidth: 3,
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: timeUnit,
                },
                grid: {
                    display: false,
                },
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                },
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
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: 'rgba(255, 255, 255, 0.2)',
                borderWidth: 1,
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

    if (!dashboardStats.value || !dashboardStats.value.invoices) {
        return {
            labels: [],
            datasets: [{
                borderColor: '#1ED980',
                backgroundColor: 'rgba(30, 217, 128, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#1ED980',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                label: 'Revenue',
                data: [],
                fill: true,
            }]
        };
    }

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
                backgroundColor: 'rgba(30, 217, 128, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#1ED980',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                label: 'Revenue',
                data: Object.values(parsedDataset),
                fill: true,
            }
        ],
    }
});

// New chart data for payment methods
const paymentMethodsData = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.payment_methods) {
        return {
            labels: [],
            datasets: [{
                backgroundColor: ['#1E88E5', '#43A047', '#E53935', '#FB8C00', '#8E24AA'],
                data: [],
            }]
        };
    }

    const methods = dashboardStats.value.payment_methods;

    return {
        labels: methods.map(m => m.method),
        datasets: [{
            backgroundColor: ['#1E88E5', '#43A047', '#E53935', '#FB8C00', '#8E24AA', '#00ACC1', '#FFB300'],
            data: methods.map(m => m.amount)
        }]
    };
});

// New chart data for product vs service revenue
const productVsServiceData = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.product_vs_service_revenue) {
        return {
            labels: [],
            datasets: []
        };
    }

    const { products, services } = dashboardStats.value.product_vs_service_revenue;
    const allDates = [...new Set([
        ...Object.keys(products),
        ...Object.keys(services)
    ])].sort();

    return {
        labels: allDates,
        datasets: [
            {
                label: 'Products',
                backgroundColor: 'rgba(30, 136, 229, 0.7)',
                borderColor: '#1E88E5',
                borderWidth: 1,
                data: allDates.map(date => products[date]?.amount || 0)
            },
            {
                label: 'Services',
                backgroundColor: 'rgba(67, 160, 71, 0.7)',
                borderColor: '#43A047',
                borderWidth: 1,
                data: allDates.map(date => services[date]?.amount || 0)
            }
        ]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
        }
    }
};

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
    },
    {
        title: 'Total',
        align: 'start',
        sortable: true,
        key: 'total',
    }
];

const topCustomersHeaders = [
    { title: 'Customer', key: 'customer_name', align: 'start' },
    { title: 'Revenue', key: 'revenue', align: 'end' },
    { title: 'Invoices', key: 'invoice_count', align: 'end' },
];

const topProductsHeaders = [
    { title: 'Product', key: 'name', align: 'start' },
    { title: 'Revenue', key: 'revenue', align: 'end' },
    { title: 'Quantity', key: 'total_quantity', align: 'end' },
];

const topServicesHeaders = [
    { title: 'Service', key: 'name', align: 'start' },
    { title: 'Revenue', key: 'revenue', align: 'end' },
    { title: 'Quantity', key: 'total_quantity', align: 'end' },
];

const invoiceCounts = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.invoices) {
        return { all: 0, paid: 0, unpaid: 0, late: 0 };
    }

    const all = dashboardStats.value.invoices.length;
    const paid = dashboardStats.value.invoices.filter(invoice => invoice.status === 'Paid').length;
    const unpaid = dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === false && invoice.status === 'Published').length;
    const late = dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === true && invoice.status === 'Published').length;

    return { all, paid, unpaid, late };
});

const filteredInvoices = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.invoices) {
        return [];
    }

    switch (invoiceStatusFilter.value) {
        case 'paid': return dashboardStats.value.invoices.filter(invoice => invoice.status === 'Paid');
        case 'unpaid': return dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === false && invoice.status === 'Published');
        case 'late': return dashboardStats.value.invoices.filter(invoice => invoice.is_overdue === true && invoice.status === 'Published');
        default: return dashboardStats.value.invoices;
    }
});

const getStatusColor = (status) => {
    switch (status) {
        case 'all': return 'primary';
        case 'paid': return 'success';
        case 'unpaid': return 'warning';
        case 'late': return 'error';
        default: return 'primary';
    }
};

const getCount = (property) => {
    if (!dashboardStats.value || !dashboardStats.value.counts) {
        return 0;
    }
    return dashboardStats.value.counts[property] || 0;
}

const averageInvoiceValue = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.average_invoice) {
        return { average: 0, maximum: 0, minimum: 0, count: 0, currency: 'ADA' };
    }
    return dashboardStats.value.average_invoice;
});

const formatCurrency = (value, currency) => {
    if (value === 0) return '0';

    // Default formatting options
    const options = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    };

    // Special formatting for crypto currencies
    if (['ADA', 'BTC', 'ETH'].includes(currency)) {
        options.maximumFractionDigits = 6;
    }

    // Try to use Intl.NumberFormat for standard currencies when supported
    try {
        if (['USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD', 'CHF'].includes(currency)) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency,
                ...options
            }).format(value);
        }
    } catch (e) {
        console.warn('Currency formatting error:', e);
    }

    // Fallback for other currencies or if Intl formatting fails
    const formattedValue = new Intl.NumberFormat('en-US', options).format(value);
    return `${formattedValue} ${currency}`;
};

const topCustomers = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.top_customers) {
        return [];
    }
    return dashboardStats.value.top_customers;
});

const topProducts = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.top_products) {
        return [];
    }
    return dashboardStats.value.top_products;
});

const topServices = computed(() => {
    if (!dashboardStats.value || !dashboardStats.value.top_services) {
        return [];
    }
    return dashboardStats.value.top_services;
});

watch(timeframe, (newTimeframe) => {
    fetchDashboardData(newTimeframe);
});

onMounted(() => {
    if (!dashboardStats.value || !dashboardStats.value.invoices) {
        fetchDashboardData(timeframe.value);
    }
});
</script>

<template>
    <div class="dashboard-container">
        <v-container fluid class="pa-0">
            <v-row justify="center" class="mb-4">
                <v-col cols="12" sm="10" md="8" lg="6" xl="4">
                    <v-btn-toggle
                        variant="outlined"
                        v-model="timeframe"
                        mandatory
                        class="w-100 time-filter-buttons"
                        density="comfortable"
                        divided
                        rounded="xl">
                        <v-btn :value="1" prepend-icon="mdi-clock-time-one" class="flex-grow-1">24H</v-btn>
                        <v-btn :value="7" prepend-icon="mdi-calendar-week" class="flex-grow-1">7D</v-btn>
                        <v-btn :value="30" prepend-icon="mdi-calendar-month" class="flex-grow-1">1M</v-btn>
                        <v-btn :value="365" prepend-icon="mdi-calendar-star" class="flex-grow-1">12M</v-btn>
                    </v-btn-toggle>
                </v-col>
            </v-row>

            <v-skeleton-loader
                :loading="loading"
                type="table"
                class="bg-transparent"
            >
                <v-container fluid class="pa-0">
                    <!-- Stats Cards Row -->
                    <v-row align="stretch" class="stat-cards-row mx-0">
                        <v-col cols="12" sm="6" md="3" class="px-2">
                            <v-card elevation="2" rounded="lg" class="h-100 stat-card">
                                <v-card-text class="text-center d-flex flex-column justify-center align-center">
                                    <div class="stat-icon-wrapper mb-2 bg-blue-lighten-5">
                                        <v-icon size="x-large" icon="mdi-account-group" color="blue"></v-icon>
                                    </div>
                                    <p class="text-h3 font-weight-bold text-blue">{{ getCount('customers') }}</p>
                                    <p class="text-subtitle-1 text-grey">Customer{{ getCount('customers') > 1 ? 's' : '' }}</p>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="6" md="3" class="px-2">
                            <v-card elevation="2" rounded="lg" class="h-100 stat-card">
                                <v-card-text class="text-center d-flex flex-column justify-center align-center">
                                    <div class="stat-icon-wrapper mb-2 bg-purple-lighten-5">
                                        <v-icon size="x-large" icon="mdi-shopping" color="purple"></v-icon>
                                    </div>
                                    <p class="text-h3 font-weight-bold text-purple">{{ getCount('products') }}</p>
                                    <p class="text-subtitle-1 text-grey">Product{{ getCount('products') > 1 ? 's' : '' }}</p>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="6" md="3" class="px-2">
                            <v-card elevation="2" rounded="lg" class="h-100 stat-card">
                                <v-card-text class="text-center d-flex flex-column justify-center align-center">
                                    <div class="stat-icon-wrapper mb-2 bg-indigo-lighten-5">
                                        <v-icon size="x-large" icon="mdi-briefcase-variant" color="indigo"></v-icon>
                                    </div>
                                    <p class="text-h3 font-weight-bold text-indigo">{{ getCount('services') }}</p>
                                    <p class="text-subtitle-1 text-grey">Service{{ getCount('services') > 1 ? 's' : '' }}</p>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="6" md="3" class="px-2">
                            <v-card elevation="2" rounded="lg" class="h-100 stat-card">
                                <v-card-text class="text-center d-flex flex-column justify-center align-center">
                                    <div class="stat-icon-wrapper mb-2 bg-amber-lighten-5">
                                        <v-icon size="x-large" icon="mdi-file-document-multiple" color="amber-darken-2"></v-icon>
                                    </div>
                                    <div class="d-flex justify-space-around w-100">
                                        <div>
                                            <p class="text-h4 font-weight-bold text-success mb-0">{{ getCount('paid_invoices') }}</p>
                                            <p class="text-caption text-grey">Paid</p>
                                        </div>
                                        <v-divider vertical class="mx-2"></v-divider>
                                        <div>
                                            <p class="text-h4 font-weight-bold text-error mb-0">{{ getCount('unpaid_invoices') }}</p>
                                            <p class="text-caption text-grey">Unpaid</p>
                                        </div>
                                    </div>
                                    <p class="text-subtitle-1 text-grey mt-2">Total: {{ getCount('invoices') }}</p>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Average Invoice Value Card -->
                    <v-row class="mx-0 mb-4">
                        <v-col cols="12" class="px-2">
                            <v-card elevation="2" rounded="lg" class="h-100 stat-card chart-card">
                                <v-card-title class="d-flex align-center py-3 px-6">
                                    <v-icon icon="mdi-calculator-variant" color="amber-darken-2" class="me-2"></v-icon>
                                    Invoice Analysis <span class="text-caption ms-2">({{ averageInvoiceValue.currency }})</span>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="text-center d-flex justify-space-around py-4">
                                    <div class="invoice-stat-item">
                                        <div class="stat-icon-wrapper mb-2 bg-amber-lighten-5 mx-auto">
                                            <v-icon size="large" icon="mdi-cash-multiple" color="amber-darken-2"></v-icon>
                                        </div>
                                        <p class="text-h4 font-weight-bold text-amber-darken-2">
                                            {{ formatCurrency(averageInvoiceValue.average, averageInvoiceValue.currency) }}
                                        </p>
                                        <p class="text-subtitle-1 text-grey">Average Value</p>
                                        <p class="text-caption text-grey" v-if="averageInvoiceValue.count > 0">
                                            Based on {{ averageInvoiceValue.count }} invoice{{ averageInvoiceValue.count > 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                    <v-divider vertical></v-divider>
                                    <div class="invoice-stat-item">
                                        <div class="stat-icon-wrapper mb-2 bg-success-lighten-5 mx-auto">
                                            <v-icon size="large" icon="mdi-arrow-up-bold" color="success"></v-icon>
                                        </div>
                                        <p class="text-h4 font-weight-bold text-success">
                                            {{ formatCurrency(averageInvoiceValue.maximum, averageInvoiceValue.currency) }}
                                        </p>
                                        <p class="text-subtitle-1 text-grey">Highest Value</p>
                                    </div>
                                    <v-divider vertical></v-divider>
                                    <div class="invoice-stat-item">
                                        <div class="stat-icon-wrapper mb-2 bg-blue-grey-lighten-5 mx-auto">
                                            <v-icon size="large" icon="mdi-arrow-down-bold" color="blue-grey"></v-icon>
                                        </div>
                                        <p class="text-h4 font-weight-bold text-blue-grey">
                                            {{ formatCurrency(averageInvoiceValue.minimum, averageInvoiceValue.currency) }}
                                        </p>
                                        <p class="text-subtitle-1 text-grey">Lowest Value</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Charts and Data Row -->
                    <v-row class="mx-0">
                        <v-col cols="12" lg="6" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-chart-line-variant"
                                            color="primary" class="me-2"></v-icon>
                                    Revenue Generated
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-6">
                                    <div class="chart-container">
                                        <Line
                                            :key="chartKey"
                                            :data="salesByTimeData"
                                            :options="salesByTimeOptions"/>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" lg="6" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-invoice-check" color="primary" class="me-2"/>
                                    Recent Invoices
                                </v-card-title>
                                <v-divider/>
                                <v-card-text class="px-4 pt-4 pb-2">
                                    <v-btn-toggle
                                        v-model="invoiceStatusFilter"
                                        variant="outlined"
                                        class="w-100 mb-4 invoice-filter-buttons"
                                        rounded="xl"
                                        mandatory
                                        divided>
                                        <v-btn :value="'all'" :color="getStatusColor('all')" prepend-icon="mdi-view-list" class="flex-grow-1">
                                            All
                                            <v-badge :color="getStatusColor('all')" :content="invoiceCounts.all" inline />
                                        </v-btn>
                                        <v-btn :value="'paid'" :color="getStatusColor('paid')" prepend-icon="mdi-check-circle" class="flex-grow-1">
                                            Paid
                                            <v-badge :color="getStatusColor('paid')" :content="invoiceCounts.paid" inline />
                                        </v-btn>
                                        <v-btn :value="'unpaid'" :color="getStatusColor('unpaid')" prepend-icon="mdi-clock-outline" class="flex-grow-1">
                                            Unpaid
                                            <v-badge :color="getStatusColor('unpaid')" :content="invoiceCounts.unpaid" inline />
                                        </v-btn>
                                        <v-btn :value="'late'" :color="getStatusColor('late')" prepend-icon="mdi-alert-circle" class="flex-grow-1">
                                            Late
                                            <v-badge :color="getStatusColor('late')" :content="invoiceCounts.late" inline />
                                        </v-btn>
                                    </v-btn-toggle>
                                </v-card-text>
                                <v-card-text class="px-4 pb-4 pt-0">
                                    <v-data-table
                                        :items="filteredInvoices"
                                        :headers="invoicesHeaders"
                                        density="comfortable"
                                        class="invoice-table"
                                        hover>
                                        <template v-slot:item.status="{ item }">
                                            <v-chip
                                                :color="item.status === 'Paid' ? 'success' : (item.is_overdue ? 'error' : 'warning')"
                                                size="small"
                                                variant="flat"
                                                class="text-capitalize"
                                            >
                                                {{ item.status }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item.due_date="{ item }">
                                            {{ new Date(item.due_date).toLocaleDateString() }}
                                        </template>
                                        <template v-slot:item.total="{ item }">
                                            {{ formatCurrency(parseFloat(item.total), item.currency) }}
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                                <v-card-actions class="justify-end px-4 pb-4">
                                    <v-btn
                                        :href="route('invoices.index')"
                                        variant="flat"
                                        color="primary"
                                        prepend-icon="mdi-invoice"
                                        append-icon="mdi-chevron-right"
                                        class="px-6"
                                        rounded="lg">
                                        See All Invoices
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Payment Methods & Product vs Service Revenue -->
                    <v-row class="mx-0 mt-4">
                        <v-col cols="12" md="6" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-cash-multiple" color="blue" class="me-2"></v-icon>
                                    Payment Methods
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-6">
                                    <div class="chart-container" style="height: 300px;">
                                        <Pie
                                            :key="chartKey"
                                            :data="paymentMethodsData"
                                            :options="chartOptions"/>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-chart-bar" color="deep-purple" class="me-2"></v-icon>
                                    Products vs Services Revenue
                                    <span class="text-caption ms-2" v-if="dashboardStats.value?.product_vs_service_revenue?.currency">
                                        ({{ dashboardStats.value.product_vs_service_revenue.currency }})
                                    </span>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-6">
                                    <div class="chart-container" style="height: 300px;">
                                        <Bar
                                            :key="chartKey"
                                            :data="productVsServiceData"
                                            :options="chartOptions"/>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Top Customers, Products, and Services -->
                    <v-row class="mx-0 mt-4">
                        <v-col cols="12" lg="4" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-account-star" color="indigo" class="me-2"></v-icon>
                                    Top Customers
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-4">
                                    <v-data-table
                                        :items="topCustomers"
                                        :headers="topCustomersHeaders"
                                        density="comfortable"
                                        class="top-customers-table"
                                        hover>
                                        <template v-slot:item.revenue="{ item }">
                                            {{ formatCurrency(parseFloat(item.revenue), averageInvoiceValue.currency) }}
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" lg="4" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-package-variant" color="deep-orange" class="me-2"></v-icon>
                                    Top Products
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-4">
                                    <v-data-table
                                        :items="topProducts"
                                        :headers="topProductsHeaders"
                                        density="comfortable"
                                        class="top-products-table"
                                        hover>
                                        <template v-slot:item.revenue="{ item }">
                                            {{ formatCurrency(parseFloat(item.revenue), averageInvoiceValue.currency) }}
                                        </template>
                                        <template v-slot:item.total_quantity="{ item }">
                                            {{ parseFloat(item.total_quantity).toFixed(0) }}
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" lg="4" class="px-2">
                            <v-card elevation="2" rounded="lg" class="chart-card h-100">
                                <v-card-title class="d-flex align-center py-4 px-6">
                                    <v-icon icon="mdi-briefcase-check" color="teal" class="me-2"></v-icon>
                                    Top Services
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="px-4 py-4">
                                    <v-data-table
                                        :items="topServices"
                                        :headers="topServicesHeaders"
                                        density="comfortable"
                                        class="top-services-table"
                                        hover>
                                        <template v-slot:item.revenue="{ item }">
                                            {{ formatCurrency(parseFloat(item.revenue), averageInvoiceValue.currency) }}
                                        </template>
                                        <template v-slot:item.total_quantity="{ item }">
                                            {{ parseFloat(item.total_quantity).toFixed(0) }}
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-skeleton-loader>
        </v-container>
    </div>
</template>

<style scoped>
.dashboard-container {
    width: 100%;
    max-width: 100%;
    padding: 16px;
}

.stat-cards-row {
    margin-bottom: 16px;
}

.stat-card {
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.stat-icon-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 64px;
    height: 64px;
    border-radius: 16px;
    margin-bottom: 12px;
}

.invoice-stat-item {
    flex: 1;
    padding: 0 16px;
}

.chart-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.chart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.chart-container {
    height: 350px;
    max-height: 50vh;
    width: 100%;
}

.time-filter-buttons, .invoice-filter-buttons {
    transition: all 0.3s;
}

.time-filter-buttons .v-btn, .invoice-filter-buttons .v-btn {
    transition: all 0.2s;
}

.time-filter-buttons .v-btn:hover, .invoice-filter-buttons .v-btn:hover {
    transform: translateY(-2px);
}

.v-data-table {
    border-radius: 8px;
    overflow: hidden;
}

@media (min-width: 1600px) {
    .chart-container {
        height: 400px;
    }

    .stat-icon-wrapper {
        width: 80px;
        height: 80px;
    }
}

@media (max-width: 600px) {
    .dashboard-container {
        padding: 8px;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
    }

    .chart-container {
        height: 300px;
    }

    .v-btn-toggle {
        flex-wrap: wrap;
    }

    .v-btn-toggle .v-btn {
        flex-basis: calc(50% - 4px);
        margin: 2px;
    }
}
</style>
