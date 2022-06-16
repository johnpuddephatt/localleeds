<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import InertiaButton from "@/Jetstream/InertiaButton.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    services: Array,
    organisations: Array,
});

const selectingOrganisation = ref(false);
const confirmingDelete = ref(null);

const selectOrganisation = () => {
    selectingOrganisation.value = true;
};

const closeModal = () => {
    selectingOrganisation.value = false;
};

const deleteService = () => {
    Inertia.delete(
        route("dashboard.service.delete", { service: confirmingDelete.value })
    );
    confirmingDelete.value = false;
};
</script>

<template>
    <DashboardLayout title="Services">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Services
            </h2>

            <JetButton @click.prevent="selectOrganisation" class="-my-2 ml-auto"
                >Add a new service
            </JetButton>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="!services.length"
                    class="flex h-96 items-center justify-center overflow-hidden bg-white shadow-xl sm:rounded-lg"
                >
                    <p class="text-gray-400">No services to show you.</p>
                </div>
                <table
                    v-else
                    class="min-w-full table-fixed divide-y divide-gray-200 overflow-hidden rounded border shadow dark:divide-gray-700"
                >
                    <thead class="bg-slate-200 dark:bg-gray-700">
                        <tr>
                            <!-- <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input
                                        id="checkbox-all"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                    />
                                    <label for="checkbox-all" class="sr-only"
                                        >checkbox</label
                                    >
                                </div>
                            </th>-->
                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Service name
                            </th>
                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Organisation name
                            </th>

                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Type
                            </th>
                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Last verified
                            </th>
                            <th scope="col" class="w-0 p-4">
                                <span class="sr-only">Delete</span>
                            </th>
                            <th scope="col" class="w-0 p-4">
                                <span class="sr-only">View</span>
                            </th>
                            <th scope="col" class="w-0 p-4">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                    >
                        <tr
                            v-for="service in services"
                            class="hover:bg-green-100 dark:hover:bg-gray-700"
                        >
                            <!--<td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input
                                        id="checkbox-table-1"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                    />
                                    <label
                                        for="checkbox-table-1"
                                        class="sr-only"
                                        >checkbox</label
                                    >
                                </div>
                            </td>-->
                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-800 dark:text-white"
                            >
                                {{ service.name }}
                            </td>

                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-800 dark:text-white"
                            >
                                {{ service.organisation.name }}
                            </td>

                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-500 dark:text-white"
                            >
                                <span
                                    v-if="service.deliverable_type"
                                    class="rounded-full bg-gray-200 px-3 py-1 text-xs"
                                    >{{ service.deliverable_type }}</span
                                >
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm text-gray-700 dark:text-white"
                            >
                                {{ service.assured_date }}
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-1 text-right text-sm"
                            >
                                <button
                                    @click="confirmingDelete = service.id"
                                    class="rounded-2xl border-none bg-blue-100 px-4 py-3 font-medium hover:bg-blue-200"
                                >
                                    Delete
                                </button>
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-1 text-right text-sm font-medium"
                            >
                                <a
                                    target="_blank"
                                    :href="
                                        route('service.show', {
                                            id: service.id,
                                        })
                                    "
                                    class="inline-block rounded-2xl bg-blue-100 px-4 py-3 hover:bg-blue-200"
                                    >View</a
                                >
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-1 pr-6 text-right text-sm font-medium"
                            >
                                <Link
                                    :href="
                                        route('dashboard.service.edit', {
                                            id: service.id,
                                        })
                                    "
                                    class="inline-block rounded-2xl bg-blue-100 px-4 py-3 hover:bg-blue-200"
                                    >Edit</Link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <JetDialogModal :show="selectingOrganisation" @close="closeModal">
            <template #title> Select organisation </template>

            <template #content>
                <div v-if="organisations.length">
                    <Link
                        class="mb-2 block rounded border py-4 text-center"
                        v-for="organisation in organisations"
                        :href="
                            route('dashboard.service.create', {
                                organisation: organisation.id,
                            })
                        "
                        >{{ organisation.name }}</Link
                    >
                </div>
                <div
                    v-else
                    class="rounded border bg-gray-50 py-36 text-center text-sm text-gray-500"
                >
                    <p class="mt-8">No organisations have been added yet.</p>

                    <inertia-button
                        class="mt-6"
                        :href="route('dashboard.organisation.create')"
                        >Create a new organisation</inertia-button
                    >
                </div>
                <p
                    class="mt-6 text-sm text-gray-500"
                    v-if="organisations.length"
                >
                    Choose an existing organisation above or
                    <Link
                        class="text-gray-700 underline"
                        :href="route('dashboard.organisation.create')"
                        >create a new one</Link
                    >.
                </p>
            </template>

            <template #footer>
                <JetSecondaryButton @click="closeModal">
                    Cancel
                </JetSecondaryButton>
            </template>
        </JetDialogModal>

        <JetDialogModal
            :show="!!confirmingDelete"
            @close="confirmingDelete = null"
        >
            <template #title
                >Are you sure you want to delete this service?
            </template>

            <template #content> </template>

            <template #footer>
                <JetSecondaryButton @click="confirmingDelete = null">
                    Cancel
                </JetSecondaryButton>

                <JetDangerButton @click="deleteService" class="ml-2">
                    Delete
                </JetDangerButton>
            </template>
        </JetDialogModal>
    </DashboardLayout>
</template>
