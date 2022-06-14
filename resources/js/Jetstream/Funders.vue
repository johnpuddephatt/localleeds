<template>
    <div>
        <ul class="mt-1 divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(funding, key) in modelValue"
            >
                <div>
                    {{ funding.source.substring(0, 100)
                    }}<span v-if="funding.source.length > 100">...</span>
                </div>

                <button
                    @click.prevent="openFundingModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeFunding(key)"
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
            No funders added
        </div>

        <JetSecondaryButton class="mt-2" @click="openFundingModal()"
            >Add funder</JetSecondaryButton
        >
    </div>
    <JetDialogModal :show="addingFundingNumber">
        <template #title>Add funder details</template>

        <template #content>
            <form ref="form">
                <div class="mt-4">
                    <JetLabel for="funding_source" value="Funding source" />

                    <JetTextarea
                        required
                        maxlength="500"
                        v-model="fundingOptions[currentlyEditingFunding].source"
                        id="funding_source"
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelFundingModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveFunding">
                {{
                    fundingOptions.length > modelValue.length
                        ? "Add funder"
                        : "Update funder"
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
import { usePage } from "@inertiajs/inertia-vue3";
import { v4 as uuidv4 } from "uuid";

const props = defineProps({
    modelValue: Array,
});

const emit = defineEmits(["update:modelValue"]);

const addingFundingNumber = ref(false);
const currentlyEditingFunding = ref(null);
const fundingOptions = ref([]);

const form = ref(false);

watch(
    () => props.modelValue,
    (value) => {
        fundingOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    }
);

onMounted(() => {
    fundingOptions.value = JSON.parse(JSON.stringify(props.modelValue));
});

const openFundingModal = (key) => {
    if (key == undefined) {
        fundingOptions.value.push({});
    }
    currentlyEditingFunding.value = key ?? fundingOptions.value.length - 1;
    addingFundingNumber.value = true;
};

const cancelFundingModal = () => {
    fundingOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingFundingNumber.value = false;
};

const saveFunding = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", fundingOptions.value);
        addingFundingNumber.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeFunding = (key) => {
    fundingOptions.value.splice(key, 1);
    emit("update:modelValue", fundingOptions.value);
};
</script>

<style></style>
