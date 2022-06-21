<template>
    <div class="col-span-6 my-2 flex flex-row gap-4 sm:col-span-4">
        <JetCheckbox
            id="open_to_all"
            name="open_to_all"
            :checked="open_to_all"
            v-model="open_to_all"
        />
        <JetLabel for="open_to_all" value="This service is open to everyone" />
    </div>
    <div v-if="!open_to_all">
        <ul class="mt-1 divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(eligibility, key) in modelValue"
            >
                <div>
                    <div class="text-sm font-semibold">
                        <span v-for="(tag_id, key) in eligibility.tags">
                            {{
                                eligibilities.find((item) => item.id == tag_id)
                                    .name
                            }}

                            <span
                                class="text-gray-400"
                                v-if="key + 1 < eligibility.tags.length"
                            >
                                &nbsp;and&nbsp;
                            </span>
                        </span>

                        <span
                            class="text-gray-400"
                            v-if="
                                eligibility.tags &&
                                eligibility.tags &&
                                (eligibility.minimum_age ||
                                    eligibility.maximum_age)
                            "
                            >&nbsp;and&nbsp;</span
                        >

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

                <div class="col-span-2 w-full pb-12">
                    <JetLabel for="eligibility_tags" value="Eligible groups" />
                    <JetListbox
                        v-model="
                            eligibilityOptions[currentlyEditingEligibility].tags
                        "
                        value="label"
                        :data="eligibilities"
                        id="eligibility_tags"
                    />
                    <p class="mt-4 text-sm text-gray-600">
                        The options selected here will be combined into one
                        eligibility requirement (e.g. Female AND Homeless AND
                        aged 18-25).
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        If you want anyone who was Female OR Homeless OR aged
                        18-25, add these as three separate eligibility
                        requirements.
                    </p>
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

    <JetDialogModal
        :show="showDeleteEligibilitiesModal"
        @close="cancelDeleteEligibilitiesModal"
    >
        <template #title> Remove eligibility options? </template>

        <template #content>
            Marking this service as open to all will remove all saved
            eligibility options. Are you sure you want to continue?
        </template>

        <template #footer>
            <JetSecondaryButton @click="cancelDeleteEligibilitiesModal">
                Cancel
            </JetSecondaryButton>

            <JetDangerButton
                class="ml-3"
                @click="confirmDeleteEligibilitiesModal"
            >
                Remove eligibility options
            </JetDangerButton>
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
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetListbox from "@/Jetstream/Listbox.vue";

const form = ref(null);

const props = defineProps({
    modelValue: Array,
    eligibilities: Object,
});

const emit = defineEmits(["update:modelValue"]);

const open_to_all = ref(true);

const addingEligibilityNumber = ref(false);
const currentlyEditingEligibility = ref(null);
const eligibilityOptions = ref([]);

onMounted(() => {
    eligibilityOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    open_to_all.value = props.modelValue.length ? false : true;
});

watch(
    () => props.modelValue,
    (value) => {
        eligibilityOptions.value = JSON.parse(JSON.stringify(value));
    }
);

const openEligibilityModal = (key) => {
    if (key == undefined) {
        eligibilityOptions.value.push({
            minimum_age: null,
            maximum_age: null,
            tags: [],
        });
    }
    currentlyEditingEligibility.value =
        key ?? eligibilityOptions.value.length - 1;
    addingEligibilityNumber.value = true;
};

const cancelEligibilityModal = () => {
    eligibilityOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingEligibilityNumber.value = false;

    if (!props.modelValue.length) {
        open_to_all.value = true;
    }
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

const showDeleteEligibilitiesModal = ref(false);

const cancelDeleteEligibilitiesModal = () => {
    showDeleteEligibilitiesModal.value = false;
    open_to_all.value = false;
};

const confirmDeleteEligibilitiesModal = () => {
    showDeleteEligibilitiesModal.value = false;
    emit("update:modelValue", []);
};

watch(
    () => open_to_all.value,
    (newVal) => {
        if (newVal == true && props.modelValue.length) {
            showDeleteEligibilitiesModal.value = true;
        }

        if (newVal == false && !props.modelValue.length) {
            openEligibilityModal();
        }
    }
);
</script>

<style></style>
