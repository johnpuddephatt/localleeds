<template>
    <div class="relative mt-1 mb-4 grid w-full gap-4 xl:grid-cols-2">
        <Combobox class="mb-auto" v-model="selectedValues" multiple>
            <div class="relative">
                <ComboboxInput
                    class="relative h-10 w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    @change="query = $event.target.value"
                    :displayValue="(entry) => entry[itemValue]"
                    placeholder="Search for a language..."
                />
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <SelectorIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </ComboboxButton>
                <TransitionRoot
                    leave="transition ease-in duration-100"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                    @after-leave="query = ''"
                    as="template"
                >
                    <ComboboxOptions
                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    >
                        <div
                            v-if="filteredData.length === 0 && query !== ''"
                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                        >
                            Nothing found.
                        </div>
                        <ComboboxOption
                            as="template"
                            v-for="entry in filteredData"
                            :key="entry[itemKey]"
                            :value="entry[itemKey]"
                            v-slot="{ selected, active }"
                        >
                            <li
                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                :class="{
                                    'bg-teal-600 text-white': active,
                                    'text-gray-900': !active,
                                }"
                            >
                                <span
                                    class="block truncate"
                                    :class="{
                                        'font-medium': selected,
                                        'font-normal': !selected,
                                    }"
                                >
                                    {{ entry[itemValue] }}
                                </span>
                                <span
                                    v-if="selected"
                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                    :class="{
                                        'text-white': active,
                                        'text-teal-600': !active,
                                    }"
                                >
                                    <CheckIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </TransitionRoot>
            </div>
        </Combobox>
        <ul
            class="relative w-full divide-y rounded-md border border-gray-300 shadow-sm"
            v-if="modelValue.length > 0"
        >
            <li
                class="flex flex-row items-center justify-between py-2 px-4"
                v-for="entry in selectedValues"
                :key="entry[itemKey]"
            >
                {{ data.find((item) => item[itemKey] === entry)[itemValue] }}

                <button
                    @click.prevent="removeItem(entry)"
                    class="text-sm font-semibold lowercase text-gray-500"
                >
                    Remove
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { onMounted, ref, computed, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";

const props = defineProps({
    modelValue: Array,
    data: Array,
    itemKey: String,
    itemValue: String,
});

const selectedValues = ref([]);
const query = ref("");

const emit = defineEmits(["update:modelValue"]);

watch(
    () => selectedValues,
    (first, second) => {
        emit("update:modelValue", first.value);
    },
    {
        deep: true,
    }
);

function removeItem(entry) {
    selectedValues.value = selectedValues.value.filter((obj) => obj !== entry);
    emit("update:modelValue", selectedValues.value);
}

const filteredData = computed(() =>
    query.value === ""
        ? props.data
        : props.data.filter((entry) => {
              return entry[props.itemValue]
                  .toLowerCase()
                  .includes(query.value.toLowerCase());
          })
);

onMounted(() => {
    selectedValues.value = props.modelValue;

    if (!props.modelValue) {
        emit("update:modelValue", []);
    }
});
</script>
