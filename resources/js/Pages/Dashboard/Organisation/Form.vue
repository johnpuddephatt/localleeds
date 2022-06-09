<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";

import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetTextarea from "@/Jetstream/Textarea.vue";
import JetLogo from "@/Jetstream/Logo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";

const props = defineProps({
    organisation: Object,
});

const form = useForm({
    _method: "POST",
    name: props.organisation?.name ?? null,
    logo: props.organisation?.logo ?? null,
    email: props.organisation?.email ?? null,
    url: props.organisation?.url ?? null,
    description: props.organisation?.description ?? null,
});

const updateOrganisation = () => {
    form.post(
        props.organisation
            ? route("dashboard.organisation.update", {
                  organisation: props.organisation.id,
              })
            : route("dashboard.organisation.store"),
        {
            errorBag: "updateOrganisation",
        }
    );
};
</script>

<template>
    <DashboardLayout
        :title="
            organisation ? 'Edit organisation' : 'Create a new organisation'
        "
    >
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ organisation ? "Edit organisation" : "Create organisation" }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <JetFormSection @submitted="updateOrganisation">
                    <template #title>About the organisation</template>

                    <template #description>
                        A summary of the organisation’s details will be shown
                        alongside every service, and each organisation will have
                        its own page listing all the services it delivers.
                    </template>

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

                        <!-- Logo -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="logo" value="Logo" />
                            <JetLogo
                                id="logo"
                                v-model="form.logo"
                                type="logo"
                                class="mt-1 block w-full"
                            />
                            <JetInputError
                                :message="form.errors.logo"
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
                    </template>
                    <template #actions>
                        <JetButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            {{
                                organisation
                                    ? "Update organisation"
                                    : "Create organisation"
                            }}
                        </JetButton>
                    </template>
                </JetFormSection>
            </div>
        </div>
    </DashboardLayout>
</template>
