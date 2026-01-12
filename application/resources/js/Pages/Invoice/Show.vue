<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({ errors: Object, invoice: Object });

// Active tab management
const activeTab = ref('details');

const item_headers = [
    { title: 'SKU', align: 'start', sortable: true, key: 'sku', width: '140px' },
    { title: 'Description', align: 'start', sortable: false, key: 'description' },
    { title: 'Qty', align: 'end', sortable: true, key: 'quantity', width: '100px' },
    { title: `Unit Price`, align: 'end', sortable: true, key: 'unit_price', width: '140px' },
    { title: 'Tax %', align: 'end', sortable: true, key: 'tax_rate', width: '100px' },
    { title: 'Line Total', align: 'end', sortable: false, key: 'line_total', width: '150px' },
];

const payment_headers = [
    { title: 'Date', align: 'start', sortable: true, key: 'payment_date' },
    { title: 'Method', align: 'start', sortable: true, key: 'payment_method' },
    { title: 'Currency', align: 'start', sortable: true, key: 'payment_currency' },
    { title: 'Amount', align: 'end', sortable: true, key: 'payment_amount' },
    { title: 'Reference', align: 'start', sortable: true, key: 'payment_reference' },
    { title: 'Status', align: 'center', sortable: true, key: 'status', width: '120px' },
];

const activity_headers = [
    { title: 'Date & Time', align: 'start', sortable: true, key: 'formatted_datetime.datetime', width: '220px' },
    { title: 'Activity', align: 'start', sortable: true, key: 'activity' },
];

const billing_address = computed(() => {
    return makeFormattedAddress(props.invoice.billing_address);
});

const shipping_address = computed(() => {
    return makeFormattedAddress(props.invoice.shipping_address);
});

const getAddressLines = (address) => {
    let response = ``;
    if (address === null || address === undefined) {
        return response;
    }
    if (address.line1) {
        response += `${address.line1}\n`;
    }
    if (address.line2) {
        response += `${address.line2}\n`;
    }
    if (address.line3) {
        response += `${address.line3}\n`;
    }
    return response;
};

const makeFormattedAddress = (address) => {
    if (address === null || address === undefined) {
        return null;
    }
    const lines = getAddressLines(address);
    return `${lines}${address.city}, ${address.state} ${address.postal_code}\n${address.country}`;
};

const calculateSubTotal = () => {
    let result = 0.00;
    props.invoice.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        if (!isNaN(lineSubTotal)) {
            result += lineSubTotal;
        }
    });
    return result;
};

const calculateTotalTax = () => {
    let result = 0.00;
    props.invoice.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        const lineTaxRate = parseFloat(item.tax_rate);
        if (!isNaN(lineSubTotal) && !isNaN(lineTaxRate)) {
            result += (lineSubTotal * (lineTaxRate / 100));
        }
    });
    return result;
};

const calculateGrandTotal = () => {
    return (calculateSubTotal() + calculateTotalTax());
};

const calculateLineTotal = (item) => {
    const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
    const lineTaxRate = parseFloat(item.tax_rate);
    if (!isNaN(lineSubTotal) && !isNaN(lineTaxRate)) {
        return lineSubTotal + (lineSubTotal * (lineTaxRate / 100));
    }
    return lineSubTotal || 0;
};

function voidInvoice(invoice) {
    const response = confirm(`Are you sure you want to void this invoice: ${invoice.invoice_reference}?`);
    if (response) {
        router.visit(route('invoices.void', invoice.invoice_reference), { method: 'get' });
    }
}

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
            return 'mdi-file-document-edit';
        case 'Published':
            return 'mdi-send';
        case 'Payment Processing':
            return 'mdi-clock-outline';
        case 'Paid':
            return 'mdi-check-circle';
        case 'Voided':
            return 'mdi-cancel';
        default:
            return 'mdi-circle';
    }
};

const getPaymentStatusColor = (status) => {
    switch (status) {
        case 'Success':
            return 'success';
        case 'Pending':
            return 'warning';
        case 'Error':
        case 'Timed Out':
            return 'error';
        default:
            return 'secondary';
    }
};
</script>

<template>
    <AppLayout title="View Invoice">
        <template #header>
            <PageHeader
                :title="`Invoice #${invoice.invoice_reference}`"
                :subtitle="invoice.customer.name"
                icon="mdi-file-document"
            >
                <template #actions>
                    <v-chip
                        :color="getStatusColor(invoice.status)"
                        variant="flat"
                        size="large"
                    >
                        <v-icon start :icon="getStatusIcon(invoice.status)" size="small" />
                        {{ invoice.status }}
                    </v-chip>
                </template>
            </PageHeader>
        </template>

        <v-card class="invoice-view-card">
            <v-tabs v-model="activeTab" color="primary">
                <v-tab value="details" prepend-icon="mdi-text-box">
                    Details
                </v-tab>
                <v-tab value="items" prepend-icon="mdi-format-list-bulleted">
                    Items
                    <v-badge
                        v-if="invoice.items?.length"
                        :content="invoice.items.length"
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
                <v-tab value="payments" prepend-icon="mdi-credit-card">
                    Payments
                    <v-badge
                        v-if="invoice.payments?.length"
                        :content="invoice.payments.length"
                        color="success"
                        inline
                        class="ml-2"
                    />
                </v-tab>
                <v-tab value="activity" prepend-icon="mdi-history">
                    Activity
                    <v-badge
                        v-if="invoice.activities?.length"
                        :content="invoice.activities.length"
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
            </v-tabs>

            <v-divider />

            <v-card-text class="pa-4 pa-sm-6">
                <v-window v-model="activeTab">
                    <!-- Details Tab -->
                    <v-window-item value="details">
                        <!-- Key Info Cards -->
                        <v-row class="mb-4">
                            <v-col cols="12" sm="6" lg="4">
                                <v-card variant="tonal" class="h-100" flat>
                                    <v-card-text>
                                        <div class="text-overline text-medium-emphasis mb-1">
                                            <v-icon icon="mdi-account" size="small" class="mr-1" />
                                            Customer
                                        </div>
                                        <div class="text-h6 font-weight-bold mb-2">
                                            {{ invoice.customer.name }}
                                        </div>
                                        <v-chip v-if="invoice.customer.tax_rate" size="small" variant="outlined" color="primary">
                                            <v-icon start icon="mdi-percent" size="x-small" />
                                            Tax Rate: {{ parseFloat(invoice.customer.tax_rate ?? 0) }}%
                                        </v-chip>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" sm="6" lg="4">
                                <v-card variant="tonal" class="h-100" flat>
                                    <v-card-text>
                                        <div class="text-overline text-medium-emphasis mb-1">
                                            <v-icon icon="mdi-calendar" size="small" class="mr-1" />
                                            Dates
                                        </div>
                                        <div class="d-flex flex-column ga-1">
                                            <div class="d-flex justify-space-between">
                                                <span class="text-body-2 text-medium-emphasis">Issue Date:</span>
                                                <span class="text-body-2 font-weight-medium">{{ invoice.issue_date }}</span>
                                            </div>
                                            <div class="d-flex justify-space-between">
                                                <span class="text-body-2 text-medium-emphasis">Due Date:</span>
                                                <span class="text-body-2 font-weight-medium">{{ invoice.due_date }}</span>
                                            </div>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" lg="4">
                                <v-card variant="tonal" color="primary" class="h-100" flat>
                                    <v-card-text>
                                        <div class="text-overline mb-1">
                                            <v-icon icon="mdi-cash" size="small" class="mr-1" />
                                            Total Due
                                        </div>
                                        <div class="text-h4 font-weight-black">
                                            {{ calculateGrandTotal().toFixed(2) }}
                                            <span class="text-h6">{{ invoice.currency }}</span>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <!-- Customer Reference -->
                        <v-card v-if="invoice.customer_reference" variant="outlined" class="mb-4" flat>
                            <v-card-text class="d-flex align-center py-3">
                                <v-icon icon="mdi-pound" class="mr-3" color="primary" />
                                <div>
                                    <div class="text-overline text-medium-emphasis">Customer Reference</div>
                                    <div class="text-body-1 font-weight-medium font-mono">{{ invoice.customer_reference }}</div>
                                </div>
                            </v-card-text>
                        </v-card>

                        <!-- Notification Recipients -->
                        <div v-if="invoice.recipients.length > 0" class="mb-4">
                            <div class="text-overline text-medium-emphasis mb-2">
                                <v-icon icon="mdi-email-multiple" size="small" class="mr-1" />
                                Notification Recipients
                            </div>
                            <div class="d-flex flex-wrap ga-2">
                                <v-chip
                                    v-for="recipient in invoice.recipients"
                                    :key="recipient.id"
                                    variant="tonal"
                                    color="primary"
                                    size="small"
                                >
                                    <v-icon start icon="mdi-account" size="small" />
                                    {{ recipient.name }} &lt;{{ recipient.address }}&gt;
                                </v-chip>
                            </div>
                        </div>

                        <!-- Addresses -->
                        <v-row v-if="billing_address || shipping_address">
                            <v-col cols="12" md="6" v-if="billing_address">
                                <v-card variant="outlined" class="h-100" rounded="lg">
                                    <v-card-title class="text-body-2 pb-0">
                                        <v-icon icon="mdi-map-marker" class="mr-2" color="primary" size="small" />
                                        Billing Address
                                    </v-card-title>
                                    <v-card-text class="pt-2">
                                        <div class="text-body-2 address-text">{{ billing_address }}</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" md="6" v-if="shipping_address">
                                <v-card variant="outlined" class="h-100" rounded="lg">
                                    <v-card-title class="text-body-2 pb-0">
                                        <v-icon icon="mdi-truck-delivery" class="mr-2" color="primary" size="small" />
                                        Shipping Address
                                    </v-card-title>
                                    <v-card-text class="pt-2">
                                        <div class="text-body-2 address-text">{{ shipping_address }}</div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>

                    <!-- Items Tab -->
                    <v-window-item value="items">
                        <v-data-table
                            :items="invoice.items"
                            :headers="item_headers"
                            disable-pagination
                            hide-default-footer
                            density="comfortable"
                            class="invoice-items-table"
                        >
                            <template #item.sku="{ item }">
                                <span class="font-mono text-body-2">{{ item.sku || '—' }}</span>
                            </template>
                            <template #item.quantity="{ item }">
                                <span class="font-weight-medium">{{ parseFloat(item.quantity).toFixed(2) }}</span>
                            </template>
                            <template #item.unit_price="{ item }">
                                <span class="font-mono">{{ parseFloat(item.unit_price).toFixed(2) }}</span>
                            </template>
                            <template #item.tax_rate="{ item }">
                                <v-chip size="x-small" variant="tonal" color="primary">
                                    {{ parseFloat(item.tax_rate).toFixed(0) }}%
                                </v-chip>
                            </template>
                            <template #item.line_total="{ item }">
                                <span class="font-weight-bold font-mono">{{ calculateLineTotal(item).toFixed(2) }}</span>
                            </template>
                        </v-data-table>

                        <!-- Totals Summary -->
                        <v-row justify="end" class="mt-4">
                            <v-col cols="12" sm="8" md="6" lg="4">
                                <v-card variant="tonal" color="primary" rounded="lg">
                                    <v-card-text class="pa-4">
                                        <div class="d-flex justify-space-between mb-2">
                                            <span class="text-body-2">Subtotal</span>
                                            <span class="text-body-2 font-weight-bold font-mono">
                                                {{ calculateSubTotal().toFixed(2) }} {{ invoice.currency }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-space-between mb-3">
                                            <span class="text-body-2">Tax</span>
                                            <span class="text-body-2 font-weight-bold font-mono">
                                                {{ calculateTotalTax().toFixed(2) }} {{ invoice.currency }}
                                            </span>
                                        </div>
                                        <v-divider class="mb-3" />
                                        <div class="d-flex justify-space-between">
                                            <span class="text-body-1 font-weight-bold">Total Due</span>
                                            <span class="text-h6 font-weight-black font-mono">
                                                {{ calculateGrandTotal().toFixed(2) }} {{ invoice.currency }}
                                            </span>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>

                    <!-- Payments Tab -->
                    <v-window-item value="payments">
                        <v-data-table
                            v-if="invoice.payments && invoice.payments.length > 0"
                            :items="invoice.payments"
                            :headers="payment_headers"
                            disable-pagination
                            hide-default-footer
                            density="comfortable"
                        >
                            <template #item.payment_method="{ item }">
                                <v-chip size="small" variant="tonal" :color="item.payment_method === 'Crypto' ? 'warning' : 'info'">
                                    <v-icon start :icon="item.payment_method === 'Crypto' ? 'mdi-bitcoin' : 'mdi-credit-card'" size="small" />
                                    {{ item.payment_method }}
                                </v-chip>
                            </template>
                            <template #item.payment_amount="{ item }">
                                <template v-if="item.payment_method === 'Crypto'">
                                    <div class="font-mono font-weight-bold">{{ item.crypto_asset_quantity }}</div>
                                    <div class="text-caption text-medium-emphasis">
                                        (1 {{ invoice.currency }} = {{ item.crypto_asset_ada_price }} ₳DA)
                                    </div>
                                </template>
                                <template v-else>
                                    <span class="font-mono font-weight-bold">{{ item.payment_amount }}</span>
                                </template>
                            </template>
                            <template #item.status="{ item }">
                                <v-chip size="small" :color="getPaymentStatusColor(item.status)">
                                    {{ item.status }}
                                </v-chip>
                            </template>
                        </v-data-table>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-credit-card-off" size="48" color="primary" class="mb-4 opacity-50" />
                            <h3 class="text-h6 mb-2">No Payments Yet</h3>
                            <p class="text-body-2 text-medium-emphasis">
                                Payments will appear here once the invoice is paid.
                            </p>
                        </div>
                    </v-window-item>

                    <!-- Activity Tab -->
                    <v-window-item value="activity">
                        <v-data-table
                            v-if="invoice.activities && invoice.activities.length > 0"
                            :items="invoice.activities"
                            :headers="activity_headers"
                            density="comfortable"
                        >
                            <template #item.formatted_datetime.datetime="{ item }">
                                <div class="d-flex align-center">
                                    <v-icon icon="mdi-clock-outline" size="small" class="mr-2 text-medium-emphasis" />
                                    <div>
                                        <div class="font-weight-medium text-body-2">{{ item.formatted_datetime.datetime }}</div>
                                        <div class="text-caption text-medium-emphasis">{{ item.formatted_datetime.diff }}</div>
                                    </div>
                                </div>
                            </template>
                            <template #item.activity="{ item }">
                                <div class="text-body-2">{{ item.activity }}</div>
                            </template>
                        </v-data-table>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-history" size="48" color="primary" class="mb-4 opacity-50" />
                            <h3 class="text-h6 mb-2">No Activity</h3>
                            <p class="text-body-2 text-medium-emphasis">
                                Invoice activity will be logged here.
                            </p>
                        </div>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>

        <!-- Action Bar -->
        <v-card class="mt-4" flat>
            <v-card-text class="d-flex flex-wrap justify-space-between align-center ga-3 py-3">
                <v-btn
                    :href="route('invoices.index')"
                    variant="text"
                    color="secondary"
                    prepend-icon="mdi-arrow-left"
                >
                    Back to Invoices
                </v-btn>
                <div class="d-flex flex-wrap ga-2">
                    <template v-if="invoice.status === 'Draft'">
                        <v-btn
                            variant="tonal"
                            color="primary"
                            prepend-icon="mdi-pencil"
                            :href="route('invoices.edit', invoice.invoice_reference)"
                        >
                            Edit
                        </v-btn>
                        <v-btn
                            variant="tonal"
                            color="error"
                            prepend-icon="mdi-cancel"
                            @click="voidInvoice(invoice)"
                        >
                            Void
                        </v-btn>
                    </template>
                    <template v-if="invoice.status === 'Published'">
                        <v-btn
                            variant="tonal"
                            color="info"
                            prepend-icon="mdi-email-send"
                            :href="route('invoices.sendReminderNotifications', invoice.id)"
                        >
                            Send Reminder
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="success"
                            prepend-icon="mdi-cash-check"
                            :href="route('invoices.manuallyMarkAsPaid', invoice.invoice_reference)"
                        >
                            Mark as Paid
                        </v-btn>
                    </template>
                    <template v-if="invoice.status === 'Voided'">
                        <v-btn
                            variant="flat"
                            color="warning"
                            prepend-icon="mdi-restore"
                            :href="route('invoices.restore', invoice.invoice_reference)"
                        >
                            Restore
                        </v-btn>
                    </template>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.font-mono {
    font-family: 'Source Code Pro', monospace;
}

.address-text {
    white-space: pre-line;
    line-height: 1.5;
}

.invoice-view-card {
    max-width: none !important;
}

.invoice-items-table :deep(th) {
    background: rgb(var(--v-theme-surface-variant)) !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    font-size: 0.7rem !important;
    letter-spacing: 0.025em;
}

.invoice-items-table :deep(td) {
    vertical-align: middle;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .invoice-items-table :deep(th),
    .invoice-items-table :deep(td) {
        padding: 8px 4px !important;
        font-size: 0.75rem !important;
    }
}
</style>
