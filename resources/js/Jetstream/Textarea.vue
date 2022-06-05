<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
    rows: {
        type: Number,
        default: 4,
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        :rows="rows"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
</template>
