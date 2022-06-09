<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";

import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetSelect from "@/Jetstream/Select.vue";
import JetTextarea from "@/Jetstream/Textarea.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";

import JetLogo from "@/Jetstream/Logo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetPostcode from "@/Jetstream/Postcode.vue";
import JetContacts from "@/Jetstream/Contacts.vue";
import JetCostOptions from "@/Jetstream/CostOptions.vue";
import JetEligibilities from "@/Jetstream/Eligibilities.vue";
import JetFunders from "@/Jetstream/Funders.vue";
import JetAccreditations from "@/Jetstream/Accreditations.vue";
import JetLocations from "@/Jetstream/Locations.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetCombobox from "@/Jetstream/Combobox.vue";
import JetListbox from "@/Jetstream/Listbox.vue";

import JetSectionBorder from "@/Jetstream/SectionBorder";
import JetDialogModal from "@/Jetstream/DialogModal.vue";

const debug = ref(false);

const props = defineProps({
    service: Object,
    languages: Array,
    organisation_id: String,
});

const form = useForm({
    _method: "POST",

    organisation_id: props.service?.organisation_id ?? props.organisation_id,

    status: props.service?.active ?? "active",
    name: props.service?.name ?? null,
    description: props.service?.description ?? null,
    deliverable_type: props.service?.deliverable_type ?? "Advice",
    languages: props.service?.languages ?? ["en"],

    attending_type: props.service?.attending_type ?? "venue",
    attending_access: props.service?.attending_access ?? "drop-in",
    wait_time: props.service?.wait_time ?? null,
    referral_process: props.service?.referral_process ?? null,

    email: props.service?.email ?? null,
    url: props.service?.url ?? null,
    contacts: props.service?.contacts ?? [],

    service_areas: props.service?.service_areas ?? [],

    free: !!!props.service?.cost_options.length ?? true,
    open_to_all: !!!props.service?.eligibilities.length ?? true,
    cost_options: props.service?.cost_options ?? [],
    eligibilities: props.service?.eligibilities ?? [],
    fundings: props.service?.fundings ?? [],
    reviews: props.service?.reviews ?? [],
    locations: props.service?.locations ?? [],
});

const updateService = () => {
    form.post(
        props.service
            ? route("dashboard.service.update", { service: props.service.id })
            : route("dashboard.service.store"),
        {
            errorBag: "updateService",
        }
    );
};

watch(
    () => form.attending_access,
    (newVal, oldVal) => {
        if (!["referral", "appointment"].includes(newVal)) {
            form.wait_time = null;
        }

        if (newVal !== "referral") {
            form.referral_process = null;
        }
    },
    { deep: true }
);

// console.log(attending_access);
// if (!["referral", "appointment"].includes(attending_access)) {
//     form.wait_time = null;
// }

// if (!form.attending_access == "referral") {
//     form.referral_process = null;
// }
// });
</script>

<template>
    <DashboardLayout :title="service ? 'Edit service' : 'Create a new service'">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ service ? "Edit service" : "Create service" }}
            </h2>

            <button
                class="ml-4 rounded border py-1 px-3 text-xs uppercase tracking-widest text-gray-600"
                @click="debug = !debug"
            >
                Debug
            </button>

            <JetButton
                :class="{ 'opacity-25': form.processing }"
                class="ml-auto"
                :disabled="form.processing"
                @click="updateService"
            >
                {{ service ? "Save" : "Create" }}
            </JetButton>
        </template>
        <div
            class="fixed top-0 right-0 bottom-0 w-96 transform overflow-y-auto bg-black p-8 font-mono text-sm text-white transition"
            :class="{
                'translate-x-full': !debug,
            }"
        >
            <h3 class="font-bold">Main form</h3>
            <pre>{{ form }}</pre>
        </div>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <JetFormSection>
                    <template #title>About this service</template>

                    <!--                    
                        <template #description>
                        A summary of the organisation’s details will be shown
                        alongside every service, and each organisation will
                        have its own page listing all the services it
                        delivers.
                    </template> -->

                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="name" value="Name" />
                            <JetInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.name"
                                class="mt-2"
                            />
                        </div>

                        <!-- Description -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="description" value="Description" />
                            <JetTextarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.description"
                                class="mt-2"
                            />
                        </div>

                        <!-- Status -->
                        <!--<div class="col-span-6 sm:col-span-4">
                            <JetLabel for="status" value="Service status" />
                            <JetSelect
                                id="status"
                                v-model="form.status"
                                :options="{
                                    1: 'Active',
                                    2: 'Inactive',
                                    3: 'Defunct',
                                    4: 'Temporarily Closed',
                                }"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.status"
                                class="mt-2"
                            />
                        </div>-->

                        <!-- Type -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel
                                for="deliverable_type"
                                value="Service type"
                            />
                            <JetSelect
                                id="deliverable_type"
                                v-model="form.deliverable_type"
                                :options="{
                                    Advice: 'Advice',
                                    Assessment: 'Assessment',
                                    Counselling: 'Counselling',
                                    Equipment: 'Equipment',
                                    'Financial Support': 'Financial Support',
                                    Information: 'Information',
                                    Training: 'Training',
                                }"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.deliverable_type"
                                class="mt-2"
                            />
                        </div>

                        <!-- Languages -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="languages" value="Languages" />

                            <JetCombobox
                                itemKey="code"
                                itemValue="label"
                                v-model="form.languages"
                                :data="languages"
                            />

                            <JetInputError
                                :message="form.errors.languages"
                                class="mt-2"
                            />
                        </div>
                    </template>
                    <!--<template #actions> </template>-->
                </JetFormSection>

                <JetSectionBorder />

                <JetFormSection>
                    <template #title>Contact details</template>

                    <!--                    
                        <template #description>
                        A summary of the organisation’s details will be shown
                        alongside every service, and each organisation will
                        have its own page listing all the services it
                        delivers.
                    </template> -->

                    <template #form>
                        <!-- Email -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="email" value="Email" />
                            <JetInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.email"
                                class="mt-2"
                            />
                        </div>

                        <!-- Website -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="website" value="Website" />
                            <JetInput
                                id="website"
                                v-model="form.url"
                                type="url"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.url"
                                class="mt-2"
                            />
                        </div>
                        <!-- Phone numbers -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel value="Phone numbers" />
                            <JetContacts v-model="form.contacts" />
                        </div>
                    </template>
                </JetFormSection>

                <JetSectionBorder />

                <JetFormSection>
                    <template #title>Locations and schedules</template>

                    <!--                    
                        <template #description>
                        A summary of the organisation’s details will be shown
                        alongside every service, and each organisation will
                        have its own page listing all the services it
                        delivers.
                    </template> -->

                    <template #form>
                        <!-- Postcodes -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel value="Postcodes" />
                            <JetPostcode v-model="form.service_areas" />
                        </div>
                        <!-- Locations -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="location" value="Locations" />
                            <JetLocations v-model="form.locations" />
                        </div>
                    </template>
                </JetFormSection>
                <JetSectionBorder />

                <JetFormSection>
                    <template #title>Accessing this service</template>

                    <!--                    
                        <template #description>
                        A summary of the organisation’s details will be shown
                        alongside every service, and each organisation will
                        have its own page listing all the services it
                        delivers.
                    </template> -->

                    <template #form>
                        <!-- Delivery type -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel
                                for="attending_type"
                                value="Delivery type"
                            />
                            <JetSelect
                                id="attending_type"
                                v-model="form.attending_type"
                                :options="{
                                    phone: 'Phone',
                                    online: 'Online',
                                    venue: 'Venue',
                                    'home visit': 'Home visit',
                                }"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.attending_type"
                                class="mt-2"
                            />
                        </div>

                        <!-- Attendance Access -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel
                                for="attending_access"
                                value="Attendance access"
                            />
                            <JetSelect
                                id="attending_access"
                                v-model="form.attending_access"
                                :options="{
                                    'drop-in': 'Drop-in',
                                    referral: 'Referral',
                                    appointment: 'Appointment',
                                    membership: 'Membership',
                                }"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.attending_access"
                                class="mt-2"
                            />
                        </div>

                        <!-- Wait time -->
                        <div
                            v-if="
                                ['referral', 'appointment'].includes(
                                    form.attending_access
                                )
                            "
                            class="col-span-6 sm:col-span-4"
                        >
                            <JetLabel for="wait_time" value="Wait time" />
                            <JetInput
                                id="wait_time"
                                type="text"
                                v-model="form.wait_time"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.wait_time"
                                class="mt-2"
                            />
                        </div>

                        <!-- Referral process -->
                        <div
                            v-if="form.attending_access == 'referral'"
                            class="col-span-6 sm:col-span-4"
                        >
                            <JetLabel
                                for="referral_process"
                                value="Referral process"
                            />
                            <JetTextarea
                                id="referral_process"
                                type="text"
                                v-model="form.referral_process"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.referral_process"
                                class="mt-2"
                            />
                        </div>

                        <!-- Cost options -->
                        <div
                            class="col-span-6 flex flex-row gap-4 sm:col-span-4"
                        >
                            <JetCheckbox
                                id="free"
                                name="free"
                                :checked="form.free"
                                v-model="form.free"
                            />
                            <JetLabel for="free" value="This service is free" />
                        </div>

                        <div v-if="!form.free" class="col-span-6 sm:col-span-4">
                            <JetLabel value="Cost options" />
                            <JetCostOptions v-model="form.cost_options" />
                        </div>

                        <!-- Eligibility options -->
                        <div
                            class="col-span-6 flex flex-row gap-4 sm:col-span-4"
                        >
                            <JetCheckbox
                                id="open_to_all"
                                name="open_to_all"
                                :checked="form.open_to_all"
                                v-model="form.open_to_all"
                            />
                            <JetLabel
                                for="open_to_all"
                                value="This service is open to everyone"
                            />
                        </div>

                        <div
                            v-if="!form.open_to_all"
                            class="col-span-6 sm:col-span-4"
                        >
                            <JetLabel value="Eligibility requirements" />
                            <JetEligibilities
                                v-model="form.eligibilities"
                                :options="{
                                    null: 'Anyone',
                                    12: 'Leaving care',
                                    15: 'Homeless',
                                    18: 'In danger',
                                }"
                            />
                        </div>
                    </template>
                </JetFormSection>
                <JetSectionBorder />

                <JetFormSection>
                    <template #title>Funding</template>

                    <template #description>
                        You can provide details of any funders this service
                        receives funding from.
                    </template>

                    <template #form>
                        <!-- Funders -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel value="Funders" />
                            <JetFunders v-model="form.fundings" />
                        </div>
                    </template>
                </JetFormSection>
                <JetSectionBorder />

                <JetFormSection>
                    <template #title>Accreditations</template>

                    <template #description>
                        You can provide details of any accreditations this
                        service has received here. This could include any
                        professional standards which it has met or any
                        professional reviews it has received.
                    </template>

                    <template #form>
                        <!-- Accreditations -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel value="Accreditations" />
                            <JetAccreditations v-model="form.reviews" />
                        </div>
                    </template>
                </JetFormSection>
                <JetSectionBorder />
            </div>
        </div>
    </DashboardLayout>
</template>
