<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({errors: Object, invoice: Object})

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
        router.visit(route('invoices.void', invoice.invoice_reference), { method: 'get' });
    }
}

</script>

<template>
    <app-layout title="View Invoice">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
                <span>View Invoice {{ invoice.invoice_reference }}</span>
                <span :class="`font-medium status-${invoice.status}`">{{ invoice.status }}</span>
            </h2>
        </template>
        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">Details</h1>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Customer
                            </label>
                            <input
                                :value="invoice.customer.name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                disabled
                                readonly />
                            <span class="sm-badge">Tax Rate {{ parseFloat(invoice.customer.tax_rate ?? 0) }}%</span>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Notification Recipients
                            </label>
                            <span v-for="(recipient, index) in invoice.recipients" :key="recipient.id" class="badge">
                                #{{ index + 1 }} {{ recipient.name }} ({{ recipient.address }})
                            </span>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Billing Address
                            </label>
                            <input
                                v-if="invoice.billing_address"
                                :value="[invoice.billing_address.line1, invoice.billing_address.line2, invoice.billing_address.city, invoice.billing_address.state, invoice.billing_address.postal_code, invoice.billing_address.country].filter(n => n).join(', ')"
                                class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                disabled
                                readonly />
                            <input
                                v-else
                                value="None Specified"
                                class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                disabled
                                readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Billing Address
                            </label>
                            <input
                                v-if="invoice.shipping_address"
                                :value="[invoice.shipping_address.line1, invoice.shipping_address.line2, invoice.shipping_address.city, invoice.shipping_address.state, invoice.shipping_address.postal_code, invoice.shipping_address.country].filter(n => n).join(', ')"
                                class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                disabled
                                readonly />
                            <input
                                v-else
                                value="None Specified"
                                class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                disabled
                                readonly />
                        </div>

                        <table>
                            <tr>
                                <td>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Customer Reference
                                        </label>
                                        <input
                                            placeholder="None Specified"
                                            :value="invoice.customer_reference"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Issue Date
                                        </label>
                                        <input
                                            :value="invoice.issue_date"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Due Date <span v-if="invoice.is_overdue" class="sm-badge-red">Late</span>
                                        </label>
                                        <input
                                            :value="invoice.due_date"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                        <table class="border-separate border-spacing-2">
                            <thead>
                                <tr>
                                    <th class="text-left" style="width: 25px;"></th>
                                    <th class="text-left" style="width: 125px;">SKU</th>
                                    <th class="text-left">Description</th>
                                    <th class="text-left" style="width: 125px;">Quantity</th>
                                    <th class="text-left" style="width: 125px;">Unit Price</th>
                                    <th class="text-left" style="width: 125px;">Tax Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in invoice.items" :key="index">
                                    <td>
                                        #{{ index + 1 }}
                                    </td>
                                    <td>
                                        <input
                                            :value="item.sku"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </td>
                                    <td>
                                        <input
                                            :value="item.description"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </td>
                                    <td>
                                        <input
                                            :value="parseFloat(item.quantity)"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </td>
                                    <td>
                                        <input
                                            :value="parseFloat(item.unit_price)"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </td>
                                    <td>
                                        <input
                                            :value="parseInt(item.tax_rate)"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-100 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            disabled
                                            readonly />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table>
                            <tr>
                                <td>
                                    <div v-if="invoice.status === 'Draft'" class="flex gap-2">
                                        <Link :href="route('invoices.edit', invoice.invoice_reference)">
                                            <button type="button" class="btn btn-blue">Edit Invoice</button>
                                        </Link>

                                        <button type="button" class="btn btn-red" @click="voidInvoice(props.invoice)">
                                            Void Invoice
                                        </button>
                                    </div>

                                    <div v-if="invoice.status === 'Published'" class="flex gap-2">
                                        <Link :href="route('invoices.sendReminderNotifications', invoice.invoice_reference)">
                                            <button type="button" class="btn btn-blue">Send Reminder Notifications</button>
                                        </Link>

                                        <Link :href="route('invoices.manuallyMarkAsPaid', invoice.invoice_reference)">
                                            <button type="button" class="btn btn-orange">Manually Mark as Paid</button>
                                        </Link>
                                    </div>

                                    <div v-if="invoice.status === 'Voided'" class="flex gap-2">
                                        <Link :href="route('invoices.restore', invoice.invoice_reference)">
                                            <button type="button" class="btn btn-blue">Restore Invoice</button>
                                        </Link>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <p>Subtotal <strong>{{  calculateSubTotal().toFixed(2) }}</strong></p>
                                    <p>Total Tax <strong>{{ calculateTotalTax().toFixed(2) }}</strong></p>
                                    <p>Total Due <strong>{{ calculateGrandTotal().toFixed(2) }}</strong></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">Payments</h1>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table>
                            <thead>
                                <tr>
                                    <th class="p-1 bg-gray-100 text-left rounded-l">Date</th>
                                    <th class="p-1 bg-gray-100 text-left">Method</th>
                                    <th class="p-1 bg-gray-100 text-left">Currency</th>
                                    <th class="p-1 bg-gray-100 text-left">Amount</th>
                                    <th class="p-1 bg-gray-100 text-left">Reference</th>
                                    <th class="p-1 bg-gray-100 text-left rounded-r">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="invoice.payments.length === 0">
                                    <td colspan="6" class="text-gray-600 text-center pt-1">
                                        There are no payment records against this invoice
                                    </td>
                                </tr>
                                <tr v-for="payment in invoice.payments" :key="payment.id">
                                    <td class="p-1 border-b">{{ payment.payment_date }}</td>
                                    <td class="p-1 border-b">{{ payment.payment_method }}</td>
                                    <td class="p-1 border-b">{{ payment.payment_currency }}</td>
                                    <td class="p-1 border-b">{{ payment.payment_amount }}</td>
                                    <td class="p-1 border-b">{{ payment.payment_reference }}</td>
                                    <td class="p-1 border-b">{{ payment.status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">Activities</h1>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table>
                            <thead>
                            <tr>
                                <th class="p-1 bg-gray-100 text-left rounded-l w-1/4">Date & Time</th>
                                <th class="p-1 bg-gray-100 text-left rounded-r">Activity</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in invoice.activities" :key="activity.id">
                                    <td class="p-1 border-b">
                                        {{ activity.formatted_datetime.datetime }}
                                        <span class="sm-badge">{{ activity.formatted_datetime.diff }}</span>
                                    </td>
                                    <td class="p-1 border-b">{{ activity.activity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </app-layout>
</template>
