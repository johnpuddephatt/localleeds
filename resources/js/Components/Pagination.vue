<template>
    <div v-if="links.length > 3">
        <div class="-mb-1 flex flex-row flex-wrap justify-center gap-2">
            <template v-for="(link, key) in links">
                <div
                    v-if="link.url === null"
                    :key="`link_${key}`"
                    class="rounded-xl border px-3 py-2 text-gray-400 md:px-6 md:py-3"
                    v-html="link.label"
                />
                <inertia-link
                    v-else
                    :key="key"
                    class="rounded-xl border px-3 py-2 hover:bg-blue-100 md:px-6 md:py-3"
                    :class="{
                        'bg-blue-300 text-white hover:bg-blue-300': link.active,
                    }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

<script>
import { InertiaLink } from "@inertiajs/inertia-vue3";

export default {
    props: {
        links: Array,
    },
    components: {
        InertiaLink,
    },
};
</script>
