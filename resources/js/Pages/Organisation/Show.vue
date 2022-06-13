<script setup>
import Pagination from "@/Components/Pagination";
import { reactive, computed } from "vue";
import { useForm, Head, Link } from "@inertiajs/inertia-vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

let props = defineProps({
    organisation: Object,
    services: Object,
});
</script>

<template>
    <Head>
        <title>{{ organisation.name }}</title>
    </Head>
    <FrontendLayout title="Welcome">
        <div
            class="container relative mt-36 flex min-h-screen flex-col md:flex-row xl:max-w-6xl"
        >
            <svg-vue
                class="pointer-events-none absolute right-[65%] -top-24 w-24"
                icon="organisation-top-left"
            ></svg-vue>
            <div class="md:w-3/5 md:border-r md:pr-12">
                <h1 class="mb-4 text-5xl font-semibold md:text-6xl">
                    {{ organisation.name }}
                </h1>
                <h2 class="mt-16 text-4xl font-semibold md:mt-24">
                    About this organisation
                </h2>
                <div class="space-y-8 lg:pr-12 2xl:pr-24">
                    <div class="prose prose-lg mt-8 mb-16 md:mb-24">
                        {{ organisation.description }}
                    </div>
                    <div
                        v-if="services.data.length"
                        class="relative space-y-6 border-t pt-12"
                        id="services"
                    >
                        <svg-vue
                            class="pointer-events-none right-0 top-12 ml-auto w-1/2 transform md:absolute md:mr-4 md:w-1/3 md:translate-x-1/2"
                            icon="organisation-services"
                        ></svg-vue>

                        <h2 class="!mb-16 text-4xl font-semibold">
                            Services delivered by this organisation
                        </h2>

                        <Link
                            :href="route('service.show', { id: service.id })"
                            v-for="service in services.data"
                            class="flex flex-col justify-between gap-4 rounded-lg bg-blue-100 p-8 md:flex-row"
                        >
                            <div>
                                <h3 class="text-xl font-semibold leading-tight">
                                    {{ service.name }}
                                </h3>
                            </div>
                            <div
                                class="ml-auto flex flex-none flex-row gap-2 md:gap-4"
                            >
                                <div
                                    v-if="!service.cost_options_count"
                                    class="font-semibold text-green-300"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="ml-1 -mt-1 inline-block h-6 w-6 text-green-200 opacity-80"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    Free
                                </div>
                                <div
                                    v-if="service.distance"
                                    class="font-semibold text-green-300"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="ml-1 -mt-1 inline-block h-6 w-6 text-green-200 opacity-80"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    {{ service.distance }} miles away
                                </div>
                            </div>
                        </Link>
                        <pagination :links="services.links" />
                    </div>
                </div>
            </div>

            <div class="space-y-6 pb-48 md:w-2/5 md:pl-12">
                <svg-vue
                    class="pointer-events-none relative z-10 mr-4 ml-auto -mb-[0.35rem] -mt-2 w-2/3 md:mt-0"
                    icon="organisation-top-right"
                ></svg-vue>
                <div
                    class="!mt-0 rounded-2xl bg-blue-100 p-8 pb-12"
                    v-if="organisation.url || organisation.email"
                >
                    <h3 class="mb-8 text-2xl font-semibold">
                        Contact this organisation
                    </h3>
                    <div class="space-y-6">
                        <div class="truncate">
                            <a
                                aria-label="Contact email adddress"
                                v-if="organisation.email"
                                :href="`mailto:${organisation.email}`"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-3 inline-block h-10 w-10 rounded-full bg-green-200 p-1.5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                    /></svg
                                ><span class="underline decoration-blue-200">{{
                                    organisation.email
                                }}</span></a
                            >
                        </div>
                        <div>
                            <a
                                v-if="organisation.url"
                                :href="organisation.url"
                                aria-label="Website address"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-3 inline-block h-10 w-10 rounded-full bg-green-200 p-1.5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                    /></svg
                                ><span class="underline decoration-blue-200">{{
                                    organisation.url
                                        .replace("https://", "")
                                        .replace("http://", "")
                                }}</span></a
                            >
                        </div>
                    </div>
                </div>
            </div>
            <svg-vue
                class="pointer-events-none absolute bottom-0 right-0 w-24"
                icon="organisation-bottom-right"
            ></svg-vue>
        </div>
    </FrontendLayout>
</template>

<style scoped></style>
