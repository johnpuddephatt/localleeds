<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({ filters: Object });
</script>

<template>
    <h1
        class="text-6xl font-semibold"
        v-if="filters && Object.keys(filters).length > 1"
    >
        Search results
    </h1>
    <h1 class="text-6xl font-semibold" v-else>All services</h1>
</template>
