<template>
    <div>
        <Listbox v-model="selectedValues" multiple>
            <div class="relative mt-1">
                <ListboxButton
                    class="relative h-11 w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <span
                        v-if="selectedValues && selectedValues.length"
                        class="block truncate px-3 pr-6 text-left"
                    >
                        <span
                            v-if="selectedValues.length < 3"
                            v-for="(entry, key) in selectedValues"
                        >
                            {{ data.find((item) => item.id == entry)?.[value]
                            }}<span v-if="key + 1 < selectedValues.length"
                                >,
                            </span>
                        </span>
                        <span v-else>{{ selectedValues.length }} selected</span>
                    </span>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                    >
                        <SelectorIcon
                            class="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </span>
                </ListboxButton>

                <transition
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <ListboxOptions
                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    >
                        <ListboxOption
                            v-slot="{ active, selected }"
                            v-for="entry in data"
                            :key="entry.name"
                            :value="entry[key]"
                            as="template"
                        >
                            <li
                                :class="[
                                    active
                                        ? 'bg-amber-100 text-amber-900'
                                        : 'text-gray-900',
                                    'relative cursor-default select-none py-2 pl-10 pr-4',
                                ]"
                            >
                                <span
                                    :class="[
                                        selected
                                            ? 'font-medium'
                                            : 'font-normal',
                                        'block truncate',
                                    ]"
                                    >{{ entry[value] }}</span
                                >
                                <span
                                    v-if="selected"
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                >
                                    <CheckIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";

const props = defineProps({
    modelValue: Array,
    data: Array,
    key: { type: String, default: "id" },
    value: { type: String, default: "name" },
});

const emit = defineEmits(["update:modelValue"]);

const selectedValues = ref([]);

watch(
    () => selectedValues,
    (first, second) => {
        emit("update:modelValue", first.value);
    },
    { deep: true }
);

onMounted(() => {
    selectedValues.value = props.modelValue;

    if (!props.modelValue) {
        emit("update:modelValue", []);
    }
});
</script>
