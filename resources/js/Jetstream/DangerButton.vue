<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-2xl border border-transparent bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-500 focus:border-red-700 focus:outline-none focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
