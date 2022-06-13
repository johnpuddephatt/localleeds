<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({ filters: Object });
</script>

<template>
    <h1
        class="text-6xl font-semibold"
        v-if="
            filters &&
            Object.keys(filters).length == 1 &&
            filters.service_category
        "
    >
        Browse by category
    </h1>

    <h1
        class="text-6xl font-semibold"
        v-else-if="filters && Object.keys(filters).length == 1 && filters.free"
    >
        Free services
    </h1>

    <h1
        class="text-6xl font-semibold"
        v-else-if="
            filters && Object.keys(filters).length == 2 && filters.postcode
        "
    >
        Services near <span class="uppercase">{{ filters.postcode }}</span>
    </h1>

    <h1
        class="text-6xl font-semibold"
        v-else-if="filters && Object.keys(filters).length > 1"
    >
        Search results
    </h1>

    <h1 class="text-5xl font-semibold md:text-6xl" v-else>All services</h1>
</template>
