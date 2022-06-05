<template>
    <div>
        <ul class="divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(eligibility, key) in modelValue"
            >
                <div>
                    <div class="font-semibold leading-none">
                        {{ eligibility.minimum_age ?? "No minimum age" }} to
                        {{ eligibility.maximum_age ?? " No maximum age" }}
                    </div>
                </div>

                <button
                    @click.prevent="openEligibilityModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeEligibility(key)"
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
            No eligibility requirements added
        </div>

        <JetSecondaryButton class="mt-2" @click="openEligibilityModal()"
            >Add eligibility requirement</JetSecondaryButton
        >
    </div>
    <JetDialogModal :show="addingEligibilityNumber">
        <template #title>Add new eligibility requirement </template>

        <template #content>
            <form ref="form" class="grid w-3/4 grid-cols-2 gap-4">
                <div class="w-full">
                    <JetLabel
                        for="eligibility_minimum_age"
                        value="Minimum age"
                    />
                    <JetInput
                        v-model="
                            eligibilityOptions[currentlyEditingEligibility]
                                .minimum_age
                        "
                        id="eligibility_minimum_age"
                        type="number"
                        class="mt-1 block w-full"
                        max="127"
                        min="0"
                    />
                </div>
                <div class="w-full">
                    <JetLabel
                        for="eligibility_maximum_age"
                        value="Maximum age"
                    />
                    <JetInput
                        v-model="
                            eligibilityOptions[currentlyEditingEligibility]
                                .maximum_age
                        "
                        id="eligibility_maximum_age"
                        max="127"
                        min="0"
                        type="number"
                        class="mt-1 block w-full"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelEligibilityModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveEligibility">
                {{
                    eligibilityOptions.length > modelValue.length
                        ? "Add eligibility requirement"
                        : "Update eligibility requirement"
                }}
            </JetButton>
        </template>
    </JetDialogModal>
</template>
<script setup>
import { onMounted, ref, watch } from "vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSelect from "@/Jetstream/Select.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const form = ref(null);

const props = defineProps({
    modelValue: Array,
    options: Object,
});

const emit = defineEmits(["update:modelValue"]);

const addingEligibilityNumber = ref(false);
const currentlyEditingEligibility = ref(null);
const eligibilityOptions = ref([]);

onMounted(() => {
    eligibilityOptions.value = JSON.parse(JSON.stringify(props.modelValue));
});

watch(
    () => props.modelValue,
    (value) => {
        eligibilityOptions.value = JSON.parse(JSON.stringify(value));
    }
);

const openEligibilityModal = (key) => {
    if (key == undefined) {
        eligibilityOptions.value.push({});
    }
    currentlyEditingEligibility.value =
        key ?? eligibilityOptions.value.length - 1;
    addingEligibilityNumber.value = true;
};

const cancelEligibilityModal = () => {
    eligibilityOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingEligibilityNumber.value = false;
};

const saveEligibility = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", eligibilityOptions.value);
        addingEligibilityNumber.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeEligibility = (key) => {
    eligibilityOptions.value.splice(key, 1);
    emit("update:modelValue", eligibilityOptions.value);
};
</script>

<style></style>
