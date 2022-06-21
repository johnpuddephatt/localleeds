<template>
    <div>
        <ul class="divide-y rounded border" v-if="modelValue.length">
            <li
                class="flex flex-row gap-4 p-4"
                v-for="(location, key) in modelValue"
            >
                <div class="truncate">
                    {{ location.name }}
                    <div
                        v-if="location.physical_address"
                        class="truncate text-sm text-gray-500"
                    >
                        {{ location.physical_address.address_1 }}
                    </div>
                </div>

                <button
                    @click.prevent="openLocationModal(key)"
                    class="ml-auto text-sm font-semibold lowercase text-gray-500"
                >
                    Edit
                </button>
                <button
                    @click.prevent="removeLocation(key)"
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
            No locations added
        </div>

        <JetSecondaryButton class="mt-2" @click="openLocationModal()"
            >{{ modelValue.length ? "Add another location" : "Add location" }}
        </JetSecondaryButton>
    </div>
    <JetDialogModal :show="addingLocationNumber">
        <template #title>Add location details</template>

        <template #content>
            <form ref="form">
                <div class="mt-4">
                    <JetLabel for="location_name" value="Name" />
                    <JetInput
                        required
                        maxlength="100"
                        id="location_name"
                        type="text"
                        placeholder="e.g. North Field Community Centre"
                        v-model="locationOptions[currentlyEditingLocation].name"
                        class="mt-1 block w-3/4"
                    />
                </div>

                <div class="mt-4">
                    <JetLabel for="location_address" value="Address" />
                    <GMapAutocomplete
                        required
                        @place_changed="updateAddress"
                        type="text"
                        :value="
                            locationOptions[currentlyEditingLocation]
                                .physical_address?.address_1
                        "
                        class="mt-1 block w-3/4 rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        id="location_address"
                        placeholder="Search for an address..."
                        :options="{
                            bounds: {
                                north: 54,
                                south: 53.7,
                                east: -1.37,
                                west: -1.84,
                            },
                        }"
                    >
                    </GMapAutocomplete>
                </div>
                <div class="mt-4 mb-44">
                    <JetLabel
                        for="location_accessibilities"
                        value="Accessibility features"
                    />
                    <JetListbox
                        id="location_accessibilities"
                        v-model="
                            locationOptions[currentlyEditingLocation]
                                .accessibilities
                        "
                        class="mt-1 block w-3/4"
                        :data="accessibilities"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click.prevent="cancelLocationModal">
                Cancel
            </JetSecondaryButton>

            <JetButton class="ml-3" @click.prevent="saveLocation">
                {{
                    locationOptions.length > modelValue.length
                        ? "Add location"
                        : "Update location"
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
import JetListbox from "@/Jetstream/Listbox.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const props = defineProps({
    modelValue: Array,
    accessibilities: Object,
});

const emit = defineEmits(["update:modelValue"]);

const addingLocationNumber = ref(false);
const currentlyEditingLocation = ref(null);
const locationOptions = ref([]);

const form = ref(null);

onMounted(() => {
    locationOptions.value = JSON.parse(JSON.stringify(props.modelValue));
});

watch(
    () => props.modelValue,
    (value) => {
        locationOptions.value = JSON.parse(JSON.stringify(value));
    }
);

const getPlaceName = (place) => {
    if (getAddressComponent(place, "street_number")) {
        return place.name;
    } else {
        return place.name + ", " + getAddressComponent(place, "route");
    }
};

const getAddressComponent = (place, component) => {
    return place.address_components.find((address_component) =>
        address_component.types.includes(component)
    )?.long_name;
};

const updateAddress = (place) => {
    let location = currentlyEditingLocation.value;
    locationOptions.value[location].latitude = place.geometry.location.lat();
    locationOptions.value[location].longitude = place.geometry.location.lng();
    locationOptions.value[location].physical_address = {
        address_1: getPlaceName(place) ?? null,
        city: getAddressComponent(place, "postal_town") ?? null,
        state_province:
            getAddressComponent(place, "administrative_area_level_2") ?? null,
        postal_code: getAddressComponent(place, "postal_code"),
        country: getAddressComponent(place, "country"),
    };
};

const openLocationModal = (key) => {
    if (key == undefined) {
        locationOptions.value.push({
            accessibilities: [],
        });
    }
    currentlyEditingLocation.value = key ?? locationOptions.value.length - 1;
    addingLocationNumber.value = true;
};

const cancelLocationModal = () => {
    locationOptions.value = JSON.parse(JSON.stringify(props.modelValue));
    addingLocationNumber.value = false;
};

const saveLocation = () => {
    if (form.value.checkValidity()) {
        emit("update:modelValue", locationOptions.value);
        addingLocationNumber.value = false;
    } else {
        form.value.reportValidity();
    }
};

const removeLocation = (key) => {
    locationOptions.value.splice(key, 1);
    emit("update:modelValue", locationOptions.value);
};
</script>

<style></style>
