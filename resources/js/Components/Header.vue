<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import OrganisationSearch from "@/Components/OrganisationSearch.vue";
import ApplicationMark from "@/Jetstream/ApplicationMark";
</script>

<template>
    <header
        class="container flex flex-row items-center justify-center py-4 print:hidden md:justify-start"
    >
        <Link
            class="text-2xl font-bold tracking-tighter text-blue-300"
            :href="route('home')"
        >
            <ApplicationMark class="mr-1 inline-block h-10 w-10" />
            <span class="text-green-300">Local</span>Leeds</Link
        >
        <organisation-search />

        <button
            class="hidden md:inline-block"
            id="google_translate_element"
        ></button>

        <div class="ml-8 space-x-2 sm:block">
            <!-- <Link
                v-if="$page.props.user && route().current('service.show')"
                :href="
                    route('dashboard.service.edit', {
                        service: $page.props.service.id,
                    })
                "
                class="rounded-2xl bg-yellow bg-opacity-50 px-6 py-3 font-semibold text-blue-300"
            >
                Edit this service
            </Link>

            <Link
                v-if="$page.props.user && route().current('organisation.show')"
                :href="
                    route('dashboard.organisation.edit', {
                        service: $page.props.organisation.id,
                    })
                "
                class="rounded-2xl bg-yellow bg-opacity-50 px-6 py-3 font-semibold text-blue-300"
            >
                Edit this organisation
            </Link> -->
            <Link
                v-if="$page.props.user"
                :href="route('dashboard')"
                class="rounded-2xl bg-yellow px-6 py-3 font-semibold text-blue-300"
            >
                Dashboard
            </Link>

            <Link
                v-else
                :href="route('register')"
                class="inline-block rounded-2xl bg-yellow px-6 py-3 font-semibold"
            >
                Add a service
            </Link>
        </div>
    </header>
</template>
