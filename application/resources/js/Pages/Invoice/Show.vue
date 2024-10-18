<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, router} from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps({errors: Object, invoice: Object});

const item_headers = [
    {
        title: 'SKU',
        align: 'start',
        sortable: true,
        key: 'sku'
    },
    {
        title: 'Description',
        align: 'start',
        sortable: false,
        key: 'description'
    },
    {
        title: 'Quantity',
        align: 'start',
        sortable: true,
        key: 'quantity'
    },
    {
        title: `Unit Price (${props.invoice.currency})`,
        align: 'start',
        sortable: true,
        key: 'unit_price'
    },
    {
        title: 'Tax Rate',
        align: 'start',
        sortable: true,
        key: 'tax_rate'
    }
];

const payment_headers = [
    {
        title: 'Date',
        align: 'start',
        sortable: true,
        key: 'payment_date'
    },
    {
        title: 'Method',
        align: 'start',
        sortable: true,
        key: 'payment_method'
    },
    {
        title: 'Currency',
        align: 'start',
        sortable: true,
        key: 'payment_currency'
    },
    {
        title: 'Amount',
        align: 'start',
        sortable: true,
        key: 'payment_amount'
    },
    {
        title: 'Reference',
        align: 'start',
        sortable: true,
        key: 'payment_reference'
    },
    {
        title: 'Status',
        align: 'start',
        sortable: true,
        key: 'status'
    },
];

const activity_headers = [
    {
        title: 'Date & Time',
        align: 'start',
        sortable: true,
        key: 'formatted_datetime.datetime'
    },
    {
        title: 'Activity',
        align: 'start',
        sortable: true,
        key: 'activity'
    }
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
        response += `${address.line1}\r`;
    }
    if (address.line2) {
        response += `${address.line2}\r`;
    }
    if (address.line3) {
        response += `${address.line3}\r`;
    }
    return response;
}

const makeFormattedAddress = (address) => {
    if (address === null || address === undefined) {
        return ``;
    }
    const lines = getAddressLines(address);
    return `${lines}
${address.city}, ${address.state} ${address.postal_code}
${address.country}`;
}

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

function voidInvoice(invoice) {
    const response = confirm(`Are you sure you want to void this invoice: ${invoice.invoice_reference}?`);
    if (response) {
        router.visit(route('invoices.void', invoice.invoice_reference), {method: 'get'});
    }
}

</script>

<template>
    <app-layout title="View Invoice">
        <template #header>
            <h1>
                View Invoice:
                {{ invoice.invoice_reference }}
                <v-chip label>{{ invoice.status }}</v-chip>
            </h1>
            <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
                            <span>View Invoice {{ invoice.invoice_reference }}</span>
                            <span :class="`font-medium status-${invoice.status.replace(' ', '_')}`">{{ invoice.status }}</span>
                        </h2>-->
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <h2>Details</h2>
            <v-text-field v-model="invoice.customer.name" disabled readonly
                          label="Customer">
                <template v-slot:append-inner>
                    <v-chip label>Tax Rate
                        {{ parseFloat(invoice.customer.tax_rate ?? 0) }}%
                    </v-chip>
                </template>
            </v-text-field>
            <v-list v-if="invoice.recipients.length > 0">
                <v-list-subheader>Notification Recipients</v-list-subheader>
                <v-list-item v-for="(recipient, index) in invoice.recipients"
                             :key="recipient.id">
                    #{{ index + 1 }} {{ recipient.name }}
                    ({{ recipient.address }})
                </v-list-item>
            </v-list>
            <v-row>
                <v-col cols="12" md="6">
                    <v-textarea label="Billing Address"
                                v-model="billing_address" readonly disabled/>
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea label="Shipping Address"
                                v-model="shipping_address" readonly disabled/>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="4">
                    <v-text-field label="Customer Reference"
                                  v-model="invoice.customer_reference"
                                  readonly disabled/>
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field label="Issue Date"
                                  v-model="invoice.issue_date" readonly
                                  disabled/>
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field label="Due Date" v-model="invoice.due_date"
                                  readonly disabled/>
                </v-col>
            </v-row>
            <h2 class="mt-4">Items</h2>
            <v-data-table :items="invoice.items" :headers="item_headers"
                          disable-pagination hide-default-footer/>
            <v-row justify="end" align="end">
                <v-col>
                    <v-row>
                        <template v-if="invoice.status === 'Draft'">
                            <v-col>
                                <v-btn
                                    :href="route('invoices.edit', invoice.invoice_reference)">
                                    Edit Invoice
                                </v-btn>
                            </v-col>
                            <v-col>
                                <v-btn color="danger"
                                       @click="voidInvoice(invoice)">
                                    Void Invoice
                                </v-btn>
                            </v-col>
                        </template>
                        <template v-if="invoice.status === 'Published'">
                            <v-col>
                                <v-btn
                                    :href="route('invoices.sendReminderNotifications', invoice.id)">
                                    Send Reminder Notification
                                </v-btn>
                            </v-col>
                            <v-col>
                                <v-btn
                                    :href="route('invoices.manuallyMarkAsPaid', invoice.invoice_reference)">
                                    Manually Mark Paid
                                </v-btn>
                            </v-col>
                        </template>
                        <template v-if="invoice.status === 'Voided'">
                            <v-btn
                                :href="route('invoices.restore', invoice.invoice_reference)">
                                Restore Invoice
                            </v-btn>
                        </template>
                    </v-row>
                </v-col>
                <v-col cols="auto">
                    <v-table density="comfortable">
                        <tbody>
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-end font-weight-black">
                                {{ calculateSubTotal().toFixed(2) }}
                                {{ invoice.currency }}
                            </td>
                        </tr>
                        <tr>
                            <td>Total Tax</td>
                            <td class="text-end font-weight-black">
                                {{ calculateTotalTax().toFixed(2) }}
                                {{ invoice.currency }}
                            </td>
                        </tr>
                        <tr>
                            <td>Total Due</td>
                            <td class="text-end font-weight-black">
                                {{ calculateGrandTotal().toFixed(2) }}
                                {{ invoice.currency }}
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-col>
            </v-row>
            <h2 class="mt-4">Payments</h2>
            <v-data-table :items="invoice.payments" :headers="payment_headers"
                          disable-pagination hide-default-footer
                          density="comfortable">
                <template v-slot:item.payment_amount="{ item }">
                    <template v-if="item.payment_method === 'Crypto'">
                        {{ item.crypto_asset_quantity }}<br/>
                        (1 {{ invoice.currency }} =
                        {{ item.crypto_asset_ada_price }} ₳DA)
                    </template>
                    <template v-else>
                        {{ item.payment_amount }}
                    </template>
                </template>
            </v-data-table>
            <h2 class="mt-4">Activity Log</h2>
            <v-data-table :items="invoice.activities"
                          :headers="activity_headers" density="compact">
                <template v-slot:item.formatted_datetime.datetime="{ item }">
                    {{ item.formatted_datetime.datetime }}
                    ({{ item.formatted_datetime.diff }})
                </template>
            </v-data-table>
        </v-sheet>
    </app-layout>
</template>
