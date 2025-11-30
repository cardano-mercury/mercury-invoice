<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import FormCard from '@/Components/FormCard.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    errors: Object,
    invoice: Object,
    customers: Array,
    products: Array,
    services: Array,
});

const form = useForm({
    customer_id: props.invoice.customer_id,
    customer_email_ids: props.invoice.recipients.map((item) => item.id),
    billing_address_id: props.invoice.billing_address ? props.invoice.billing_address.id : null,
    shipping_address_id: props.invoice.shipping_address ? props.invoice.shipping_address.id : null,
    customer_reference: props.invoice.customer_reference,
    issue_date: props.invoice.issue_date,
    due_date: props.invoice.due_date,
    items: props.invoice.items.map((item) => {
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
        return props.customers.filter((customer) => customer.id === form.customer_id)[0] ?? null;
    }
    return null;
};

const addSelectedProduct = () => {
    const product = props.products.filter((product) => product.id === selectedProduct.value)[0] ?? null;
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
    const service = props.services.filter((service) => service.id === selectedService.value)[0] ?? null;
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
    let result = 0.0;
    form.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        if (!isNaN(lineSubTotal)) {
            result += lineSubTotal;
        }
    });
    return result;
};

const calculateTotalTax = () => {
    let result = 0.0;
    form.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        const lineTaxRate = parseFloat(item.tax_rate);
        if (!isNaN(lineSubTotal) && !isNaN(lineTaxRate)) {
            result += lineSubTotal * (lineTaxRate / 100);
        }
    });
    return result;
};

const calculateGrandTotal = () => {
    return calculateSubTotal() + calculateTotalTax();
};

function voidInvoice(invoice) {
    const response = confirm(`Are you sure you want to void this invoice: ${invoice.invoice_reference}?`);
    if (response) {
        router.visit(route('invoices.void', invoice.invoice_reference), { method: 'get' });
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
};

const makeFormattedAddress = (address) => {
    if (address === null || address === undefined) {
        return ``;
    }
    const lines = getAddressLines(address);
    return `${lines}
${address.city}, ${address.state} ${address.postal_code}
${address.country}`;
};

const hasValidItems = computed(() => {
    return form.items.some((item) => item.description && item.quantity && item.unit_price);
});

const getStatusColor = (status) => {
    switch (status) {
        case 'Draft':
            return 'secondary';
        case 'Published':
            return 'info';
        case 'Paid':
            return 'success';
        case 'Voided':
            return 'error';
        default:
            return 'secondary';
    }
};
</script>

<template>
    <AppLayout title="Edit Invoice">
        <template #header>
            <PageHeader 
                title="Edit Invoice" 
                :subtitle="`#${invoice.invoice_reference}`"
                icon="mdi-file-document-edit"
            >
                <template #actions>
                    <v-chip 
                        :color="getStatusColor(invoice.status)" 
                        variant="flat"
                        size="large"
                    >
                        <v-icon start icon="mdi-circle" size="x-small" />
                        {{ invoice.status }}
                    </v-chip>
                </template>
            </PageHeader>
        </template>

        <v-form @submit.prevent="form.put(route('invoices.update', invoice))">
            <!-- Customer Selection -->
            <FormCard 
                title="Customer Details" 
                icon="mdi-account"
                subtitle="Update the customer information for this invoice"
            >
                <v-row>
                    <v-col cols="12">
                        <v-autocomplete
                            v-model="form.customer_id"
                            :items="customers"
                            item-title="name"
                            item-value="id"
                            label="Select Customer"
                            placeholder="Search for a customer..."
                            prepend-inner-icon="mdi-account-search"
                            :error-messages="form.errors.customer_id"
                            required
                            clearable
                        >
                            <template #append v-if="form.customer_id">
                                <v-chip color="primary" variant="tonal" size="small">
                                    <v-icon start icon="mdi-percent" size="small" />
                                    Tax: {{ parseFloat(selectedCustomer()?.tax_rate ?? 0) }}%
                                </v-chip>
                            </template>
                        </v-autocomplete>
                    </v-col>
                </v-row>

                <template v-if="form.customer_id">
                    <v-divider class="my-4" />

                    <v-row>
                        <v-col cols="12">
                            <v-select
                                v-model="form.customer_email_ids"
                                :items="selectedCustomer()?.emails || []"
                                item-value="id"
                                item-title="name"
                                label="Notification Recipients"
                                prepend-inner-icon="mdi-email-multiple"
                                multiple
                                chips
                                closable-chips
                                :error-messages="form.errors.customer_email_ids"
                            >
                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props">
                                        <template #subtitle>
                                            {{ item.raw.address }}
                                        </template>
                                    </v-list-item>
                                </template>
                                <template #chip="{ item, props }">
                                    <v-chip v-bind="props" closable>
                                        {{ item.raw.name }} &lt;{{ item.raw.address }}&gt;
                                    </v-chip>
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.billing_address_id"
                                :items="selectedCustomer()?.addresses || []"
                                item-value="id"
                                item-title="line1"
                                label="Billing Address"
                                prepend-inner-icon="mdi-map-marker"
                                :error-messages="form.errors.billing_address_id"
                                clearable
                            >
                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props">
                                        <template #title>
                                            <div class="text-body-2" style="white-space: pre-line;">
                                                {{ makeFormattedAddress(item.raw) }}
                                            </div>
                                        </template>
                                    </v-list-item>
                                </template>
                                <template #selection="{ item }">
                                    <div class="text-body-2 text-truncate">
                                        {{ item.raw.line1 }}, {{ item.raw.city }}
                                    </div>
                                </template>
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.shipping_address_id"
                                :items="selectedCustomer()?.addresses || []"
                                item-value="id"
                                item-title="line1"
                                label="Shipping Address"
                                prepend-inner-icon="mdi-truck-delivery"
                                :error-messages="form.errors.shipping_address_id"
                                clearable
                            >
                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props">
                                        <template #title>
                                            <div class="text-body-2" style="white-space: pre-line;">
                                                {{ makeFormattedAddress(item.raw) }}
                                            </div>
                                        </template>
                                    </v-list-item>
                                </template>
                                <template #selection="{ item }">
                                    <div class="text-body-2 text-truncate">
                                        {{ item.raw.line1 }}, {{ item.raw.city }}
                                    </div>
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="form.customer_reference"
                                label="Customer Reference #"
                                placeholder="e.g. PO-1234"
                                prepend-inner-icon="mdi-pound"
                                :error-messages="form.errors.customer_reference"
                            />
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field
                                v-model="form.issue_date"
                                type="date"
                                label="Issue Date"
                                prepend-inner-icon="mdi-calendar"
                                :error-messages="form.errors.issue_date"
                            />
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field
                                v-model="form.due_date"
                                type="date"
                                label="Due Date"
                                prepend-inner-icon="mdi-calendar-clock"
                                :error-messages="form.errors.due_date"
                            />
                        </v-col>
                    </v-row>
                </template>
            </FormCard>

            <!-- Invoice Items -->
            <FormCard 
                v-if="form.customer_id"
                title="Invoice Items" 
                icon="mdi-format-list-bulleted"
                subtitle="Manage the line items for this invoice"
            >
                <!-- Quick Add Section -->
                <v-row class="mb-4">
                    <v-col cols="12" md="5">
                        <v-select
                            v-model="selectedProduct"
                            :items="products"
                            item-value="id"
                            item-title="name"
                            label="Quick add product"
                            prepend-inner-icon="mdi-package-variant"
                            clearable
                        >
                            <template #append>
                                <v-btn
                                    :disabled="!selectedProduct"
                                    variant="flat"
                                    color="primary"
                                    icon="mdi-plus"
                                    size="small"
                                    @click="addSelectedProduct"
                                />
                            </template>
                        </v-select>
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-select
                            v-model="selectedService"
                            :items="services"
                            item-value="id"
                            item-title="name"
                            label="Quick add service"
                            prepend-inner-icon="mdi-briefcase"
                            clearable
                        >
                            <template #append>
                                <v-btn
                                    :disabled="!selectedService"
                                    variant="flat"
                                    color="primary"
                                    icon="mdi-plus"
                                    size="small"
                                    @click="addSelectedService"
                                />
                            </template>
                        </v-select>
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-btn
                            @click="addNewItem()"
                            variant="tonal"
                            color="primary"
                            prepend-icon="mdi-plus"
                            block
                            class="h-100"
                        >
                            Custom Item
                        </v-btn>
                    </v-col>
                </v-row>

                <v-divider class="mb-4" />

                <!-- Items Table -->
                <v-card variant="outlined">
                    <v-table density="comfortable" class="invoice-items-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">SKU</th>
                                <th>Description</th>
                                <th style="width: 100px;">Qty</th>
                                <th style="width: 130px;">
                                    Price ({{ $page.props.auth.user.account_currency }})
                                </th>
                                <th style="width: 100px;">Tax %</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in form.items" :key="index">
                                <td>
                                    <v-text-field
                                        v-model="item.sku"
                                        placeholder="SKU"
                                        density="compact"
                                        variant="plain"
                                        :readonly="item.product_id > 0 || item.service_id > 0"
                                        hide-details
                                    />
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="item.description"
                                        placeholder="Item description..."
                                        density="compact"
                                        variant="plain"
                                        :readonly="item.product_id > 0 || item.service_id > 0"
                                        hide-details
                                        required
                                    />
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="item.quantity"
                                        type="number"
                                        placeholder="1"
                                        density="compact"
                                        variant="plain"
                                        step="any"
                                        min="0"
                                        hide-details
                                        required
                                    />
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="item.unit_price"
                                        type="number"
                                        placeholder="0.00"
                                        density="compact"
                                        variant="plain"
                                        step="0.01"
                                        min="0"
                                        hide-details
                                        required
                                    />
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="item.tax_rate"
                                        type="number"
                                        placeholder="0"
                                        density="compact"
                                        variant="plain"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        hide-details
                                    />
                                </td>
                                <td class="text-center">
                                    <v-btn
                                        variant="text"
                                        icon="mdi-trash-can"
                                        size="small"
                                        color="error"
                                        @click="removeLine(index)"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-card>

                <!-- Totals -->
                <v-row justify="end" class="mt-6">
                    <v-col cols="12" md="5" lg="4">
                        <v-card variant="tonal" color="primary">
                            <v-card-text>
                                <div class="d-flex justify-space-between mb-2">
                                    <span class="text-body-2">Subtotal</span>
                                    <span class="text-body-2 font-weight-bold">
                                        {{ calculateSubTotal().toFixed(2) }}
                                        {{ $page.props.auth.user.account_currency }}
                                    </span>
                                </div>
                                <div class="d-flex justify-space-between mb-2">
                                    <span class="text-body-2">Tax</span>
                                    <span class="text-body-2 font-weight-bold">
                                        {{ calculateTotalTax().toFixed(2) }}
                                        {{ $page.props.auth.user.account_currency }}
                                    </span>
                                </div>
                                <v-divider class="my-2" />
                                <div class="d-flex justify-space-between">
                                    <span class="text-body-1 font-weight-bold">Total Due</span>
                                    <span class="text-h6 font-weight-black">
                                        {{ calculateGrandTotal().toFixed(2) }}
                                        {{ $page.props.auth.user.account_currency }}
                                    </span>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </FormCard>

            <!-- Actions -->
            <v-card v-if="form.customer_id" class="mt-6">
                <v-card-text class="d-flex flex-wrap justify-space-between align-center ga-4">
                    <div class="d-flex flex-wrap ga-2">
                        <v-btn
                            :href="route('invoices.show', props.invoice.invoice_reference)"
                            variant="tonal"
                            color="primary"
                            prepend-icon="mdi-eye"
                        >
                            View
                        </v-btn>
                        <v-btn
                            variant="tonal"
                            color="error"
                            prepend-icon="mdi-cancel"
                            @click="voidInvoice(props.invoice)"
                        >
                            Void
                        </v-btn>
                    </div>
                    <div class="d-flex flex-wrap ga-3">
                        <v-btn
                            :href="route('invoices.index')"
                            variant="text"
                            color="secondary"
                            prepend-icon="mdi-close"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            type="submit"
                            variant="outlined"
                            color="primary"
                            prepend-icon="mdi-content-save-outline"
                            :loading="form.processing && form.save_mode === 'Draft'"
                            :disabled="!hasValidItems"
                            @click="form.save_mode = 'Draft'"
                        >
                            Save as Draft
                        </v-btn>
                        <v-btn
                            type="submit"
                            variant="flat"
                            color="primary"
                            prepend-icon="mdi-send"
                            :loading="form.processing && form.save_mode === 'Publish'"
                            :disabled="!hasValidItems"
                            @click="form.save_mode = 'Publish'"
                        >
                            Save &amp; Publish
                        </v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </v-form>
    </AppLayout>
</template>

<style scoped>
.invoice-items-table th {
    background: rgb(var(--v-theme-surface-variant)) !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    font-size: 0.75rem !important;
    letter-spacing: 0.025em;
}

.invoice-items-table td {
    vertical-align: middle;
}

.invoice-items-table :deep(.v-field__input) {
    padding: 8px 0;
    min-height: 36px;
}
</style>
