<script setup>
import { reactive, computed } from "vue";
import { useForm, Head, Link } from "@inertiajs/inertia-vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import LocationMap from "@/Components/LocationMap.vue";

let props = defineProps({
    service: Object,
});

const print = () => {
    window.print();
    return false;
};
</script>

<template>
    <Head>
        <title>{{ service.name }} ({{ service.organisation.name }})</title>
    </Head>
    <FrontendLayout title="Welcome">
        <div
            class="container relative mt-40 flex min-h-screen flex-col md:flex-row xl:max-w-6xl"
        >
            <svg-vue
                class="pointer-events-none absolute right-[45%] -top-24 w-24"
                icon="service-top-left"
            ></svg-vue>
            <div class="md:w-3/5 md:border-r md:pr-12">
                <h1 class="mb-4 text-5xl font-semibold md:text-6xl">
                    {{ service.name }}
                </h1>
                <p class="text-2xl font-semibold">
                    {{ service.organisation.name }}
                </p>
                <div class="mt-8 flex flex-col items-start gap-2 md:flex-row">
                    <div class="rounded-2xl bg-green-100 p-3 px-6 font-medium">
                        {{ service.deliverable_type }} service
                    </div>
                    <div class="rounded-2xl bg-blue-100 p-3 px-6 font-medium">
                        <span class="capitalize">{{
                            service.attending_access
                        }}</span>
                        access via
                        {{ service.attending_type }}
                    </div>
                </div>

                <div class="space-y-8 lg:pr-12 2xl:pr-24">
                    <div class="prose prose-lg my-16 md:my-24">
                        {{ service.description }}
                    </div>

                    <div
                        class="rounded-2xl bg-blue-200 p-8"
                        v-if="service.referral_process || service.wait_time"
                    >
                        <h3 class="mb-2 text-2xl font-semibold">
                            Accessing this service
                        </h3>

                        <p class="mb-6" v-if="!service.eligibilities.length">
                            This service is open to everyone
                        </p>
                        <div v-else class="mb-4">
                            <h4 class="mt-6 -mb-1.5 font-semibold">
                                Who can access this service?
                            </h4>

                            <div class="divide-y">
                                <div
                                    class="py-2"
                                    v-for="eligibility in service.eligibilities"
                                >
                                    <span v-if="eligibility.minimum_age">
                                        Ages {{ eligibility.minimum_age }}
                                    </span>
                                    <span v-if="eligibility.maximum_age">
                                        {{
                                            eligibility.minimum_age
                                                ? " to "
                                                : "Aged under "
                                        }}
                                        {{ eligibility.maximum_age }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4" v-if="service.referral_process">
                            <h4 class="font-semibold">Referral process</h4>
                            <div class="prose">
                                {{ service.referral_process }}
                            </div>
                        </div>

                        <div class="mt-4" v-if="service.wait_time">
                            <h4 class="font-semibold">Wait time</h4>
                            <div class="prose">{{ service.wait_time }}</div>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl bg-blue-200 p-8"
                        v-if="service.reviews.length"
                    >
                        <h3 class="mb-2 text-2xl font-semibold">
                            Reviews &amp; accreditations
                        </h3>

                        <div class="divide-y divide-gray-100">
                            <component
                                :is="review.url ? 'a' : 'div'"
                                :href="review.url ? review.url : null"
                                :target="review.url ? '_blank' : null"
                                class="py-4"
                                v-for="review in service.reviews"
                            >
                                <div
                                    class="mb-2 inline-block rounded-xl bg-white px-4 py-2 text-lg font-semibold"
                                >
                                    {{ review.score }}
                                </div>
                                <div class="">
                                    <div class="text-lg font-medium">
                                        {{ review.title }}
                                    </div>
                                    <div class="text-sm">
                                        {{ review.description }}
                                    </div>
                                    <div class="mt-2 text-sm italic">
                                        {{
                                            new Date(
                                                review.date
                                            ).toLocaleDateString()
                                        }}
                                    </div>
                                </div>
                            </component>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl bg-blue-200 p-8"
                        v-if="service.fundings.length"
                    >
                        <h3 class="mb-2 text-2xl font-semibold">
                            How this service is funded
                        </h3>

                        <div class="divide-y divide-gray-100">
                            <div
                                class="py-4"
                                v-for="funding in service.fundings"
                            >
                                {{ funding.source }}
                            </div>
                        </div>
                    </div>

                    <div class="font-medium italic">
                        The information on this page was last verified on
                        {{ service.assured_date }}
                    </div>

                    <div
                        class="relative !mt-24 rounded-2xl bg-pink bg-opacity-20 p-8"
                    >
                        <h2 class="text-3xl font-semibold">
                            About this organisation
                        </h2>
                        <div class="prose my-6">
                            {{ service.organisation.description }}
                        </div>
                        <Link
                            class="inline-block rounded-2xl bg-blue-300 px-6 py-3 text-lg text-white"
                            :href="
                                route('organisation.show', {
                                    organisation: service.organisation.id,
                                })
                            "
                            >More about this organisation</Link
                        >

                        <svg-vue
                            class="pointer-events-none absolute left-[95%] top-[95%] hidden w-48 -translate-x-1/2 -translate-y-1/2 transform md:block"
                            icon="service-org-profile"
                        ></svg-vue>
                    </div>
                </div>
            </div>

            <div class="space-y-6 pb-24 md:w-2/5 md:pl-12">
                <svg-vue
                    class="pointer-events-none relative z-10 -mt-8 mr-4 ml-auto -mb-[0.43rem] w-2/3 md:mt-0"
                    icon="service-right-top"
                ></svg-vue>
                <div
                    v-if="!service.cost_options.length"
                    class="!mt-0 rounded-2xl bg-blue-100 p-8 text-xl font-semibold"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="ml-1 -mt-1 mr-1 inline-block h-10 w-10 rounded-full bg-green-200 p-1 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    This is a free service
                </div>

                <div
                    class="!mt-0 rounded-2xl bg-blue-100 p-8 pb-12"
                    v-if="service.cost_options.length"
                >
                    <h3 class="mb-4 text-2xl font-semibold">Cost details</h3>

                    <div class="divide-y">
                        <div v-for="cost_option in service.cost_options">
                            <div
                                class="flex flex-row justify-between gap-4 py-2"
                            >
                                <div class="font-medium">
                                    {{ cost_option.option }}
                                </div>
                                <div v-if="cost_option.amount">
                                    £{{ cost_option.amount }}
                                    {{ cost_option.amount_description }}
                                </div>
                                <div v-else>Free</div>
                            </div>
                        </div>
                    </div>
                </div>
                <location-map
                    v-if="service.locations.length"
                    :locations="service.locations"
                />

                <div
                    class="rounded-2xl bg-blue-100 p-8 pb-12"
                    v-if="
                        service.contacts.length || service.url || service.email
                    "
                >
                    <h3 class="mb-8 text-2xl font-semibold">Contact details</h3>
                    <div class="space-y-6">
                        <div class="truncate">
                            <a
                                aria-label="Contact email adddress"
                                v-if="service.email"
                                :href="`mailto:${service.email}`"
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
                                    service.email
                                }}</span></a
                            >
                        </div>
                        <div>
                            <a
                                v-if="service.url"
                                :href="service.url"
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
                                    service.url
                                        .replace("https://", "")
                                        .replace("http://", "")
                                        .replace("www.", "")
                                }}</span></a
                            >
                        </div>
                        <div
                            v-if="service.contacts.length"
                            class="flex flex-row"
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
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                />
                            </svg>
                            <div class="mt-2 space-y-6">
                                <div v-for="contact in service.contacts">
                                    <div class="mb-2 font-medium leading-tight">
                                        {{ contact.name }}
                                    </div>
                                    <div
                                        class="text-sm leading-tight"
                                        v-if="
                                            contact.title &&
                                            contact.title != contact.name
                                        "
                                    >
                                        {{ contact.title }}
                                    </div>
                                    <div class="mt-2 text-sm">
                                        <a
                                            class="underline decoration-blue-200"
                                            :href="`tel:${contact.phone.number.replace(
                                                ' ',
                                                ''
                                            )}`"
                                            >{{ contact.phone.number }}</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    class="block w-full rounded-2xl bg-green-200 py-4 px-10 text-left text-2xl font-medium text-white print:hidden"
                    @click="print"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mr-4 inline-block h-16 w-16 rounded-full bg-white p-2 text-green-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                        />
                    </svg>
                    Print this page
                </button>
            </div>
            <svg-vue
                class="pointer-events-none absolute right-8 top-[100%] w-24 md:right-auto md:left-[85%]"
                icon="service-bottom-right"
            ></svg-vue>
        </div>
    </FrontendLayout>
</template>

<style scoped></style>
