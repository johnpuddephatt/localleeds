<script setup>
import Pagination from "@/Components/Pagination";
import { reactive, computed } from "vue";
import { useForm, Head, Link } from "@inertiajs/inertia-vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import IndexTitle from "@/Components/IndexTitle.vue";
import ServicesMap from "@/Components/ServicesMap.vue";

let props = defineProps({
    view: String,
    services: Object,
    filters: Object,
    service_categories: Array,
});

const form = useForm({
    postcode: props.filters.postcode?.toUpperCase() ?? null,
    service_category: props.filters.service_category ?? "",
    service_user: "",
    distance: props.filters.distance ?? 3,
    free: props.filters.free ?? false,
    iframe: props.filters.iframe ? true : false,
});

const submitForm = () => {
    form.transform((data) => cleanupParameters(data)).get(
        route("service.index", { view: props.view }),
        { preserveScroll: true }
    );
};

const cleanupParameters = (parameters) => {
    return Object.fromEntries(
        Object.entries(parameters)
            .filter(([_, v]) => v)
            .filter(([_, v]) => !(_ == "distance" && form.postcode == null))
            .filter(([_, v]) => !(_ == "page"))
    );
};

const totalServices = computed(() => {
    return props.view == "map" ? props.services.length : props.services.total;
});
</script>

<template>
    <Head>
        <title>Search for a community-based service</title>
    </Head>

    <FrontendLayout :iframe="form.iframe">
        <svg-vue
            v-if="!form.iframe"
            class="pointer-events-none absolute right-0 top-[6.5rem] w-48 md:w-72"
            icon="searchindex_hero-upper-right"
        ></svg-vue>
        <svg-vue
            v-if="!form.iframe"
            class="top-4vh pointer-events-none absolute left-0 -z-10 w-16 md:top-[35vh] md:w-60"
            icon="searchindex_hero-lower-left"
        ></svg-vue>

        <img
            v-if="!form.iframe"
            class="absolute top-32 right-[28rem] hidden w-32 rounded-full md:block"
            src="/images/searchindex_hero-upper-right-1.jpg"
        />
        <img
            v-if="!form.iframe"
            class="absolute right-72 top-48 hidden w-40 rounded-full md:block"
            src="/images/searchindex_hero-upper-right-2.jpg"
        />

        <div
            class="container relative min-h-screen space-y-8 xl:max-w-4xl"
            :class="{ 'my-16 pt-56': !form.iframe, 'py-12': form.iframe }"
        >
            <IndexTitle
                v-if="!form.iframe"
                :filters="filters"
                :serviceCategories="service_categories"
            />

            <form @submit.prevent="submitForm" class="relative space-y-8">
                <div v-if="!form.iframe" class="rounded-2xl bg-blue-200 p-12">
                    <div
                        class="flex flex-row flex-wrap items-center gap-4 md:flex-nowrap md:gap-6"
                    >
                        <p class="font-semibold">Show services within</p>
                        <select
                            class="w-full border-none px-6 py-4 md:w-1/6"
                            v-model="form.distance"
                            aria-label="Maximum distance from postcode"
                        >
                            <option value="1">1 mile</option>
                            <option value="2">2 miles</option>
                            <option value="3">3 miles</option>
                            <option value="4">4 miles</option>
                            <option value="5">5 miles</option>
                            <option value="10">10 miles</option>
                        </select>
                        <p class="font-semibold">of</p>
                        <input
                            required
                            aria-label="Postcode"
                            class="flex-grow border-none px-6 py-4 md:w-1/4"
                            v-model="form.postcode"
                            type="text"
                            placeholder="Enter a postcode..."
                        />
                        <input
                            class="rounded-2xl bg-blue-300 px-10 py-3 font-bold text-white"
                            value="Search"
                            type="submit"
                        />
                    </div>
                </div>
                <div
                    v-if="!form.iframe"
                    class="flex flex-col gap-4 rounded-2xl bg-blue-200 p-12 md:flex-row md:gap-8"
                >
                    <select
                        class="border-none px-6 py-4 md:w-1/4"
                        v-model="form.service_category"
                        @change="submitForm"
                    >
                        <option value="">All categories</option>
                        <option
                            v-for="service_category in service_categories"
                            :value="service_category.id"
                        >
                            {{ service_category.label }}
                        </option>
                    </select>

                    <!--<select
                        class="border-none px-6 py-4 md:w-1/4"
                        v-model="form.service_user"
                    >
                        <option value="" selected>All people</option>
                        <option value="young_people">Young people</option>
                        <option value="older_people">Older people</option>
                    </select>-->

                    <label class="py-4"
                        ><input
                            @change.prevent="submitForm"
                            class="mr-1"
                            type="checkbox"
                            v-model="form.free"
                        />
                        Only show free services</label
                    >
                </div>
            </form>

            <div
                class="flex flex-col items-center justify-between gap-4 md:flex-row"
                :class="{ 'pt-16 md:pt-32': !form.iframe }"
                id="results"
            >
                <h2 class="text-2xl font-semibold">
                    Found:
                    <span class="text-green-200"
                        >{{ totalServices }}
                        {{ totalServices == 1 ? "service" : "services" }}</span
                    >
                </h2>

                <nav class="flex flex-row gap-2 text-lg">
                    <Link
                        :href="
                            route(
                                'service.index',
                                cleanupParameters(props.filters)
                            ) + '#results'
                        "
                        class="rounded-2xl px-4 py-2 font-semibold"
                        :class="{ 'bg-blue-100': view == 'list' }"
                    >
                        List view

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="ml-1 -mt-0.5 inline-block h-6 w-6 opacity-60"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"
                            />
                        </svg>
                    </Link>
                    <Link
                        :href="
                            route('service.index', {
                                ...cleanupParameters(props.filters),
                                view: 'map',
                            }) + '#results'
                        "
                        class="rounded-2xl px-4 py-2 font-semibold"
                        :class="{ 'bg-blue-100': view == 'map' }"
                    >
                        Map view

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="ml-1 -mt-1 inline-block h-6 w-6 opacity-60"
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
                    </Link>
                </nav>
            </div>

            <template v-if="view == 'list'">
                <div
                    class="rounded-2xl bg-blue-100 px-8 py-24 text-center text-sm"
                    v-if="!services.data.length"
                >
                    No services found.
                </div>
                <component
                    :is="form.iframe ? 'a' : Link"
                    target="_blank"
                    :href="route('service.show', { id: service.id })"
                    v-for="service in services.data"
                    class="flex flex-col justify-between gap-4 rounded-lg bg-blue-100 p-8 md:flex-row"
                >
                    <div>
                        <h3 class="text-xl font-semibold leading-tight">
                            {{ service.name }}
                        </h3>
                        <div
                            class="mt-1 text-base font-semibold text-green-300"
                        >
                            {{ service.organisation }}
                        </div>
                    </div>
                    <div
                        class="ml-auto flex flex-none flex-row gap-2 md:ml-0 md:gap-4"
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
                            v-if="service.distance && filters.postcode"
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
                </component>
                <pagination :links="services.links" />
            </template>

            <div v-if="view == 'map'">
                <services-map :iframe="form.iframe" :services="services" />
            </div>

            <svg-vue
                v-if="!form.iframe"
                class="pointer-events-none absolute right-4 top-[100%] w-24 md:left-[100%]"
                icon="searchindex-bottom-right"
            ></svg-vue>
        </div>
    </FrontendLayout>
</template>

<style scoped></style>
