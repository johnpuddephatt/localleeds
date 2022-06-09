<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import OrganisationSearch from "@/Components/OrganisationSearch.vue";
</script>

<template>
    <header
        class="container flex flex-row items-center justify-center py-4 print:hidden md:justify-start"
    >
        <Link
            class="text-3xl font-bold tracking-tighter text-blue-300"
            :href="route('home')"
            ><span class="text-green-300">Local</span>Leeds</Link
        >
        <organisation-search />

        <button
            class="hidden md:inline-block"
            id="google_translate_element"
        ></button>

        <div class="ml-8 space-x-2 sm:block">
            <Link
                v-if="$page.props.user"
                :href="route('dashboard')"
                class="rounded-2xl bg-yellow px-6 py-3 font-semibold text-blue-300"
            >
                Dashboard
            </Link>

            <template v-else>
                <!--<Link
                     :href="route('login')"
                     class="hidden rounded-2xl bg-yellow px-6 py-3 font-semibold md:inline-block"
                 >
                     Log in
                 </Link>-->

                <Link
                    :href="route('register')"
                    class="inline-block rounded-2xl bg-yellow px-6 py-3 font-semibold"
                >
                    Add a service
                </Link>
            </template>
        </div>
    </header>
</template>
