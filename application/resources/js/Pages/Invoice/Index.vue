<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from '@inertiajs/vue3';

defineProps({
    invoices: Array
});
</script>

<template>
    <app-layout title="Invoices">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoices
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="flex gap-6 justify-end mb-6">
                            <a target="_blank" :href="route('invoices.export')" class="btn btn-gray">
                                Export Invoices
                            </a>
                            <Link :href="route('invoices.create')">
                                <button class="btn btn-blue">
                                    Create New
                                </button>
                            </Link>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left">Reference</th>
                                    <th class="text-left">Customer</th>
                                    <th class="text-left">Issue Date</th>
                                    <th class="text-left">Due Date</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-right">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="invoice in invoices" :key="invoice.id">
                                    <td>{{ invoice.invoice_reference }}</td>
                                    <td>{{ invoice.customer.name }}</td>
                                    <td>{{ invoice.issue_date }}</td>
                                    <td>{{ invoice.due_date }} <span v-if="invoice.is_overdue" class="sm-badge-red">Late</span> </td>
                                    <td>{{ parseFloat(invoice.total).toFixed(2) }} {{ invoice.currency }}</td>
                                    <td>
                                        <span :class="`status-${invoice.status.replace(' ', '_')}`">
                                            {{ invoice.status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <Link :href="route('invoices.show', invoice.invoice_reference)">
                                            <button class="btn">
                                                View
                                            </button>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
