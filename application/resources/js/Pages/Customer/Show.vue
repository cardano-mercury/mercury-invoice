<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
  customer: Object,
  customerCategories: Array,
  phoneTypes: Object,
  addressTypes: Object
});

// Active tab management
const activeTab = ref('basic');

// Form for delete action
const form = useForm(props.customer);
</script>
<template>
    <app-layout :title="'Customer: ' + customer.name">
        <template #header>
            <h1>Customer: {{ customer.name }}</h1>
            <v-row class="d-flex justify-space-between">
                <v-col class="d-flex ga-2">
                    <v-chip label v-if="customer.tax_number">
                        Tax Number: {{ customer.tax_number }}
                    </v-chip>
                    <v-chip label v-if="customer.tax_rate">
                        Tax Rate: {{ customer.tax_rate }}
                    </v-chip>
                </v-col>
                <v-col class="d-flex justify-end align-end ga-2">
                    <v-btn :href="route('customers.edit', customer.id)"
                           variant="flat"
                           color="primary"
                           size="small"
                           prepend-icon="mdi-pencil"
                    >
                        Edit
                    </v-btn>
                    <v-btn variant="flat"
                           color="error"
                           size="small"
                           prepend-icon="mdi-trash-can"
                           @click="form.delete(route('customers.destroy', customer.id))"
                    >
                        Delete
                    </v-btn>
                </v-col>
            </v-row>
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
                <v-window v-model="activeTab">
                    <!-- Basic Info Tab -->
                    <v-window-item value="basic">
                        <v-row>
                            <!-- Customer Name -->
                            <v-col cols="12" sm="4">
                                <v-card flat class="py-2 px-2 rounded-lg grey lighten-4">
                                    <div class="text-overline mb-1 text--secondary">
                                        Customer Name
                                    </div>
                                    <div class="text-h6">
                                        {{ customer.name || 'N/A' }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Tax Number -->
                            <v-col cols="12" sm="4" v-if="customer.tax_number">
                                <v-card flat class="py-2 px-2 rounded-lg blue-grey lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Tax Number
                                    </div>
                                    <div class="text-h6">
                                        {{ customer.tax_number }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Tax Rate -->
                            <v-col cols="12" sm="4" v-if="customer.tax_rate">
                                <v-card flat class="py-2 px-2 rounded-lg blue lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Tax Rate
                                    </div>
                                    <div class="text-h6 d-flex align-center">
                                        {{ customer.tax_rate }}%
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>

                    <!-- Emails Tab -->
                    <v-window-item value="emails">
                        <v-card>
                            <v-card-title class="text-h6">Email Addresses</v-card-title>

                            <!-- Emails Table -->
                            <v-card-text>
                                <v-table v-if="customer.emails && customer.emails.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Email Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="email in customer.emails" :key="email.id">
                                            <td>
                                                <v-icon v-if="email.is_default" color="success">mdi-check</v-icon>
                                            </td>
                                            <td>{{ email.name }}</td>
                                            <td>{{ email.address }}</td>
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
                            <v-card-title class="text-h6">Phone Numbers</v-card-title>

                            <!-- Phones Table -->
                            <v-card-text>
                                <v-table v-if="customer.phones && customer.phones.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Number</th>
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
                            <v-card-title class="text-h6">Addresses</v-card-title>

                            <!-- Addresses Table -->
                            <v-card-text>
                                <v-table v-if="customer.addresses && customer.addresses.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Default</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Address</th>
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
                            <v-card-title class="text-h6">Categories</v-card-title>

                            <!-- Current Categories -->
                            <v-card-text>
                                <div class="mb-4">Current Categories</div>
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
