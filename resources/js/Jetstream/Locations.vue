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
            <form ref="form" class="min-h-[20rem]">
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
                <div
                    class="mt-4"
                    v-if="
                        locationOptions[currentlyEditingLocation]
                            .physical_address
                    "
                >
                    <JetLabel
                        for="location_accessibilities"
                        value="Accessibility features"
                    />
                    <JetListbox
                        id="location_accessibilities"
                        v-model="
                            locationOptions[currentlyEditingLocation]
                                .accessibility_for_disabilities
                        "
                        class="mt-1 block w-3/4"
                        :data="accessibilities"
                    />
                </div>
                <div
                    class="mt-4"
                    v-if="
                        locationOptions[currentlyEditingLocation]
                            .physical_address
                    "
                >
                    <JetLabel value="Opening times" />
                    <div class="w-3/4 divide-y">
                        <div
                            class="flex flex-row gap-4 py-1"
                            v-for="(schedule, index) in locationOptions[
                                currentlyEditingLocation
                            ].regular_schedules"
                        >
                            <JetSelect
                                class="w-1/3 flex-1"
                                :options="{
                                    0: 'Monday',
                                    1: 'Tuesday',
                                    2: 'Wednesday',
                                    3: 'Thursday',
                                    4: 'Friday',
                                    5: 'Saturday',
                                    6: 'Sunday',
                                }"
                                v-model="schedule.weekday"
                            />
                            <JetInput
                                required
                                type="time"
                                v-model="schedule.opens_at"
                            />
                            <JetInput
                                required
                                type="time"
                                v-model="schedule.closes_at"
                            />
                            <button
                                class="text-sm font-semibold"
                                @click.prevent="
                                    locationOptions[
                                        currentlyEditingLocation
                                    ].regular_schedules.splice(index, 1)
                                "
                            >
                                remove
                            </button>
                        </div>
                    </div>
                    <JetSecondaryButton
                        class="mt-2"
                        @click.prevent="
                            locationOptions[
                                currentlyEditingLocation
                            ].regular_schedules.push({
                                weekday: 1,
                                opens_at: '09:00',
                                closes_at: '17:30',
                            })
                        "
                    >
                        Add new day
                    </JetSecondaryButton>
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
import { usePage } from "@inertiajs/inertia-vue3";

import { ref, watch, onMounted } from "vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetTextarea from "@/Jetstream/Textarea.vue";
import JetSelect from "@/Jetstream/Select.vue";
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
    locationOptions.value[location].name = place.name;

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

    if (
        place.opening_hours &&
        confirm(
            "Opening hours for this location were found on Google. Do you want to automatically add them?"
        ) == true
    ) {
        let schedule = [];
        place.opening_hours.periods.forEach((period) => {
            schedule.push({
                service_id: usePage().props.value.service.id,
                location_id: locationOptions.value[location].id,
                weekday: period.open.day,
                opens_at: `${period.open.time.substring(
                    0,
                    2
                )}:${period.open.time.substring(2, 4)}`,
                closes_at: `${period.close.time.substring(
                    0,
                    2
                )}:${period.close.time.substring(2, 4)}`,
            });
        });
        locationOptions.value[location].regular_schedules = schedule;
    }
};

const openLocationModal = (key) => {
    if (key == undefined) {
        locationOptions.value.push({
            accessibilities: [],
        });
    }
    currentlyEditingLocation.value = key ?? locationOptions.value.length - 1;
    if (
        locationOptions.value[currentlyEditingLocation.value]
            .accessibility_for_disabilities == null
    ) {
        locationOptions.value[
            currentlyEditingLocation.value
        ].accessibility_for_disabilities = [];
    }
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
