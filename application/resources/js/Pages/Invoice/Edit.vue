<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router, useForm} from '@inertiajs/vue3';
import {ref} from 'vue'

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
        router.visit(route('invoices.void', invoice.invoice_reference), {method: 'get'});
    }
}

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

</script>

<template>
    <app-layout title="Edit Invoice">
        <template #header>
            <h1>
                Edit Invoice #<span>{{ invoice.invoice_reference }}</span>
                <v-chip label>{{ invoice.status }}</v-chip>
            </h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-form
                @submit.prevent="form.put(route('invoices.update', invoice))">
                <h2>Customer Details</h2>
                <v-select v-model="form.customer_id" required :items="customers"
                          item-title="name" item-value="id"
                          label="Select Customer"
                >
                    <template v-slot:append v-if="form.customer_id">
                        <v-chip label v-if="form.customer_id">
                            Tax Rate:
                            {{ parseFloat(selectedCustomer().tax_rate ?? 0) }}%
                        </v-chip>
                    </template>
                </v-select>
                <template v-if="form.customer_id">
                    <v-select v-model="form.customer_email_ids" multiple
                              required :items="selectedCustomer().emails"
                              item-value="id" item-title="name"
                              label="Notification Recipients">
                        <template v-slot:item="{ props, item }">
                            <v-list-item v-bind="props">
                                &lt;{{ item.raw.address }}&gt;
                            </v-list-item>
                        </template>
                        <template v-slot:selection="{ item, index }">
                            <v-chip>
                                {{ item.raw.name }}
                                &lt;{{ item.raw.address }}&gt;
                            </v-chip>
                        </template>
                    </v-select>
                    <v-select v-model="form.billing_address_id"
                              :items="selectedCustomer().addresses"
                              item-value="id" item-title="line1"
                              label="Billing Address">
                        <template v-slot:item="{ props, item }">
                            <v-list-item v-bind="props">
                                {{ makeFormattedAddress(item.raw) }}
                            </v-list-item>
                        </template>
                        <template v-slot:selection="{ item, index }">
                            {{ makeFormattedAddress(item.raw) }}
                        </template>
                    </v-select>
                    <v-select v-model="form.shipping_address_id"
                              :items="selectedCustomer().addresses"
                              item-value="id" item-title="line1"
                              label="Shipping Address">
                        <template v-slot:item="{ props, item }">
                            <v-list-item v-bind="props">
                                {{ makeFormattedAddress(item.raw) }}
                            </v-list-item>
                        </template>
                        <template v-slot:selection="{ item, index }">
                            {{ makeFormattedAddress(item.raw) }}
                        </template>
                    </v-select>

                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.customer_reference"
                                          label="Customer Reference #"
                                          placeholder="e.g. INV-1234"/>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field type="date" v-model="form.issue_date"
                                          label="Issue Date"/>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field type="date" v-model="form.due_date"
                                          label="Due Date"/>
                        </v-col>
                    </v-row>
                    <h2>Invoice Items</h2>
                    <v-row>
                        <v-col>
                            <v-select :items="products" label="Add a product"
                                      item-value="id" item-title="name"
                                      v-model="selectedProduct">
                                <template v-slot:append>
                                    <v-btn
                                        :disabled="!selectedProduct"
                                        variant="flat" size="large"
                                        rounded="0" @click="addSelectedProduct">
                                        Add
                                    </v-btn>
                                </template>
                            </v-select>
                        </v-col>
                        <v-col>
                            <v-select :items="services" label="Add a service"
                                      item-value="id" item-title="name"
                                      v-model="selectedService">
                                <template v-slot:append>
                                    <v-btn
                                        :disabled="!selectedService"
                                        variant="flat" size="large"
                                        rounded="0" @click="addSelectedService">
                                        Add
                                    </v-btn>
                                </template>
                            </v-select>
                        </v-col>
                        <v-col cols="auto">
                            <v-btn @click="addNewItem()" variant="flat"
                                   size="large" rounded="0">Add Item
                            </v-btn>
                        </v-col>
                    </v-row>
                    <v-table density="compact">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price
                                ({{ $page.props.auth.user.account_currency }})
                            </th>
                            <th>Tax Rate (%)</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in form.items" :key="index">
                            <td>
                                <v-text-field placeholder="e.g. ABC-123"
                                              v-model="item.sku"
                                              density="compact"
                                              :readonly="item.product_id > 0 || item.service_id > 0"/>
                            </td>
                            <td>
                                <v-text-field placeholder="e.g. My product..."
                                              v-model="item.description"
                                              density="compact"
                                              :readonly="item.product_id > 0 || item.service_id > 0"
                                              required/>

                            </td>
                            <td>
                                <v-text-field type="number" placeholder="e.g. 5"
                                              step="any" v-model="item.quantity"
                                              density="compact"
                                              required/>
                            </td>
                            <td>
                                <v-text-field type="number"
                                              placeholder="e.g. 10.99"
                                              step="any"
                                              v-model="item.unit_price"
                                              density="compact"
                                              required/>
                            </td>
                            <td>
                                <v-text-field type="number"
                                              placeholder="e.g. 5.37"
                                              step="any" v-model="item.tax_rate"
                                              density="compact"/>
                            </td>
                            <td class="text-end">
                                <v-btn variant="flat" icon="mdi-trash-can"
                                       size="small"
                                       @click="removeLine(index)"/>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                    <v-row justify="end" align="end">
                        <v-col>
                            <v-row justify="start">
                                <v-col cols="auto">
                                    <v-btn type="button" variant="flat"
                                           :href="route('invoices.show', props.invoice.invoice_reference)">
                                        View Invoice
                                    </v-btn>
                                </v-col>
                                <v-col cols="auto">
                                    <v-btn type="button" variant="flat"
                                           color="error"
                                           @click="voidInvoice(props.invoice)">
                                        Void Invoice
                                    </v-btn>
                                </v-col>
                                <v-col cols="auto">
                                    <v-btn type="submit" variant="flat"
                                           @click="form.save_mode='Draft'">
                                        Update as Draft
                                    </v-btn>
                                </v-col>
                                <v-col cols="auto">
                                    <v-btn type="submit" variant="flat"
                                           @click="form.save_mode='Publish'"
                                           color="primary">
                                        Update &amp; Publish
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="auto">
                            <v-table density="comfortable">
                                <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end font-weight-black">
                                        {{ calculateSubTotal().toFixed(2) }}
                                        {{
                                            $page.props.auth.user.account_currency
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Tax</td>
                                    <td class="text-end font-weight-black">
                                        {{ calculateTotalTax().toFixed(2) }}
                                        {{
                                            $page.props.auth.user.account_currency
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Due</td>
                                    <td class="text-end font-weight-black">
                                        {{ calculateGrandTotal().toFixed(2) }}
                                        {{
                                            $page.props.auth.user.account_currency
                                        }}
                                    </td>
                                </tr>
                                </tbody>
                            </v-table>
                        </v-col>
                    </v-row>
                </template>
            </v-form>
        </v-sheet>
    </app-layout>
</template>
