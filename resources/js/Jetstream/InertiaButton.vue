<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    href: {
        type: String,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="inline-flex items-center rounded-2xl border border-transparent bg-blue-300 px-6 py-2 text-base font-semibold tracking-wide text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25"
    >
        <slot />
    </Link>
</template>
