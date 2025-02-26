<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router as Inertia, useForm } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
  customer: Object,
  customerCategories: Array,
  phoneTypes: Object,
  addressTypes: Object
});

// Basic customer info form
const basicInfoForm = useForm(props.customer);

// Active tab management
const activeTab = ref('basic');

// Email management
const emailForm = useForm({
  name: '',
  address: '',
  is_default: false
});

const editingEmail = ref(null);

function addEmail() {
  emailForm.post(route('customers.emails.store', props.customer.id), {
    preserveScroll: true,
    onSuccess: () => {
      emailForm.reset();
    }
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
    }
  });
}

function cancelEmailEdit() {
  editingEmail.value = null;
  emailForm.reset();
}

function deleteEmail(email) {
  if (confirm('Are you sure you want to delete this email?')) {
    Inertia.delete(route('customers.emails.destroy', [props.customer.id, email.id]), {
      preserveScroll: true
    });
  }
}

// Phone management
const phoneForm = useForm({
  name: '',
  type: Object.keys(props.phoneTypes)[0] || 'Home',
  number: '',
  is_default: false
});

const editingPhone = ref(null);

function addPhone() {
  phoneForm.post(route('customers.phones.store', props.customer.id), {
    preserveScroll: true,
    onSuccess: () => {
      phoneForm.reset();
      phoneForm.type = Object.keys(props.phoneTypes)[0] || 'Home';
    }
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
    }
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
      preserveScroll: true
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
  is_default: false
});

const editingAddress = ref(null);

function addAddress() {
  addressForm.post(route('customers.addresses.store', props.customer.id), {
    preserveScroll: true,
    onSuccess: () => {
      addressForm.reset();
      addressForm.type = Object.keys(props.addressTypes)[0] || 'Billing';
    }
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
    }
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
      preserveScroll: true
    });
  }
}

// Category management
const selectedCategories = ref(props.customer.categories?.map(cat => cat.id) || []);

function updateCategories() {
  Inertia.put(route('customers.categories.update', props.customer.id), {
    customer_id: props.customer.id,
    category_ids: selectedCategories.value
  }, {
    preserveScroll: true
  });
}
</script>
<template>
    <app-layout title="Update Customer">
        <template #header>
            <h1>Update Customer</h1>
        </template>

        <v-card class="mb-4">
            <v-tabs v-model="activeTab">
                <v-tab value="basic">Basic Info</v-tab>
                <v-tab value="emails">Emails</v-tab>
                <v-tab value="phones">Phones</v-tab>
                <v-tab value="addresses">Addresses</v-tab>
                <v-tab value="categories">Categories</v-tab>
            </v-tabs>

            <v-card-text class="bg-white px-4 py-12">
                <!-- Basic Info Tab -->
                <v-window v-model="activeTab">
                    <v-window-item value="basic">
                        <v-form @submit.prevent="basicInfoForm.put(route('customers.update', customer.id))">
                            <v-text-field
                                label="Customer Name"
                                name="name"
                                autocomplete="customer_name"
                                v-model="basicInfoForm.name"
                                placeholder="e.g. ACME Holding, Co."
                                :error-messages="basicInfoForm.errors.name"
                                required
                                autofocus
                            />
                            <v-text-field
                                label="Tax Number"
                                name="tax_number"
                                autocomplete="customer_tax_number"
                                placeholder="e.g. 12-3456789"
                                :error-messages="basicInfoForm.errors.tax_number"
                                v-model="basicInfoForm.tax_number"
                            />
                            <v-text-field
                                label="Tax Rate"
                                name="tax_rate"
                                autocomplete="customer_tax_rate"
                                placeholder="e.g. 8.75"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100.00"
                                :error-messages="basicInfoForm.errors.tax_rate"
                                v-model="basicInfoForm.tax_rate"
                            />
                            <v-row>
                                <v-col class="d-flex ga-2">
                                    <v-btn color="primary" type="submit" variant="flat">Update Basic Info</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-window-item>

                    <!-- Emails Tab -->
                    <v-window-item value="emails">
                        <v-card>
                            <v-card-title class="text-h6">
                                Email Addresses
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="editingEmail = null; emailForm.reset()">
                                    Add New Email
                                </v-btn>
                            </v-card-title>

                            <!-- Email Form -->
                            <v-card-text v-if="!editingEmail">
                                <v-form @submit.prevent="addEmail">
                                    <v-row>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="emailForm.name"
                                                label="Label"
                                                placeholder="e.g. Work Email"
                                                :error-messages="emailForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field
                                                v-model="emailForm.address"
                                                label="Email Address"
                                                placeholder="e.g. contact@example.com"
                                                :error-messages="emailForm.errors.address"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-checkbox
                                                v-model="emailForm.is_default"
                                                label="Default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit">Add Email</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Edit Email Form -->
                            <v-card-text v-else>
                                <v-form @submit.prevent="updateEmail">
                                    <v-row>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="emailForm.name"
                                                label="Label"
                                                placeholder="e.g. Work Email"
                                                :error-messages="emailForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field
                                                v-model="emailForm.address"
                                                label="Email Address"
                                                placeholder="e.g. contact@example.com"
                                                :error-messages="emailForm.errors.address"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-checkbox
                                                v-model="emailForm.is_default"
                                                label="Default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit" class="mr-2">Update Email</v-btn>
                                            <v-btn @click="cancelEmailEdit">Cancel</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Emails Table -->
                            <v-card-text>
                                <v-table v-if="customer.emails && customer.emails.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Email Address</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="email in customer.emails" :key="email.id">
                                            <td>
                                                <v-icon v-if="email.is_default" color="success">mdi-check</v-icon>
                                            </td>
                                            <td>{{ email.name }}</td>
                                            <td>{{ email.address }}</td>
                                            <td>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-pencil" @click="editEmail(email)" color="primary me-2">
                                                    Edit
                                                </v-btn>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-delete" @click="deleteEmail(email)" color="error">
                                                    Delete
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                                <div v-else class="text-center pa-4">
                                    No email addresses added yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Phones Tab -->
                    <v-window-item value="phones">
                        <v-card>
                            <v-card-title class="text-h6">
                                Phone Numbers
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="editingPhone = null; phoneForm.reset()">
                                    Add New Phone
                                </v-btn>
                            </v-card-title>

                            <!-- Phone Form -->
                            <v-card-text v-if="!editingPhone">
                                <v-form @submit.prevent="addPhone">
                                    <v-row>
                                        <v-col cols="12" sm="3">
                                            <v-text-field
                                                v-model="phoneForm.name"
                                                label="Label"
                                                placeholder="e.g. Office Phone"
                                                :error-messages="phoneForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="3">
                                            <v-select
                                                v-model="phoneForm.type"
                                                :items="Object.keys(phoneTypes)"
                                                label="Type"
                                                :error-messages="phoneForm.errors.type"
                                                required
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="phoneForm.number"
                                                label="Phone Number"
                                                placeholder="e.g. +1 (555) 123-4567"
                                                :error-messages="phoneForm.errors.number"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-checkbox
                                                v-model="phoneForm.is_default"
                                                label="Default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit">Add Phone</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Edit Phone Form -->
                            <v-card-text v-else>
                                <v-form @submit.prevent="updatePhone">
                                    <v-row>
                                        <v-col cols="12" sm="3">
                                            <v-text-field
                                                v-model="phoneForm.name"
                                                label="Label"
                                                placeholder="e.g. Office Phone"
                                                :error-messages="phoneForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="3">
                                            <v-select
                                                v-model="phoneForm.type"
                                                :items="Object.keys(phoneTypes)"
                                                label="Type"
                                                :error-messages="phoneForm.errors.type"
                                                required
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="phoneForm.number"
                                                label="Phone Number"
                                                placeholder="e.g. +1 (555) 123-4567"
                                                :error-messages="phoneForm.errors.number"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-checkbox
                                                v-model="phoneForm.is_default"
                                                label="Default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit" class="mr-2">Update Phone</v-btn>
                                            <v-btn @click="cancelPhoneEdit">Cancel</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Phones Table -->
                            <v-card-text>
                                <v-table v-if="customer.phones && customer.phones.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Number</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="phone in customer.phones" :key="phone.id">
                                            <td>
                                                <v-icon v-if="phone.is_default" color="success">mdi-check</v-icon>
                                            </td>
                                            <td>{{ phone.name }}</td>
                                            <td>{{ phone.type }}</td>
                                            <td>{{ phone.number }}</td>
                                            <td>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-pencil" @click="editPhone(phone)" color="primary me-2">
                                                    Edit
                                                </v-btn>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-delete" @click="deletePhone(phone)" color="error">
                                                    Delete
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                                <div v-else class="text-center pa-4">
                                    No phone numbers added yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Addresses Tab -->
                    <v-window-item value="addresses">
                        <v-card>
                            <v-card-title class="text-h6">
                                Addresses
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="editingAddress = null; addressForm.reset()">
                                    Add New Address
                                </v-btn>
                            </v-card-title>

                            <!-- Address Form -->
                            <v-card-text v-if="!editingAddress">
                                <v-form @submit.prevent="addAddress">
                                    <v-row>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.name"
                                                label="Label"
                                                placeholder="e.g. Main Office"
                                                :error-messages="addressForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-select
                                                v-model="addressForm.type"
                                                :items="Object.keys(addressTypes)"
                                                label="Type"
                                                :error-messages="addressForm.errors.type"
                                                required
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-checkbox
                                                v-model="addressForm.is_default"
                                                label="Default"
                                                :error-messages="addressForm.errors.is_default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="addressForm.line1"
                                                label="Address Line 1"
                                                placeholder="e.g. 123 Main St"
                                                :error-messages="addressForm.errors.line1"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="addressForm.line2"
                                                label="Address Line 2"
                                                placeholder="e.g. Suite 100"
                                                :error-messages="addressForm.errors.line2"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.city"
                                                label="City"
                                                placeholder="e.g. New York"
                                                :error-messages="addressForm.errors.city"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-text-field
                                                v-model="addressForm.state"
                                                label="State/Province"
                                                placeholder="e.g. NY"
                                                :error-messages="addressForm.errors.state"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-text-field
                                                v-model="addressForm.postal_code"
                                                label="Postal Code"
                                                placeholder="e.g. 10001"
                                                :error-messages="addressForm.errors.postal_code"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.country"
                                                label="Country"
                                                placeholder="e.g. United States"
                                                :error-messages="addressForm.errors.country"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit">Add Address</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Edit Address Form -->
                            <v-card-text v-else>
                                <v-form @submit.prevent="updateAddress">
                                    <v-row>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.name"
                                                label="Label"
                                                placeholder="e.g. Main Office"
                                                :error-messages="addressForm.errors.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-select
                                                v-model="addressForm.type"
                                                :items="Object.keys(addressTypes)"
                                                label="Type"
                                                :error-messages="addressForm.errors.type"
                                                required
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-checkbox
                                                v-model="addressForm.is_default"
                                                label="Default"
                                                :error-messages="addressForm.errors.is_default"
                                            ></v-checkbox>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="addressForm.line1"
                                                label="Address Line 1"
                                                placeholder="e.g. 123 Main St"
                                                :error-messages="addressForm.errors.line1"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="addressForm.line2"
                                                label="Address Line 2"
                                                placeholder="e.g. Suite 100"
                                                :error-messages="addressForm.errors.line2"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.city"
                                                label="City"
                                                placeholder="e.g. New York"
                                                :error-messages="addressForm.errors.city"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-text-field
                                                v-model="addressForm.state"
                                                label="State/Province"
                                                placeholder="e.g. NY"
                                                :error-messages="addressForm.errors.state"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="2">
                                            <v-text-field
                                                v-model="addressForm.postal_code"
                                                label="Postal Code"
                                                placeholder="e.g. 10001"
                                                :error-messages="addressForm.errors.postal_code"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="addressForm.country"
                                                label="Country"
                                                placeholder="e.g. United States"
                                                :error-messages="addressForm.errors.country"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit" class="mr-2">Update Address</v-btn>
                                            <v-btn @click="cancelAddressEdit">Cancel</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Addresses Table -->
                            <v-card-text>
                                <v-table v-if="customer.addresses && customer.addresses.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="address in customer.addresses" :key="address.id">
                                            <td>
                                                <v-icon v-if="address.is_default" color="success">mdi-check</v-icon>
                                            </td>
                                            <td>{{ address.name }}</td>
                                            <td>{{ address.type }}</td>
                                            <td>
                                                {{ address.line1 }}<br>
                                                <span v-if="address.line2">{{ address.line2 }}<br></span>
                                                {{ address.city }}, {{ address.state }} {{ address.postal_code }}<br>
                                                {{ address.country }}
                                            </td>
                                            <td>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-pencil" @click="editAddress(address)" color="primary me-2">
                                                    Edit
                                                </v-btn>
                                                <v-btn variant="flat" size="small" prepend-icon="mdi-delete" @click="deleteAddress(address)" color="error">
                                                    Delete
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                                <div v-else class="text-center pa-4">
                                    No addresses added yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <v-card>
                            <v-card-title class="text-h6">
                                Categories
                            </v-card-title>

                            <!-- Categories Form -->
                            <v-card-text>
                                <v-form @submit.prevent="updateCategories">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="selectedCategories"
                                                :items="customerCategories"
                                                item-title="name"
                                                item-value="id"
                                                label="Select Categories"
                                                multiple
                                                chips
                                                :error-messages="errors.category_ids"
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn color="primary" type="submit">Update Categories</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>

                            <!-- Current Categories -->
                            <v-card-text>
                                <div class="text-h6 mb-4">Current Categories</div>
                                <v-chip-group v-if="customer.categories && customer.categories.length > 0">
                                    <v-chip v-for="category in customer.categories" :key="category.id">
                                        {{ category.name }}
                                    </v-chip>
                                </v-chip-group>
                                <div v-else class="text-center pa-4">
                                    No categories assigned yet.
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </app-layout>
</template>
