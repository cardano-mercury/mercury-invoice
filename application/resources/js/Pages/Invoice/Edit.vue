<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, router, useForm} from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps({
    errors: Object,
    invoice: Object,
    customers: Array,
    products: Array,
    services: Array,
});

const form = useForm({
    customer_id: props.invoice.customer_id,
    customer_email_ids: props.invoice.recipients.map(item => item.id),
    billing_address_id: (props.invoice.billing_address ? props.invoice.billing_address.id : null),
    shipping_address_id: (props.invoice.shipping_address ? props.invoice.shipping_address.id : null),
    customer_reference: props.invoice.customer_reference,
    issue_date: props.invoice.issue_date,
    due_date: props.invoice.due_date,
    items: props.invoice.items.map(item => {
        return {
            product_id: item.product_id,
            service_id: item.service_id,
            sku: item.sku,
            description: item.description,
            quantity: parseFloat(item.quantity),
            unit_price: parseFloat(item.unit_price),
            tax_rate: parseInt(item.tax_rate),
        };
    }),
    save_mode: null,
});

const selectedProduct = ref('');
const selectedService = ref('');

const selectedCustomer = () => {
    if (form.customer_id) {
        return props.customers.filter(customer => customer.id === form.customer_id)[0] ?? null;
    }
    return null;
};

const addSelectedProduct = () => {
    const product = props.products.filter(product => product.id === selectedProduct.value)[0] ?? null;
    if (product) {
        addNewItem({
            product_id: product.id,
            service_id: null,
            sku: product.sku,
            description: product.description,
            quantity: 1,
            unit_price: parseFloat(product.unit_price).toFixed(2),
            tax_rate: parseInt(selectedCustomer().tax_rate),
        });
    }
    selectedProduct.value = '';
};

const addSelectedService = () => {
    const service = props.services.filter(service => service.id === selectedService.value)[0] ?? null;
    if (service) {
        addNewItem({
            product_id: null,
            service_id: service.id,
            sku: null,
            description: service.description,
            quantity: 1,
            unit_price: parseFloat(service.unit_price).toFixed(2),
            tax_rate: parseInt(selectedCustomer().tax_rate),
        });
    }
    selectedService.value = '';
};

const addNewItem = (target = null) => {
    const newItem = target ?? {
        product_id: null,
        service_id: null,
        sku: null,
        description: null,
        quantity: null,
        unit_price: null,
        tax_rate: null,
    };
    if (
        target !== null &&
        form.items.length === 1 &&
        form.items[0].sku === null &&
        form.items[0].description === null &&
        form.items[0].quantity === null &&
        form.items[0].unit_price === null &&
        form.items[0].tax_rate === null
    ) {
        form.items = [newItem];
    } else {
        form.items.push(newItem);
    }
};

const removeLine = (index) => {
    form.items.splice(index, 1);
    if (form.items.length === 0) {
        addNewItem();
    }
};

const calculateSubTotal = () => {
    let result = 0.00;
    form.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        if (!isNaN(lineSubTotal)) {
            result += lineSubTotal;
        }
    });
    return result;
};

const calculateTotalTax = () => {
    let result = 0.00;
    form.items.forEach((item) => {
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
    <app-layout title="Edit Invoice">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
                <span>Edit Invoice {{ invoice.invoice_reference }}</span>
                <span :class="`font-medium status-${invoice.status.replace(' ', '_')}`">{{ invoice.status }}</span>
            </h2>
        </template>
        <div class="py-12">
            <form @submit.prevent="form.put(route('invoices.update', invoice))">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_id">
                                    Select Customer
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="customer_id" v-model="form.customer_id"
                                    required
                                >
                                    <option v-for="customer in customers" :value="customer.id" :key="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                                <span v-if="form.customer_id" class="sm-badge">Tax Rate {{ parseFloat(selectedCustomer().tax_rate ?? 0) }}%</span>
                                <div v-if="errors.customer_id" class="text-red-600">{{ errors.customer_id }}</div>
                            </div>

                            <div class="mb-4" v-if="form.customer_id">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_email_ids">
                                    Notification Recipients
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="customer_id" v-model="form.customer_email_ids"
                                    multiple
                                    required
                                >
                                    <option v-for="(email, index) in selectedCustomer().emails" :value="email.id" :key="email.id">
                                        #{{ index + 1 }} {{ email.name }} ({{ email.address }})
                                    </option>
                                </select>
                                <div v-for="(email, index) in selectedCustomer().emails" class="text-red-600">
                                    {{ errors[`customer_email_ids.${index}`] }}
                                </div>
                            </div>

                            <div class="mb-4" v-if="form.customer_id">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_address_id">
                                    Billing Address
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="billing_address_id" v-model="form.billing_address_id"
                                >
                                    <option v-for="address in selectedCustomer().addresses" :value="address.id" :key="address.id">
                                        {{ [address.line1, address.line2, address.city, address.state, address.postal_code, address.country].filter(n => n).join(', ') }}
                                    </option>
                                </select>
                                <div v-if="errors.billing_address_id" class="text-red-600">{{ errors.billing_address_id }}</div>
                            </div>

                            <div class="mb-4" v-if="form.customer_id">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="shipping_address_id">
                                    Shipping Address
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="shipping_address_id" v-model="form.shipping_address_id"
                                >
                                    <option v-for="address in selectedCustomer().addresses" :value="address.id" :key="address.id">
                                        {{ [address.line1, address.line2, address.city, address.state, address.postal_code, address.country].filter(n => n).join(', ') }}
                                    </option>
                                </select>
                                <div v-if="errors.shipping_address_id" class="text-red-600">{{ errors.shipping_address_id }}</div>
                            </div>

                            <table v-if="form.customer_id">
                                <tr>
                                    <td>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_reference">
                                                Customer Reference
                                            </label>
                                            <input placeholder="e.g. INV-1234"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   id="customer_reference"
                                                   maxlength="64"
                                                   v-model="form.customer_reference" />
                                            <div v-if="errors.customer_reference" class="text-red-600">{{ errors.customer_reference }}</div>
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="issue_date">
                                                Issue Date
                                            </label>
                                            <input type="date"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   id="issue_date"
                                                   v-model="form.issue_date"
                                                   required />
                                            <div v-if="errors.issue_date" class="text-red-600">{{ errors.issue_date }}</div>
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                                                Due Date
                                            </label>
                                            <input type="date"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   id="due_date"
                                                   v-model="form.due_date"
                                                   required />
                                            <div v-if="errors.due_date" class="text-red-600">{{ errors.due_date }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" v-if="form.customer_id">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                            <div class="flex justify-end gap-4 mb-4">

                                <div class="flex flex-wrap items-stretch w-1/3 relative">
                                    <select
                                        v-model="selectedProduct"
                                        class="flex-shrink flex-grow flex-auto leading-normal w-px border h-10 border-gray-500 rounded border-r-0 rounded-r-none px-3 relative focus:border-blue focus:shadow"
                                    >
                                        <option disabled value="">Add a product</option>
                                        <option v-for="product in products" :value="product.id" :key="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                    <div class="flex -mr-px">
                                        <button
                                            type="button"
                                            class="flex items-center leading-normal bg-grey-lighter rounded rounded-l-none border border-l-0 border-gray-500 px-3 whitespace-no-wrap text-grey-dark text-sm"
                                            @click="addSelectedProduct"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-stretch w-1/3 relative">
                                    <select
                                        v-model="selectedService"
                                        class="flex-shrink flex-grow flex-auto leading-normal w-px border h-10 border-gray-500 rounded border-r-0 rounded-r-none px-3 relative focus:border-blue focus:shadow"
                                    >
                                        <option disabled value="">Add a service</option>
                                        <option v-for="service in services" :value="service.id" :key="service.id">
                                            {{ service.name }}
                                        </option>
                                    </select>
                                    <div class="flex -mr-px">
                                        <button
                                            type="button"
                                            class="flex items-center leading-normal bg-grey-lighter rounded rounded-l-none border border-l-0 border-gray-500 px-3 whitespace-no-wrap text-grey-dark text-sm"
                                            @click="addSelectedService"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-blue" @click="addNewItem()">
                                    Add New Item
                                </button>
                            </div>

                            <table class="border-separate border-spacing-2">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width: 25px;"></th>
                                        <th class="text-left" style="width: 125px;">SKU</th>
                                        <th class="text-left">Description</th>
                                        <th class="text-left" style="width: 125px;">Quantity</th>
                                        <th class="text-left" style="width: 125px;">Unit Price ({{ invoice.currency }})</th>
                                        <th class="text-left" style="width: 125px;">Tax Rate (%)</th>
                                        <th class="text-left" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <td>
                                            #{{ index + 1 }}
                                        </td>
                                        <td>
                                            <input placeholder="e.g. ABC123"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   :class="errors[`items.${index}.sku`] ? 'bg-red-100 border-red-500' : ''"
                                                   :title="errors[`items.${index}.sku`] ?? ''"
                                                   v-model="item.sku"
                                                   maxlength="32"
                                                   :readonly="item.product_id !== null || item.service_id !== null" />
                                        </td>
                                        <td>
                                            <input placeholder="e.g. My product..."
                                                   minlength="3"
                                                   maxlength="1024"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   :class="(errors[`items.${index}.description`] || errors[`items.${index}.product_id`] || errors[`items.${index}.service_id`]) ? 'bg-red-100 border-red-500' : ''"
                                                   :title="errors[`items.${index}.description`] ?? errors[`items.${index}.product_id`] ?? errors[`items.${index}.service_id`] ?? ''"
                                                   v-model="item.description"
                                                   :readonly="item.product_id !== null || item.service_id !== null"
                                                   required />
                                        </td>
                                        <td>
                                            <input placeholder="e.g. 5"
                                                   type="number"
                                                   step="0.01"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   :class="errors[`items.${index}.quantity`] ? 'bg-red-100 border-red-500' : ''"
                                                   :title="errors[`items.${index}.quantity`] ?? ''"
                                                   v-model="item.quantity"
                                                   required />
                                        </td>
                                        <td>
                                            <input placeholder="e.g. 10.99"
                                                   type="number"
                                                   step="0.01"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   :class="errors[`items.${index}.unit_price`] ? 'bg-red-100 border-red-500' : ''"
                                                   :title="errors[`items.${index}.unit_price`] ?? ''"
                                                   v-model="item.unit_price"
                                                   required />
                                        </td>
                                        <td>
                                            <input placeholder="e.g. 17.5"
                                                   type="number"
                                                   step="0.1"
                                                   min="0"
                                                   max="100"
                                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   :class="errors[`items.${index}.tax_rate`] ? 'bg-red-100 border-red-500' : ''"
                                                   :title="errors[`items.${index}.tax_rate`] ?? ''"
                                                   v-model="item.tax_rate"
                                                   required />
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-red" @click="removeLine(index)">
                                                X
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="form.customer_id">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <table>
                                <tr>
                                    <td>
                                        <div class="flex gap-2">

                                            <Link :href="route('invoices.show', props.invoice.invoice_reference)">
                                                <button type="button" class="btn btn-gray">View Invoice</button>
                                            </Link>

                                            <button type="button" class="btn btn-red" @click="voidInvoice(props.invoice)">
                                                Void Invoice
                                            </button>

                                            <button
                                                class="btn btn-orange"
                                                type="submit"
                                                @click="form.save_mode='Draft'"
                                            >
                                                Update as Draft
                                            </button>

                                            <button
                                                class="btn btn-blue"
                                                type="submit"
                                                @click="form.save_mode='Publish'"
                                            >
                                                Update &amp; Publish
                                            </button>

                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <p>Subtotal <strong>{{  calculateSubTotal().toFixed(2) }} {{ invoice.currency }}</strong></p>
                                        <p>Total Tax <strong>{{ calculateTotalTax().toFixed(2) }} {{ invoice.currency }}</strong></p>
                                        <p>Total Due <strong>{{ calculateGrandTotal().toFixed(2) }} {{ invoice.currency }}</strong></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </app-layout>
</template>
