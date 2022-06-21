<template>
    <div>
        <ul class="divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(contact, key) in modelValue"
            >
                <div>
                    <div class="font-semibold leading-none">
                        {{ contact.name }}
                    </div>
                    <div class="text-sm text-gray-700">
                        {{ contact.title }}
                    </div>
                    <div class="mt-2 text-sm">{{ contact.phone?.number }}</div>
                </div>

                <button
                    @click.prevent="openContactModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeContact(key)"
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
            No contacts added
        </div>

        <JetSecondaryButton class="mt-2" @click="openContactModal()"
            >Add contact</JetSecondaryButton
        >
    </div>
    <JetDialogModal :show="addingContact">
        <template #title>Add new contact</template>

        <template #content>
            <form ref="form">
                <div class="mt-4">
                    <JetLabel for="contact_name" value="Contact name" />

                    <JetInput
                        v-model="contacts[currentlyEditingContact].name"
                        id="contact_name"
                        maxlength="100"
                        required
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="contact_title"
                        value="Contact role / job role"
                    />

                    <JetInput
                        v-model="contacts[currentlyEditingContact].title"
                        id="contact_title"
                        maxlength="100"
                        type="text"
                        class="mt-1 block w-3/4"
                    />
                </div>
                <div class="mt-4">
                    <JetLabel
                        for="contact_number"
                        value="Contact phone number"
                    />

                    <JetInput
                        v-model="contacts[currentlyEditingContact].phone.number"
                        id="contact_number"
                        required
                        type="text"
                        pattern="[0-9 ]+"
                        data-custom-validity="Phone numbers can only contains numbers (0-9) and spaces"
                        class="mt-1 block w-3/4"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelContactModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveContact">
                {{
                    contacts.length > modelValue.length
                        ? "Add phone number"
                        : "Update phone number"
                }}
            </JetButton>
        </template>
    </JetDialogModal>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const props = defineProps({
    modelValue: Array,
});

const emit = defineEmits(["update:modelValue"]);

const addingContact = ref(false);
const currentlyEditingContact = ref(null);
const contacts = ref([]);
const form = ref(null);

onMounted(() => {
    contacts.value = JSON.parse(JSON.stringify(props.modelValue));
});

watch(
    () => props.modelValue,
    (value) => {
        contacts.value = JSON.parse(JSON.stringify(value));
    }
);

const openContactModal = (key) => {
    if (key == undefined) {
        contacts.value.push({
            phone: {},
        });
    }
    currentlyEditingContact.value = key ?? contacts.value.length - 1;
    addingContact.value = true;
};

const cancelContactModal = () => {
    contacts.value = JSON.parse(JSON.stringify(props.modelValue));
    addingContact.value = false;
};

const saveContact = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", contacts.value);
        addingContact.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeContact = (key) => {
    contacts.value.splice(key, 1);
    emit("update:modelValue", contacts.value);
};
</script>

<style></style>
