<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import OrganisationSearch from "@/Components/OrganisationSearch.vue";
</script>

<template>
    <footer class="relative mt-48 bg-blue-300 py-12 print:hidden md:mt-96">
        <svg-vue
            class="pointer-events-none absolute left-0 bottom-[100%] w-3/4 md:w-1/2"
            icon="footer"
        ></svg-vue>
        <div class="container flex flex-row items-center">
            <Link
                class="text-3xl font-bold tracking-tighter text-green-100"
                :href="route('home')"
                ><span class="text-white">Local</span>Leeds</Link
            >
            <organisation-search :openTop="true" />

            <div class="ml-8 space-x-2 sm:block">
                <Link
                    v-if="$page.props.user"
                    :href="route('dashboard')"
                    class="rounded-2xl bg-blue-200 px-6 py-3 font-semibold"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <!--<Link
                        :href="route('login')"
                        class="rounded-2xl bg-blue-200 px-6 py-3 font-semibold"
                    >
                        Log in
                    </Link>-->

                    <Link
                        :href="route('register')"
                        class="rounded-2xl bg-blue-200 px-6 py-3 font-semibold"
                    >
                        Add a service
                    </Link>
                </template>
            </div>
        </div>
    </footer>
</template>
