<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";

let props = defineProps({
    openTop: Boolean,
});

let fetchStarted = false;

const organisations = ref(null);
const searchTerm = ref(null);
const searchOpen = ref(false);

const fetchOrganisations = () => {
    if (fetchStarted == false) {
        fetchStarted = true;
        fetch("/organisations")
            .then((response) => response.json())
            .then((data) => (organisations.value = data))
            .then(() => (searchOpen.value = true));
    } else {
        searchOpen.value = true;
    }
};

const filteredOrganisations = computed(() => {
    return organisations.value.filter((organisation) => {
        return organisation.name
            .toLowerCase()
            .includes(searchTerm.value.toLowerCase());
    });
});

const closeSearch = (event) => {
    if (!event.relatedTarget || event.relatedTarget.nodeName !== "A") {
        searchOpen.value = false;
    }
};
</script>

<template>
    <div class="relative ml-auto hidden md:block">
        <svg
            class="pointer-events-none absolute top-2.5 left-3 h-6 w-6 opacity-40"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
        </svg>
        <input
            @blur="closeSearch"
            @click.stop="fetchOrganisations"
            v-model="searchTerm"
            class="w-72 rounded-2xl px-6 pl-10 font-medium shadow-inner"
            :class="{ 'border-blue-200': searchOpen }"
            type="text"
            placeholder="Search for an organisation..."
        />

        <div
            v-if="searchOpen && searchTerm && organisations"
            class="absolute left-0 right-0 z-40 max-h-48 divide-y overflow-y-auto rounded-2xl border bg-white shadow"
            :class="{ 'top-[110%]': !openTop, 'bottom-[110%]': openTop }"
        >
            <Link
                class="block truncate p-3 hover:bg-blue-100"
                v-for="organisation in filteredOrganisations"
                :href="
                    route('organisation.show', {
                        organisation: organisation.id,
                    })
                "
            >
                {{ organisation.name }}
            </Link>
        </div>
    </div>
</template>
