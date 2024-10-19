<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3';

const props = defineProps({errors: Object, service: Object})

const form = useForm(props.service);
</script>

<template>
    <app-layout :title="'service: ' + service.name">
        <template #header>
            <h1>
                Service: {{ service.name }}
            </h1>
            <div class="mb-2">
                <v-chip label class="me-2">
                    Unit Price: {{ parseFloat(service.unit_price).toFixed(2) }}
                </v-chip>
                <v-chip label class="me-2" v-if="service.supplier">
                    Supplier: {{ service.supplier }}
                </v-chip>
            </div>
            <p class="text-body-2" v-if="service.description">
                {{ service.description }}
            </p>
        </template>
        <v-sheet class="bg-white px-4 py-12">
            <v-row justify="end">
                <v-col cols="auto">
                    <v-btn :href="route('services.edit', service.id)"
                           variant="flat" prepend-icon="mdi-pencil">
                        Edit
                    </v-btn>
                </v-col>
                <v-col cols="auto">
                    <v-btn variant="flat" color="error"
                           prepend-icon="mdi-trash-can"
                           @click="form.delete(route('services.destroy', service.id))">
                        Delete
                    </v-btn>
                </v-col>
            </v-row>
        </v-sheet>
    </app-layout>
</template>
