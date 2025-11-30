<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import FormCard from '@/Components/FormCard.vue';
import { router as Inertia, useForm } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object,
    customer: Object,
    customerCategories: Array,
    phoneTypes: Object,
    addressTypes: Object,
});

// Basic customer info form
const basicInfoForm = useForm(props.customer);

// Active tab management
const activeTab = ref('basic');

// Email management
const emailForm = useForm({
    name: '',
    address: '',
    is_default: false,
});

const editingEmail = ref(null);

function addEmail() {
    emailForm.post(route('customers.emails.store', props.customer.id), {
        preserveScroll: true,
        onSuccess: () => {
            emailForm.reset();
        },
    });
}

function editEmail(email) {
    editingEmail.value = email;
    emailForm.name = email.name;
    emailForm.address = email.address;
    emailForm.is_default = email.is_default;
}

function updateEmail() {
    if (!editingEmail.value) return;

    emailForm.put(route('customers.emails.update', [props.customer.id, editingEmail.value.id]), {
        preserveScroll: true,
        onSuccess: () => {
            emailForm.reset();
            editingEmail.value = null;
        },
    });
}

function cancelEmailEdit() {
    editingEmail.value = null;
    emailForm.reset();
}

function deleteEmail(email) {
    if (confirm('Are you sure you want to delete this email?')) {
        Inertia.delete(route('customers.emails.destroy', [props.customer.id, email.id]), {
            preserveScroll: true,
        });
    }
}

// Phone management
const phoneForm = useForm({
    name: '',
    type: Object.keys(props.phoneTypes)[0] || 'Home',
    number: '',
    is_default: false,
});

const editingPhone = ref(null);

function addPhone() {
    phoneForm.post(route('customers.phones.store', props.customer.id), {
        preserveScroll: true,
        onSuccess: () => {
            phoneForm.reset();
            phoneForm.type = Object.keys(props.phoneTypes)[0] || 'Home';
        },
    });
}

function editPhone(phone) {
    editingPhone.value = phone;
    phoneForm.name = phone.name;
    phoneForm.type = phone.type;
    phoneForm.number = phone.number;
    phoneForm.is_default = phone.is_default;
}

function updatePhone() {
    if (!editingPhone.value) return;

    phoneForm.put(route('customers.phones.update', [props.customer.id, editingPhone.value.id]), {
        preserveScroll: true,
        onSuccess: () => {
            phoneForm.reset();
            phoneForm.type = Object.keys(props.phoneTypes)[0] || 'Home';
            editingPhone.value = null;
        },
    });
}

function cancelPhoneEdit() {
    editingPhone.value = null;
    phoneForm.reset();
    phoneForm.type = Object.keys(props.phoneTypes)[0] || 'Home';
}

function deletePhone(phone) {
    if (confirm('Are you sure you want to delete this phone?')) {
        Inertia.delete(route('customers.phones.destroy', [props.customer.id, phone.id]), {
            preserveScroll: true,
        });
    }
}

// Address management
const addressForm = useForm({
    name: '',
    type: Object.keys(props.addressTypes)[0] || 'Billing',
    line1: '',
    line2: '',
    city: '',
    state: '',
    postal_code: '',
    country: '',
    is_default: false,
});

const editingAddress = ref(null);

function addAddress() {
    addressForm.post(route('customers.addresses.store', props.customer.id), {
        preserveScroll: true,
        onSuccess: () => {
            addressForm.reset();
            addressForm.type = Object.keys(props.addressTypes)[0] || 'Billing';
        },
    });
}

function editAddress(address) {
    editingAddress.value = address;
    addressForm.name = address.name;
    addressForm.type = address.type;
    addressForm.line1 = address.line1;
    addressForm.line2 = address.line2;
    addressForm.city = address.city;
    addressForm.state = address.state;
    addressForm.postal_code = address.postal_code;
    addressForm.country = address.country;
    addressForm.is_default = address.is_default;
}

function updateAddress() {
    if (!editingAddress.value) return;

    addressForm.put(route('customers.addresses.update', [props.customer.id, editingAddress.value.id]), {
        preserveScroll: true,
        onSuccess: () => {
            addressForm.reset();
            addressForm.type = Object.keys(props.addressTypes)[0] || 'Billing';
            editingAddress.value = null;
        },
    });
}

function cancelAddressEdit() {
    editingAddress.value = null;
    addressForm.reset();
    addressForm.type = Object.keys(props.addressTypes)[0] || 'Billing';
}

function deleteAddress(address) {
    if (confirm('Are you sure you want to delete this address?')) {
        Inertia.delete(route('customers.addresses.destroy', [props.customer.id, address.id]), {
            preserveScroll: true,
        });
    }
}

// Category management
const selectedCategories = ref(props.customer.categories?.map((cat) => cat.id) || []);

function updateCategories() {
    Inertia.put(
        route('customers.categories.update', props.customer.id),
        {
            customer_id: props.customer.id,
            category_ids: selectedCategories.value,
        },
        {
            preserveScroll: true,
        }
    );
}

// Category CRUD
const categoryForm = useForm({
    name: '',
});

const editingCategory = ref(null);

function addCategory() {
    categoryForm.post(route('customer-categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            categoryForm.reset();
        },
    });
}

function editCategory(category) {
    editingCategory.value = category;
    categoryForm.name = category.name;
}

function updateCategory() {
    if (!editingCategory.value) return;

    categoryForm.put(route('customer-categories.update', editingCategory.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            categoryForm.reset();
            editingCategory.value = null;
        },
    });
}

function cancelCategoryEdit() {
    editingCategory.value = null;
    categoryForm.reset();
}

function deleteCategory(category) {
    if (confirm('Are you sure you want to delete this category? It will be removed from all customers.')) {
        Inertia.delete(route('customer-categories.destroy', category.id), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <AppLayout title="Update Customer">
        <template #header>
            <PageHeader 
                title="Update Customer" 
                :subtitle="customer.name"
                icon="mdi-account-edit"
            >
                <template #actions>
                    <v-btn
                        :href="route('customers.show', customer.id)"
                        variant="tonal"
                        color="primary"
                        prepend-icon="mdi-eye"
                    >
                        View
                    </v-btn>
                </template>
            </PageHeader>
        </template>

        <v-card>
            <v-tabs v-model="activeTab" color="primary" grow>
                <v-tab value="basic" prepend-icon="mdi-account">
                    Basic Info
                </v-tab>
                <v-tab value="emails" prepend-icon="mdi-email">
                    Emails
                    <v-badge 
                        v-if="customer.emails?.length" 
                        :content="customer.emails.length" 
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
                <v-tab value="phones" prepend-icon="mdi-phone">
                    Phones
                    <v-badge 
                        v-if="customer.phones?.length" 
                        :content="customer.phones.length" 
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
                <v-tab value="addresses" prepend-icon="mdi-map-marker">
                    Addresses
                    <v-badge 
                        v-if="customer.addresses?.length" 
                        :content="customer.addresses.length" 
                        color="primary"
                        inline
                        class="ml-2"
                    />
                </v-tab>
                <v-tab value="categories" prepend-icon="mdi-tag-multiple">
                    Categories
                </v-tab>
            </v-tabs>

            <v-divider />

            <v-card-text class="pa-6">
                <v-window v-model="activeTab">
                    <!-- Basic Info Tab -->
                    <v-window-item value="basic">
                        <v-form @submit.prevent="basicInfoForm.put(route('customers.update', customer.id))">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="basicInfoForm.name"
                                        label="Customer Name"
                                        placeholder="e.g. ACME Holding, Co."
                                        prepend-inner-icon="mdi-domain"
                                        :error-messages="basicInfoForm.errors.name"
                                        required
                                        autofocus
                                    />
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="basicInfoForm.tax_number"
                                        label="Tax Number"
                                        placeholder="e.g. 12-3456789"
                                        prepend-inner-icon="mdi-file-document"
                                        :error-messages="basicInfoForm.errors.tax_number"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="basicInfoForm.tax_rate"
                                        label="Tax Rate (%)"
                                        placeholder="e.g. 8.75"
                                        prepend-inner-icon="mdi-percent"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        :error-messages="basicInfoForm.errors.tax_rate"
                                    />
                                </v-col>
                            </v-row>

                            <div class="d-flex justify-end mt-6">
                                <v-btn
                                    type="submit"
                                    color="primary"
                                    variant="flat"
                                    prepend-icon="mdi-content-save"
                                    :loading="basicInfoForm.processing"
                                >
                                    Update Basic Info
                                </v-btn>
                            </div>
                        </v-form>
                    </v-window-item>

                    <!-- Emails Tab -->
                    <v-window-item value="emails">
                        <FormCard 
                            :title="editingEmail ? 'Edit Email' : 'Add New Email'" 
                            icon="mdi-email-plus"
                            flat
                        >
                            <v-form @submit.prevent="editingEmail ? updateEmail() : addEmail()">
                                <v-row>
                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="emailForm.name"
                                            label="Label"
                                            placeholder="e.g. Work Email"
                                            prepend-inner-icon="mdi-label"
                                            :error-messages="emailForm.errors.name"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="5">
                                        <v-text-field
                                            v-model="emailForm.address"
                                            label="Email Address"
                                            placeholder="e.g. contact@example.com"
                                            prepend-inner-icon="mdi-email"
                                            type="email"
                                            :error-messages="emailForm.errors.address"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="3">
                                        <v-switch
                                            v-model="emailForm.is_default"
                                            label="Default"
                                            color="primary"
                                        />
                                    </v-col>
                                </v-row>

                                <div class="d-flex ga-2">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        variant="flat"
                                        :prepend-icon="editingEmail ? 'mdi-content-save' : 'mdi-plus'"
                                        :loading="emailForm.processing"
                                    >
                                        {{ editingEmail ? 'Update Email' : 'Add Email' }}
                                    </v-btn>
                                    <v-btn
                                        v-if="editingEmail"
                                        variant="tonal"
                                        color="secondary"
                                        prepend-icon="mdi-close"
                                        @click="cancelEmailEdit"
                                    >
                                        Cancel
                                    </v-btn>
                                </div>
                            </v-form>
                        </FormCard>

                        <!-- Emails Table -->
                        <v-card variant="outlined" class="mt-4">
                            <v-card-title class="d-flex align-center">
                                <v-icon icon="mdi-email-multiple" class="mr-2" />
                                Email Addresses
                            </v-card-title>
                            <v-divider />
                            <v-data-table
                                v-if="customer.emails && customer.emails.length > 0"
                                :items="customer.emails"
                                :headers="[
                                    { title: 'Default', key: 'is_default', width: '80px' },
                                    { title: 'Label', key: 'name' },
                                    { title: 'Email Address', key: 'address' },
                                    { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
                                ]"
                                density="comfortable"
                                hide-default-footer
                            >
                                <template #item.is_default="{ item }">
                                    <v-icon v-if="item.is_default" color="success" icon="mdi-check-circle" />
                                </template>
                                <template #item.actions="{ item }">
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-pencil"
                                        color="primary"
                                        @click="editEmail(item)"
                                    />
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-delete"
                                        color="error"
                                        @click="deleteEmail(item)"
                                    />
                                </template>
                            </v-data-table>
                            <v-card-text v-else class="text-center text-medium-emphasis py-8">
                                <v-icon icon="mdi-email-off" size="48" class="mb-2" />
                                <p>No email addresses added yet.</p>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Phones Tab -->
                    <v-window-item value="phones">
                        <FormCard 
                            :title="editingPhone ? 'Edit Phone' : 'Add New Phone'" 
                            icon="mdi-phone-plus"
                            flat
                        >
                            <v-form @submit.prevent="editingPhone ? updatePhone() : addPhone()">
                                <v-row>
                                    <v-col cols="12" sm="3">
                                        <v-text-field
                                            v-model="phoneForm.name"
                                            label="Label"
                                            placeholder="e.g. Office Phone"
                                            prepend-inner-icon="mdi-label"
                                            :error-messages="phoneForm.errors.name"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="3">
                                        <v-select
                                            v-model="phoneForm.type"
                                            :items="Object.keys(phoneTypes)"
                                            label="Type"
                                            prepend-inner-icon="mdi-phone"
                                            :error-messages="phoneForm.errors.type"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="phoneForm.number"
                                            label="Phone Number"
                                            placeholder="e.g. +1 (555) 123-4567"
                                            prepend-inner-icon="mdi-dialpad"
                                            :error-messages="phoneForm.errors.number"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="2">
                                        <v-switch
                                            v-model="phoneForm.is_default"
                                            label="Default"
                                            color="primary"
                                        />
                                    </v-col>
                                </v-row>

                                <div class="d-flex ga-2">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        variant="flat"
                                        :prepend-icon="editingPhone ? 'mdi-content-save' : 'mdi-plus'"
                                        :loading="phoneForm.processing"
                                    >
                                        {{ editingPhone ? 'Update Phone' : 'Add Phone' }}
                                    </v-btn>
                                    <v-btn
                                        v-if="editingPhone"
                                        variant="tonal"
                                        color="secondary"
                                        prepend-icon="mdi-close"
                                        @click="cancelPhoneEdit"
                                    >
                                        Cancel
                                    </v-btn>
                                </div>
                            </v-form>
                        </FormCard>

                        <!-- Phones Table -->
                        <v-card variant="outlined" class="mt-4">
                            <v-card-title class="d-flex align-center">
                                <v-icon icon="mdi-phone-classic" class="mr-2" />
                                Phone Numbers
                            </v-card-title>
                            <v-divider />
                            <v-data-table
                                v-if="customer.phones && customer.phones.length > 0"
                                :items="customer.phones"
                                :headers="[
                                    { title: 'Default', key: 'is_default', width: '80px' },
                                    { title: 'Label', key: 'name' },
                                    { title: 'Type', key: 'type' },
                                    { title: 'Number', key: 'number' },
                                    { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
                                ]"
                                density="comfortable"
                                hide-default-footer
                            >
                                <template #item.is_default="{ item }">
                                    <v-icon v-if="item.is_default" color="success" icon="mdi-check-circle" />
                                </template>
                                <template #item.type="{ item }">
                                    <v-chip size="small" variant="tonal">{{ item.type }}</v-chip>
                                </template>
                                <template #item.actions="{ item }">
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-pencil"
                                        color="primary"
                                        @click="editPhone(item)"
                                    />
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-delete"
                                        color="error"
                                        @click="deletePhone(item)"
                                    />
                                </template>
                            </v-data-table>
                            <v-card-text v-else class="text-center text-medium-emphasis py-8">
                                <v-icon icon="mdi-phone-off" size="48" class="mb-2" />
                                <p>No phone numbers added yet.</p>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Addresses Tab -->
                    <v-window-item value="addresses">
                        <FormCard 
                            :title="editingAddress ? 'Edit Address' : 'Add New Address'" 
                            icon="mdi-map-marker-plus"
                            flat
                        >
                            <v-form @submit.prevent="editingAddress ? updateAddress() : addAddress()">
                                <v-row>
                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="addressForm.name"
                                            label="Label"
                                            placeholder="e.g. Main Office"
                                            prepend-inner-icon="mdi-label"
                                            :error-messages="addressForm.errors.name"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-select
                                            v-model="addressForm.type"
                                            :items="Object.keys(addressTypes)"
                                            label="Type"
                                            prepend-inner-icon="mdi-home"
                                            :error-messages="addressForm.errors.type"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-switch
                                            v-model="addressForm.is_default"
                                            label="Default Address"
                                            color="primary"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="addressForm.line1"
                                            label="Address Line 1"
                                            placeholder="e.g. 123 Main St"
                                            prepend-inner-icon="mdi-map-marker"
                                            :error-messages="addressForm.errors.line1"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="addressForm.line2"
                                            label="Address Line 2"
                                            placeholder="e.g. Suite 100"
                                            prepend-inner-icon="mdi-office-building"
                                            :error-messages="addressForm.errors.line2"
                                        />
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="addressForm.city"
                                            label="City"
                                            placeholder="e.g. New York"
                                            prepend-inner-icon="mdi-city"
                                            :error-messages="addressForm.errors.city"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="2">
                                        <v-text-field
                                            v-model="addressForm.state"
                                            label="State/Province"
                                            placeholder="e.g. NY"
                                            :error-messages="addressForm.errors.state"
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="2">
                                        <v-text-field
                                            v-model="addressForm.postal_code"
                                            label="Postal Code"
                                            placeholder="e.g. 10001"
                                            :error-messages="addressForm.errors.postal_code"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="addressForm.country"
                                            label="Country"
                                            placeholder="e.g. United States"
                                            prepend-inner-icon="mdi-earth"
                                            :error-messages="addressForm.errors.country"
                                            required
                                        />
                                    </v-col>
                                </v-row>

                                <div class="d-flex ga-2">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        variant="flat"
                                        :prepend-icon="editingAddress ? 'mdi-content-save' : 'mdi-plus'"
                                        :loading="addressForm.processing"
                                    >
                                        {{ editingAddress ? 'Update Address' : 'Add Address' }}
                                    </v-btn>
                                    <v-btn
                                        v-if="editingAddress"
                                        variant="tonal"
                                        color="secondary"
                                        prepend-icon="mdi-close"
                                        @click="cancelAddressEdit"
                                    >
                                        Cancel
                                    </v-btn>
                                </div>
                            </v-form>
                        </FormCard>

                        <!-- Addresses Table -->
                        <v-card variant="outlined" class="mt-4">
                            <v-card-title class="d-flex align-center">
                                <v-icon icon="mdi-map-marker-multiple" class="mr-2" />
                                Addresses
                            </v-card-title>
                            <v-divider />
                            <v-data-table
                                v-if="customer.addresses && customer.addresses.length > 0"
                                :items="customer.addresses"
                                :headers="[
                                    { title: 'Default', key: 'is_default', width: '80px' },
                                    { title: 'Label', key: 'name' },
                                    { title: 'Type', key: 'type' },
                                    { title: 'Address', key: 'address' },
                                    { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
                                ]"
                                density="comfortable"
                                hide-default-footer
                            >
                                <template #item.is_default="{ item }">
                                    <v-icon v-if="item.is_default" color="success" icon="mdi-check-circle" />
                                </template>
                                <template #item.type="{ item }">
                                    <v-chip size="small" variant="tonal">{{ item.type }}</v-chip>
                                </template>
                                <template #item.address="{ item }">
                                    <div class="text-body-2">
                                        {{ item.line1 }}<br />
                                        <span v-if="item.line2">{{ item.line2 }}<br /></span>
                                        {{ item.city }}, {{ item.state }} {{ item.postal_code }}<br />
                                        {{ item.country }}
                                    </div>
                                </template>
                                <template #item.actions="{ item }">
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-pencil"
                                        color="primary"
                                        @click="editAddress(item)"
                                    />
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-delete"
                                        color="error"
                                        @click="deleteAddress(item)"
                                    />
                                </template>
                            </v-data-table>
                            <v-card-text v-else class="text-center text-medium-emphasis py-8">
                                <v-icon icon="mdi-map-marker-off" size="48" class="mb-2" />
                                <p>No addresses added yet.</p>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <!-- Assign Categories -->
                        <FormCard 
                            title="Assign Categories" 
                            icon="mdi-tag-check"
                            subtitle="Select categories to assign to this customer"
                            flat
                        >
                            <v-form @submit.prevent="updateCategories">
                                <v-select
                                    v-model="selectedCategories"
                                    :items="customerCategories"
                                    item-title="name"
                                    item-value="id"
                                    label="Select Categories"
                                    prepend-inner-icon="mdi-tag-multiple"
                                    multiple
                                    chips
                                    closable-chips
                                    :error-messages="errors.category_ids"
                                />

                                <div class="d-flex justify-end mt-4">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        variant="flat"
                                        prepend-icon="mdi-tag-check"
                                    >
                                        Update Categories
                                    </v-btn>
                                </div>
                            </v-form>
                        </FormCard>

                        <!-- Manage Categories -->
                        <FormCard 
                            :title="editingCategory ? 'Edit Category' : 'Create New Category'" 
                            icon="mdi-tag-plus"
                            class="mt-4"
                            flat
                        >
                            <v-form @submit.prevent="editingCategory ? updateCategory() : addCategory()">
                                <v-row align="center">
                                    <v-col cols="12" sm="8">
                                        <v-text-field
                                            v-model="categoryForm.name"
                                            label="Category Name"
                                            placeholder="e.g. VIP Client"
                                            prepend-inner-icon="mdi-tag"
                                            :error-messages="categoryForm.errors.name"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <div class="d-flex ga-2">
                                            <v-btn
                                                type="submit"
                                                color="primary"
                                                variant="flat"
                                                :prepend-icon="editingCategory ? 'mdi-content-save' : 'mdi-plus'"
                                                :loading="categoryForm.processing"
                                            >
                                                {{ editingCategory ? 'Update' : 'Create' }}
                                            </v-btn>
                                            <v-btn
                                                v-if="editingCategory"
                                                variant="tonal"
                                                color="secondary"
                                                prepend-icon="mdi-close"
                                                @click="cancelCategoryEdit"
                                            >
                                                Cancel
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </FormCard>

                        <!-- All Categories Table -->
                        <v-card variant="outlined" class="mt-4">
                            <v-card-title class="d-flex align-center">
                                <v-icon icon="mdi-tag-multiple" class="mr-2" />
                                All Customer Categories
                            </v-card-title>
                            <v-divider />
                            <v-data-table
                                v-if="customerCategories && customerCategories.length > 0"
                                :items="customerCategories"
                                :headers="[
                                    { title: 'Category Name', key: 'name' },
                                    { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
                                ]"
                                density="comfortable"
                                hide-default-footer
                            >
                                <template #item.name="{ item }">
                                    <v-chip variant="tonal" color="primary">
                                        <v-icon start icon="mdi-tag" />
                                        {{ item.name }}
                                    </v-chip>
                                </template>
                                <template #item.actions="{ item }">
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-pencil"
                                        color="primary"
                                        @click="editCategory(item)"
                                    />
                                    <v-btn
                                        variant="text"
                                        size="small"
                                        icon="mdi-delete"
                                        color="error"
                                        @click="deleteCategory(item)"
                                    />
                                </template>
                            </v-data-table>
                            <v-card-text v-else class="text-center text-medium-emphasis py-8">
                                <v-icon icon="mdi-tag-off" size="48" class="mb-2" />
                                <p>No customer categories created yet.</p>
                            </v-card-text>
                        </v-card>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
