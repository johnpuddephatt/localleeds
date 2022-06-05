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
        class="-my-2 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-6 py-2 text-base font-semibold tracking-wide text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25"
    >
        <slot />
    </Link>
</template>
