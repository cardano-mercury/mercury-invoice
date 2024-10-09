<script setup>
import {Head} from "@inertiajs/vue3";

defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />
        <section class="py-20 overflow-hidden grid justify-items-center">
            <slot />
        </section>
    </div>
</template>

<style>
body {
    @apply bg-gray-100;
}
.btn {
    @apply font-bold rounded;
}
.btn-blue {
    @apply bg-blue-500 text-white;
}
.sm-badge {
    @apply bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300
}
.status-Draft {
    @apply font-normal me-2 px-2 py-1 rounded bg-blue-100 text-blue-700
}

.status-Published {
    @apply font-normal me-2 px-2 py-1 rounded bg-violet-100 text-violet-700
}

.status-Payment_Processing {
    @apply font-normal me-2 px-2 py-1 rounded bg-amber-100 text-amber-700
}

.status-Paid {
    @apply font-normal me-2 px-2 py-1 rounded bg-green-100 text-green-700
}

.status-Voided {
    @apply font-normal me-2 px-2 py-1 rounded bg-red-100 text-red-700
}
</style>
