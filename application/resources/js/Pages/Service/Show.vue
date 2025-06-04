<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3';

const props = defineProps({
    errors: Object, 
    service: Object,
    serviceCategories: Array
});

// Active tab management
const activeTab = ref('basic');

const form = useForm(props.service);
</script>

<template>
    <app-layout :title="'Service: ' + service.name">
        <template #header>
            <h1>Service: {{ service.name }}</h1>
            <v-row class="d-flex justify-space-between">
                <v-col class="d-flex ga-2">
                    <v-chip label class="me-2">
                        Unit Price: {{ parseFloat(service.unit_price).toFixed(2) }}
                    </v-chip>
                    <v-chip label class="me-2" v-if="service.supplier">
                        Supplier: {{ service.supplier }}
                    </v-chip>
                </v-col>
                <v-col class="d-flex justify-end align-end ga-2">
                    <v-btn :href="route('services.edit', service.id)"
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
                           @click="form.delete(route('services.destroy', service.id))"
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
                            <!-- Service Name -->
                            <v-col cols="12" sm="4">
                                <v-card flat class="py-2 px-2 rounded-lg grey lighten-4">
                                    <div class="text-overline mb-1 text--secondary">
                                        Service Name
                                    </div>
                                    <div class="text-h6">
                                        {{ service.name || 'N/A' }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Unit Price -->
                            <v-col cols="12" sm="4">
                                <v-card flat class="py-2 px-2 rounded-lg blue lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Unit Price
                                    </div>
                                    <div class="text-h6 d-flex align-center">
                                        {{ parseFloat(service.unit_price).toFixed(2) }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Supplier -->
                            <v-col cols="12" sm="4" v-if="service.supplier">
                                <v-card flat class="py-2 px-2 rounded-lg green lighten-5">
                                    <div class="text-overline mb-1 text--secondary">
                                        Supplier
                                    </div>
                                    <div class="text-h6">
                                        {{ service.supplier }}
                                    </div>
                                </v-card>
                            </v-col>

                            <!-- Description -->
                            <v-col cols="12" v-if="service.description">
                                <v-card flat class="py-2 px-2 rounded-lg grey lighten-4">
                                    <div class="text-overline mb-1 text--secondary">
                                        Description
                                    </div>
                                    <div class="text-body-1">
                                        {{ service.description }}
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
                                <v-chip-group v-if="service.categories && service.categories.length > 0">
                                    <v-chip v-for="category in service.categories" :key="category.id">
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
