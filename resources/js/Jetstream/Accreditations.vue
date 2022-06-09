<template>
    <div>
        <ul class="divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(accreditation, key) in modelValue"
            >
                <div class="truncate">
                    {{ accreditation.title.substring(0, 100)
                    }}<span v-if="accreditation.title.length > 100">...</span>
                </div>

                <button
                    @click.prevent="openAccreditationModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeAccreditation(key)"
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
            No accreditations added
        </div>

        <JetSecondaryButton class="mt-2" @click="openAccreditationModal()"
            >Add accreditation</JetSecondaryButton
        >
    </div>
    <JetDialogModal :show="addingAccreditationNumber">
        <template #title>Add accreditation details</template>

        <template #content>
            <form ref="form">
                <div class="mt-4">
                    <JetLabel
                        for="accreditation_title"
                        value="Accreditation title"
                    />

                    <JetInput
                        v-model="
                            accreditationOptions[currentlyEditingAccreditation]
                                .title
                        "
                        maxlength="100"
                        id="accreditation_title"
                        required
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="accreditation_description"
                        value="Accreditation description"
                    />

                    <JetTextarea
                        maxlength="500"
                        v-model="
                            accreditationOptions[currentlyEditingAccreditation]
                                .description
                        "
                        id="accreditation_description"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="accreditation_date"
                        value="Accreditation date"
                    />

                    <JetInput
                        v-model="
                            accreditationOptions[currentlyEditingAccreditation]
                                .date
                        "
                        id="accreditation_date"
                        type="date"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="accreditation_score"
                        value="Accreditation score"
                    />

                    <JetInput
                        v-model="
                            accreditationOptions[currentlyEditingAccreditation]
                                .score
                        "
                        maxlength="100"
                        id="accreditation_score"
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="accreditation_url"
                        value="Accreditation URL"
                    />

                    <JetInput
                        maxlength="300"
                        v-model="
                            accreditationOptions[currentlyEditingAccreditation]
                                .url
                        "
                        id="accreditation_url"
                        type="URL"
                        class="mt-1 block w-3/4"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelAccreditationModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveAccreditation">
                {{
                    accreditationOptions.length > modelValue.length
                        ? "Add accreditation"
                        : "Update accreditation"
                }}
            </JetButton>
        </template>
    </JetDialogModal>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetTextarea from "@/Jetstream/Textarea.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const props = defineProps({
    modelValue: Array,
});

const emit = defineEmits(["update:modelValue"]);

const addingAccreditationNumber = ref(false);
const currentlyEditingAccreditation = ref(null);
const accreditationOptions = ref([]);
const form = ref(null);
watch(
    () => props.modelValue,
    (value) => {
        accreditationOptions.value = JSON.parse(JSON.stringify(value));
    }
);

onMounted(() => {
    accreditationOptions.value = JSON.parse(JSON.stringify(props.modelValue));
});

const openAccreditationModal = (key) => {
    if (key == undefined) {
        accreditationOptions.value.push({});
    }
    currentlyEditingAccreditation.value =
        key ?? accreditationOptions.value.length - 1;
    addingAccreditationNumber.value = true;

    if (
        accreditationOptions.value[currentlyEditingAccreditation.value].date
            .length > 10
    ) {
        let formattedDate = new Date(
            accreditationOptions.value[currentlyEditingAccreditation.value].date
        );

        console.log(formattedDate);
        accreditationOptions.value[currentlyEditingAccreditation.value].date =
            formattedDate.toISOString().split("T")[0];

        console.log(formattedDate.toISOString().split("T")[0]);
    }
};

const cancelAccreditationModal = () => {
    accreditationOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingAccreditationNumber.value = false;
};

const saveAccreditation = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", accreditationOptions.value);
        addingAccreditationNumber.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeAccreditation = (key) => {
    accreditationOptions.value.splice(key, 1);
    emit("update:modelValue", accreditationOptions.value);
};
</script>

<style></style>
