<template>
    <div>
        <ul class="divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(cost, key) in modelValue"
            >
                <div>
                    <div class="font-semibold leading-none">
                        {{ cost.option }}
                    </div>
                    <div v-if="cost.amount" class="text-sm text-gray-700">
                        £{{ cost.amount }} ({{ cost.amount_description }})
                    </div>
                    <div
                        v-if="cost.valid_from || cost.valid_to"
                        class="mt-2 text-sm"
                    >
                        {{ cost.valid_from }}
                        <span v-if="cost.valid_from && cost.valid_to">–</span>
                        {{ cost.valid_to }}
                    </div>
                </div>

                <button
                    @click.prevent="openCostModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeCost(key)"
                    class="text-sm font-semibold lowercase text-gray-500"
                >
                    Delete
                </button>
            </li>
        </ul>
        <div
            v-else
            class="mt-1 rounded border bg-gray-50 p-4 text-center text-sm text-gray-500 shadow-sm"
        >
            No cost options added
        </div>

        <JetSecondaryButton class="mt-2" @click="openCostModal()"
            >Add cost option</JetSecondaryButton
        >
    </div>
    <JetDialogModal :show="addingCostNumber">
        <template #title>Add new cost option </template>

        <template #content>
            <form ref="form">
                <div class="mt-4">
                    <JetLabel
                        for="cost_option"
                        value="Who does this cost apply to?"
                    />

                    <JetInput
                        v-model="costOptions[currentlyEditingCost].option"
                        id="cost_option"
                        maxlength="200"
                        required
                        placeholder="e.g. ‘everyone’ or ‘over 60s only’"
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel for="cost_amount" value="Price" />

                    <div class="flex w-3/4 flex-row gap-4">
                        <div class="relative">
                            <div class="absolute left-2 mt-1 p-2 text-gray-500">
                                £
                            </div>
                            <JetInput
                                v-model="
                                    costOptions[currentlyEditingCost].amount
                                "
                                id="cost_amount"
                                max="100"
                                min="0"
                                required
                                type="number"
                                class="mt-1 block pl-10"
                            />
                        </div>
                        <JetSelect
                            v-model="
                                costOptions[currentlyEditingCost]
                                    .amount_description
                            "
                            :options="{
                                'per session': 'per session',
                                'one-off': 'one-off',
                                weekly: 'weekly',
                                fornightly: 'fortnightly',
                                monthly: 'monthly',
                                annually: 'annually',
                            }"
                            id="cost_amount_description"
                            class="mt-1 block w-3/4"
                        />
                    </div>
                </div>
                <!-- Cost always valid -->
                <div
                    class="col-span-6 mt-4 flex w-3/4 flex-row gap-4 sm:col-span-4"
                >
                    <JetCheckbox
                        id="cost_option_always_valid"
                        name="cost_option_always_valid"
                        :checked="costOptionAlwaysValid"
                        v-model="costOptionAlwaysValid"
                    />
                    <JetLabel
                        for="cost_option_always_valid"
                        value="This cost option is always valid"
                    />
                </div>

                <div
                    v-if="!costOptionAlwaysValid"
                    class="mt-4 grid w-3/4 grid-cols-2 gap-4"
                >
                    <div>
                        <JetLabel
                            for="cost_valid_from"
                            value="Date valid from"
                        />
                        <JetInput
                            v-model="
                                costOptions[currentlyEditingCost].valid_from
                            "
                            id="cost_valid_from"
                            type="date"
                            class="mt-1 w-full"
                        />
                    </div>
                    <div>
                        <JetLabel for="cost_valid_to" value="Date valid to" />
                        <JetInput
                            v-model="costOptions[currentlyEditingCost].valid_to"
                            id="cost_valid_to"
                            type="date"
                            class="mt-1 w-full"
                        />
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelCostModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveCost">
                {{
                    costOptions.length > modelValue.length
                        ? "Add cost option"
                        : "Update cost option"
                }}
            </JetButton>
        </template>
    </JetDialogModal>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSelect from "@/Jetstream/Select.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const props = defineProps({
    modelValue: Array,
});

const form = ref(null);

const emit = defineEmits(["update:modelValue"]);

const addingCostNumber = ref(false);
const currentlyEditingCost = ref(null);
const costOptions = ref([]);
const costOptionAlwaysValid = ref(true);

onMounted(() => {
    costOptions.value = JSON.parse(JSON.stringify(props.modelValue));
});

watch(
    () => props.modelValue,
    (value) => {
        costOptions.value = JSON.parse(JSON.stringify(value));
    }
);

watch(costOptionAlwaysValid, (value) => {
    if (value == true) {
        costOptions.value[currentlyEditingCost.value].valid_from = null;
        costOptions.value[currentlyEditingCost.value].valid_to = null;
    }
});

const openCostModal = (key) => {
    if (key == undefined) {
        costOptions.value.push({});
    }
    currentlyEditingCost.value = key ?? costOptions.value.length - 1;
    addingCostNumber.value = true;
};

const cancelCostModal = () => {
    costOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingCostNumber.value = false;
};

const saveCost = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", costOptions.value);
        addingCostNumber.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeCost = (key) => {
    costOptions.value.splice(key, 1);
    emit("update:modelValue", costOptions.value);
};
</script>

<style></style>
