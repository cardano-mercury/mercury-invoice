<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    errors: Object,
    customer: Object,
    customerCategories: Array,
    phoneTypes: Object,
    addressTypes: Object,
});

// Active tab management
const activeTab = ref('basic');

// Form for delete action
const form = useForm(props.customer);

const handleDelete = () => {
    if (confirm(`Are you sure you want to delete ${props.customer.name}?`)) {
        form.delete(route('customers.destroy', props.customer.id));
    }
};
</script>

<template>
    <AppLayout :title="'Customer: ' + customer.name">
        <template #header>
            <PageHeader 
                :title="customer.name" 
                subtitle="Customer Details"
                icon="mdi-account"
            >
                <template #actions>
                    <v-chip v-if="customer.tax_number" variant="tonal" class="mr-2">
                        <v-icon start icon="mdi-file-document" size="small" />
                        Tax #: {{ customer.tax_number }}
                    </v-chip>
                    <v-chip v-if="customer.tax_rate" variant="tonal" color="primary" class="mr-2">
                        <v-icon start icon="mdi-percent" size="small" />
                        {{ customer.tax_rate }}% Tax
                    </v-chip>
                    <v-btn
                        :href="route('customers.edit', customer.id)"
                        variant="tonal"
                        color="primary"
                        prepend-icon="mdi-pencil"
                    >
                        Edit
                    </v-btn>
                    <v-btn
                        variant="tonal"
                        color="error"
                        prepend-icon="mdi-trash-can"
                        @click="handleDelete"
                    >
                        Delete
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
                        <v-row>
                            <v-col cols="12" md="4">
                                <v-card variant="tonal" class="h-100">
                                    <v-card-text>
                                        <div class="text-overline text-medium-emphasis mb-2">
                                            <v-icon icon="mdi-domain" size="small" class="mr-1" />
                                            Customer Name
                                        </div>
                                        <div class="text-h6 font-weight-bold">
                                            {{ customer.name || 'N/A' }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" md="4" v-if="customer.tax_number">
                                <v-card variant="tonal" class="h-100">
                                    <v-card-text>
                                        <div class="text-overline text-medium-emphasis mb-2">
                                            <v-icon icon="mdi-file-document" size="small" class="mr-1" />
                                            Tax Number
                                        </div>
                                        <div class="text-h6 font-weight-bold font-mono">
                                            {{ customer.tax_number }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="12" md="4" v-if="customer.tax_rate">
                                <v-card variant="tonal" color="primary" class="h-100">
                                    <v-card-text>
                                        <div class="text-overline mb-2">
                                            <v-icon icon="mdi-percent" size="small" class="mr-1" />
                                            Tax Rate
                                        </div>
                                        <div class="text-h6 font-weight-bold">
                                            {{ customer.tax_rate }}%
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>

                    <!-- Emails Tab -->
                    <v-window-item value="emails">
                        <v-data-table
                            v-if="customer.emails && customer.emails.length > 0"
                            :items="customer.emails"
                            :headers="[
                                { title: 'Default', key: 'is_default', width: '80px' },
                                { title: 'Label', key: 'name' },
                                { title: 'Email Address', key: 'address' },
                            ]"
                            density="comfortable"
                            hide-default-footer
                        >
                            <template #item.is_default="{ item }">
                                <v-icon v-if="item.is_default" color="success" icon="mdi-check-circle" />
                            </template>
                            <template #item.address="{ item }">
                                <a :href="`mailto:${item.address}`" class="text-primary">
                                    {{ item.address }}
                                </a>
                            </template>
                        </v-data-table>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-email-off" size="48" color="primary" class="mb-4" />
                            <p class="text-body-2 text-medium-emphasis">No email addresses added yet.</p>
                            <v-btn
                                :href="route('customers.edit', customer.id)"
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                class="mt-2"
                            >
                                Add Email
                            </v-btn>
                        </div>
                    </v-window-item>

                    <!-- Phones Tab -->
                    <v-window-item value="phones">
                        <v-data-table
                            v-if="customer.phones && customer.phones.length > 0"
                            :items="customer.phones"
                            :headers="[
                                { title: 'Default', key: 'is_default', width: '80px' },
                                { title: 'Label', key: 'name' },
                                { title: 'Type', key: 'type' },
                                { title: 'Number', key: 'number' },
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
                            <template #item.number="{ item }">
                                <a :href="`tel:${item.number}`" class="text-primary">
                                    {{ item.number }}
                                </a>
                            </template>
                        </v-data-table>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-phone-off" size="48" color="primary" class="mb-4" />
                            <p class="text-body-2 text-medium-emphasis">No phone numbers added yet.</p>
                            <v-btn
                                :href="route('customers.edit', customer.id)"
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                class="mt-2"
                            >
                                Add Phone
                            </v-btn>
                        </div>
                    </v-window-item>

                    <!-- Addresses Tab -->
                    <v-window-item value="addresses">
                        <v-row v-if="customer.addresses && customer.addresses.length > 0">
                            <v-col 
                                v-for="address in customer.addresses" 
                                :key="address.id"
                                cols="12"
                                md="6"
                                lg="4"
                            >
                                <v-card variant="outlined" class="h-100">
                                    <v-card-title class="d-flex align-center">
                                        <v-chip size="small" variant="tonal" class="mr-2">
                                            {{ address.type }}
                                        </v-chip>
                                        {{ address.name }}
                                        <v-icon 
                                            v-if="address.is_default" 
                                            icon="mdi-check-circle" 
                                            color="success" 
                                            size="small"
                                            class="ml-2"
                                        />
                                    </v-card-title>
                                    <v-card-text>
                                        <div class="text-body-2">
                                            {{ address.line1 }}<br />
                                            <span v-if="address.line2">{{ address.line2 }}<br /></span>
                                            {{ address.city }}, {{ address.state }} {{ address.postal_code }}<br />
                                            {{ address.country }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-map-marker-off" size="48" color="primary" class="mb-4" />
                            <p class="text-body-2 text-medium-emphasis">No addresses added yet.</p>
                            <v-btn
                                :href="route('customers.edit', customer.id)"
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                class="mt-2"
                            >
                                Add Address
                            </v-btn>
                        </div>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <div v-if="customer.categories && customer.categories.length > 0">
                            <div class="d-flex flex-wrap ga-2">
                                <v-chip 
                                    v-for="category in customer.categories" 
                                    :key="category.id"
                                    variant="tonal"
                                    color="primary"
                                    size="large"
                                >
                                    <v-icon start icon="mdi-tag" />
                                    {{ category.name }}
                                </v-chip>
                            </div>
                        </div>
                        <div v-else class="text-center py-12">
                            <v-icon icon="mdi-tag-off" size="48" color="primary" class="mb-4" />
                            <p class="text-body-2 text-medium-emphasis">No categories assigned yet.</p>
                            <v-btn
                                :href="route('customers.edit', customer.id)"
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                class="mt-2"
                            >
                                Assign Categories
                            </v-btn>
                        </div>
                    </v-window-item>
                </v-window>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>

<style scoped>
.font-mono {
    font-family: 'Source Code Pro', monospace;
}
</style>
