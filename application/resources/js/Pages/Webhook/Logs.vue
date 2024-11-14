<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

defineProps({
    webhook: Object
});

const itemsPerPage = ref(10);
const search = ref('');
const headers = [
    {
        title: 'UTC Date & Time',
        align: 'start',
        sortable: true,
        key: 'formatted_datetime',
    },
    {
        title: 'Status',
        align: 'start',
        sortable: true,
        key: 'status',
    },
    {
        title: 'Event Name',
        align: 'start',
        sortable: true,
        key: 'event_name',
    },
    {
        title: 'Attempts',
        align: 'start',
        sortable: true,
        key: 'attempts',
    },
    {
        title: 'Payload',
        align: 'start',
        sortable: true,
        key: 'payload',
    },
];

</script>

<template>
    <app-layout title="Customers">
        <template #header>
            <h1>
                Webhook Logs
            </h1>
        </template>
        <v-sheet class="bg-white px-4 py-12">

            <v-row class="mb-4 px-4">
                {{ webhook.url }}
            </v-row>

            <v-row class="mb-4 px-4" align="center">
                <v-text-field
                    v-model="search"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="outlined"
                    hide-details
                    single-line
                ></v-text-field>
                <v-spacer/>
                <v-btn :href="route('webhooks.index')" variant="flat" color="primary">
                    Go Back
                </v-btn>
            </v-row>

            <v-data-table
                :items="webhook.logs"
                :headers="headers"
                :search="search"
                :items-per-page="itemsPerPage"
                multi-sort
            >
                <template v-slot:item.formatted_datetime="{ item }">
                    {{ item.formatted_datetime.datetime }}
                    <br>
                    <span class="text-disabled">{{ item.formatted_datetime.diff }}</span>
                </template>
                <template v-slot:item.payload="{ item }">
                    <v-dialog>
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn
                                v-bind="activatorProps"
                                color="primary"
                                text="View Payload"
                                variant="flat"
                                size="small"
                                prepend-icon="mdi-magnify"
                            />
                        </template>
                        <template v-slot:default="{ isActive }">
                            <v-card title="Webhook Payload">
                                <v-card-text>
                                    <pre style="overflow: auto; font-size: 0.9rem;">{{ JSON.stringify(JSON.parse(item.payload), null, 2) }}</pre>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        text="Close"
                                        @click="isActive.value = false"
                                    ></v-btn>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-dialog>
                </template>
            </v-data-table>

        </v-sheet>
    </app-layout>
</template>
