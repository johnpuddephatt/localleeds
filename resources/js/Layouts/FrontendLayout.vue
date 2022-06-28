<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Banner from "@/Components/Banner.vue";

defineProps({
    title: String,
    iframe: Boolean,
});
</script>

<template>
    <Banner />
    <div
        v-if="!iframe"
        class="bg-green-300 py-2 text-center text-sm font-semibold text-white"
    >
        <div class="container">
            This site is currently for demonstration purposes only.
        </div>
    </div>
    <Header v-if="!iframe" />
    <!-- Page Content -->
    <main>
        <slot />
    </main>
    <Footer v-if="!iframe" />
</template>
