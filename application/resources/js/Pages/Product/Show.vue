<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, useForm} from '@inertiajs/vue3';

const props = defineProps({
    errors: Object, 
    product: Object,
    productCategories: Array
});

// Active tab management
const activeTab = ref('basic');

const form = useForm(props.product);
</script>
<template>
    <app-layout :title="'Product: ' + product.name">
        <template #header>
            <h1>Product: {{ product.name }}</h1>
            <v-row class="d-flex justify-space-between">
                <v-col class="d-flex ga-2">
                    <v-chip label v-if="product.sku" class="me-2">SKU: {{ product.sku }}</v-chip>
                    <v-chip label v-if="product.unit_price" class="me-2">
                        Unit Price: {{ parseFloat(product.unit_price).toFixed(2) }}
                        <span v-if="product.unit_type">/{{ product.unit_type }}</span>
                    </v-chip>
                    <v-chip label v-if="product.supplier" class="me-2">
                        Supplier: {{ product.supplier }}
                    </v-chip>
                </v-col>
                <v-col class="d-flex justify-end align-end ga-2">
                    <v-btn :href="route('products.edit', product.id)"
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
                           @click="form.delete(route('products.destroy', product.id))"
                    >
                        Delete
                    </v-btn>
                </v-col>
            </v-row>
        </template>
        
        <v-card class="mb-4">
            <v-tabs v-model="activeTab">
                <v-tab value="basic">Basic Info</v-tab>
                <v-tab value="categories">Categories</v-tab>
            </v-tabs>

            <v-card-text class="bg-white px-4 py-12">
                <v-window v-model="activeTab">
                    <!-- Basic Info Tab -->
                    <v-window-item value="basic">
                        <v-row>
                            <!-- Product Name -->
                            <v-col cols="12" sm="4">
                                <v-card flat class="py-2 px-2 rounded-lg grey lighten-4">
                                    <div class="text-overline mb-1 text--secondary">
                                        Product Name
                                    </div>
                                    <div class="text-h6">
                                        {{ product.name || 'N/A' }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- SKU -->
                            <v-col cols="12" sm="4" v-if="product.sku">
                                <v-card flat class="py-2 px-2 rounded-lg blue-grey lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        SKU
                                    </div>
                                    <div class="text-h6">
                                        {{ product.sku }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Unit Price -->
                            <v-col cols="12" sm="4" v-if="product.unit_price">
                                <v-card flat class="py-2 px-2 rounded-lg blue lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Unit Price
                                    </div>
                                    <div class="text-h6 d-flex align-center">
                                        {{ parseFloat(product.unit_price).toFixed(2) }}
                                        <span v-if="product.unit_type" class="ms-1">/{{ product.unit_type }}</span>
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Supplier -->
                            <v-col cols="12" sm="4" v-if="product.supplier">
                                <v-card flat class="py-2 px-2 rounded-lg green lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Supplier
                                    </div>
                                    <div class="text-h6">
                                        {{ product.supplier }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Description -->
                            <v-col cols="12" v-if="product.description">
                                <v-card flat class="py-2 px-2 rounded-lg grey lighten-4">
                                    <div class="text-overline mb-1 text--secondary">
                                        Description
                                    </div>
                                    <div class="text-body-1">
                                        {{ product.description }}
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>

                    <!-- Categories Tab -->
                    <v-window-item value="categories">
                        <v-card>
                            <v-card-title class="text-h6">Categories</v-card-title>

                            <!-- Current Categories -->
                            <v-card-text>
                                <div class="mb-4">Current Categories</div>
                                <v-chip-group v-if="product.categories && product.categories.length > 0">
                                    <v-chip v-for="category in product.categories" :key="category.id">
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
