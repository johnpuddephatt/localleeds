<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-gray-500">
            {{ message }}
        </p>
    </div>
</template>
