<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import InertiaButton from "@/Jetstream/InertiaButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    organisations: Array,
});
</script>

<template>
    <DashboardLayout title="Organisations">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Organisations
            </h2>

            <inertia-button
                :href="route('dashboard.organisation.create')"
                class="-my-2 ml-auto"
                >Add a new organisation</inertia-button
            >
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="!organisations.length"
                    class="flex h-96 items-center justify-center overflow-hidden bg-white shadow-xl sm:rounded-lg"
                >
                    <p class="text-gray-400">No organisations to show you.</p>
                </div>
                <table
                    v-else
                    class="table-auto divide-y divide-gray-200 overflow-hidden rounded border shadow dark:divide-gray-700"
                >
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4">
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
                            </th>
                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Organisation name
                            </th>
                            <!--<th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Category
                            </th>
                            <th
                                scope="col"
                                class="py-3 px-6 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-400"
                            >
                                Admins
                            </th>-->
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
                            v-for="organisation in organisations"
                            class="hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="w-4 p-4">
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
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ organisation.name }}
                            </td>
                            <!--<td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-500 dark:text-white"
                            >
                                A category
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-6 text-sm font-medium text-gray-900 dark:text-white"
                            >
                                <span
                                    class="rounded-full bg-gray-200 px-3 py-1 text-xs"
                                    >X admins</span
                                >
                            </td>-->
                            <td
                                class="whitespace-nowrap py-4 px-1 text-right text-sm font-medium"
                            >
                                <a
                                    target="_blank"
                                    :href="
                                        route('organisation.show', {
                                            id: organisation.id,
                                        })
                                    "
                                    class="rounded-2xl bg-blue-100 px-4 py-3 hover:bg-blue-200"
                                    >View</a
                                >
                            </td>
                            <td
                                class="whitespace-nowrap py-4 px-1 pr-6 text-right text-sm font-medium"
                            >
                                <Link
                                    :href="
                                        route('dashboard.organisation.edit', {
                                            id: organisation.id,
                                        })
                                    "
                                    class="rounded-2xl bg-blue-100 px-4 py-3 hover:bg-blue-200"
                                    >Edit</Link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </DashboardLayout>
</template>
