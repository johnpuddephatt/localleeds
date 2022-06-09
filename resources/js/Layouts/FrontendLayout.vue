<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Banner from "@/Components/Banner.vue";

defineProps({
    title: String,
});
</script>

<template>
    <Banner />
    <Header />
    <!-- Page Content -->
    <main>
        <slot />
    </main>
    <Footer />
</template>
