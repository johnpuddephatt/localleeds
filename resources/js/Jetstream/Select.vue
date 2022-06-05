<template>
    <select
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @change="$emit('update:modelValue', $event.target.value)"
        ref="select"
    >
        <option
            v-for="(value, key) in options"
            :value="key"
            :selected="key == modelValue"
        >
            {{ value }}
        </option>
    </select>
</template>

<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
    options: Object,
});

const emit = defineEmits(["update:modelValue"]);

const select = ref(null);

onMounted(() => {
    emit("update:modelValue", select.value.value);
    if (select.value.hasAttribute("autofocus")) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });
</script>
