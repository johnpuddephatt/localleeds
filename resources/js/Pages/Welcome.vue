<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineProps({
    homeCategories: Array,
    homeFeaturedCategories: Array,
});

const form = useForm({
    postcode: null,
    service_category: "",
    service_user: "",
});

const submitForm = () => {
    form.transform((data) => ({
        ...Object.fromEntries(Object.entries(data).filter(([_, v]) => v)),
    })).get(route("service.index"));
};
</script>

<template>
    <Head>
        <title>Find local, community-based support</title>
    </Head>
    <FrontendLayout title="Welcome">
        <div
            class="container relative flex min-h-screen flex-col justify-center lg:max-w-4xl"
        >
            <svg-vue
                class="pointer-events-none absolute right-[100%] top-8 hidden w-64 md:block"
                icon="hero-top-left"
            ></svg-vue>

            <img
                class="absolute top-16 left-[100%] hidden w-64 rounded-full md:block"
                src="/images/hero-upper-right.jpg"
            />
            <img
                class="absolute right-[100%] bottom-24 hidden w-48 rounded-full md:block"
                src="/images/hero-lower-left.jpg"
            />

            <svg-vue
                class="pointer-events-none absolute left-[70%] bottom-8 hidden w-[35rem] md:block"
                icon="hero-bottom-right"
            ></svg-vue>

            <h1
                class="mb-12 text-center text-4xl font-semibold leading-tight md:text-5xl lg:text-6xl"
            >
                Find local, community&#8209;based support in Leeds
            </h1>
            <div class="mb-24 rounded-2xl bg-blue-200 p-12 md:mb-0">
                <h2 class="text-2xl font-semibold">Search for a service</h2>
                <form @submit.prevent="submitForm">
                    <div class="my-8 flex flex-row gap-8">
                        <input
                            class="w-full border-none px-6 py-4 md:w-auto"
                            v-model="form.postcode"
                            type="text"
                            placeholder="Enter a postcode..."
                        />
                        <select
                            class="hidden w-1/3 border-none px-6 py-4 md:inline-block"
                            v-model="form.service_category"
                        >
                            <option value="" selected>All categories</option>
                            <option value="mental_health">Mental health</option>
                            <option value="mental_health">Food banks</option>
                        </select>

                        <select
                            class="hidden w-1/3 border-none px-6 py-4 md:inline-block"
                            v-model="form.service_user"
                        >
                            <option value="" selected>All people</option>
                            <option value="young_people">Young people</option>
                            <option value="older_people">Older people</option>
                        </select>
                    </div>
                    <input
                        class="rounded-2xl bg-blue-300 px-10 py-3 font-bold text-white"
                        value="Search"
                        type="submit"
                    />
                </form>
            </div>
        </div>

        <div class="container mx-auto mb-24 max-w-6xl space-y-12">
            <h2 class="text-4xl font-semibold">Browse by category</h2>

            <div
                class="grid grid-cols-1 gap-36 pt-36 md:grid-cols-3 md:gap-12 md:pt-48"
            >
                <Link
                    :href="
                        route('service.index', {
                            service_category: homeFeaturedCategory.id,
                        })
                    "
                    v-for="homeFeaturedCategory in homeFeaturedCategories"
                    class="rounded-lg bg-blue-200 p-8 text-center"
                >
                    <img
                        :src="homeFeaturedCategory.image"
                        class="mx-auto -mt-48 w-72"
                    />
                    <h3 class="my-4 text-2xl font-semibold text-green-400">
                        {{ homeFeaturedCategory.name }}
                    </h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing
                        elit, sed diam septa nonummy.
                    </p>
                </Link>
            </div>

            <div class="container grid grid-cols-1 gap-12 md:grid-cols-3">
                <div
                    v-for="homeCategory in homeCategories"
                    class="border-t border-blue-200 py-6"
                >
                    <h3
                        class="mb-4 flex flex-row justify-between gap-2 text-2xl font-semibold text-green-400"
                    >
                        {{ homeCategory.name }}

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="top-4 h-6 w-6"
                            width="18.56"
                            height="21.05"
                            viewBox="0 0 18.56 21.05"
                        >
                            <path
                                fill="#d78752"
                                d="M818,1692.53v-9.31a1.22,1.22,0,0,1,1.82-1.06l8.07,4.66,8.06,4.65a1.22,1.22,0,0,1,0,2.11l-8.06,4.66-8.07,4.65a1.21,1.21,0,0,1-1.82-1.05Z"
                                transform="translate(-818 -1682)"
                            />
                        </svg>
                    </h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing
                        elit, sed diam septa nonummy.
                    </p>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped></style>
