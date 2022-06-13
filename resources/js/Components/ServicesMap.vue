<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import {
    LMap,
    LIcon,
    LTileLayer,
    LMarker,
    LControlLayers,
    LGeoJson,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LRectangle,
} from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";

defineProps({ services: Array });

const zoom = ref(10);

const currentlyViewed = ref(null);
</script>

<template>
    <div class="relative pt-[120%] md:pt-[75%]">
        <l-map
            v-model="zoom"
            :maxZoom="12"
            :minZoom="10"
            :center="[53.8351134, -1.4990645]"
            class="absolute inset-0"
        >
            <l-tile-layer
                url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                attribution=""
            ></l-tile-layer>

            <l-marker
                v-for="service in services"
                @click="currentlyViewed = service"
                :lat-lng="[service.latitude, service.longitude]"
            >
                <l-icon
                    :popupAnchor="[0, 20]"
                    :iconSize="[35, 53]"
                    icon-url="/images/marker-icon.svg"
                />
            </l-marker>
        </l-map>
        <div
            class="absolute inset-0 z-50 bg-black bg-opacity-25"
            v-if="currentlyViewed"
            @click.self="currentlyViewed = null"
        >
            <div
                class="absolute left-1/2 top-1/2 w-[90%] max-w-sm -translate-x-1/2 -translate-y-1/2 transform overflow-hidden rounded-2xl border bg-blue-100 shadow"
            >
                <div class="p-8">
                    <h3 class="text-xl font-semibold leading-tight">
                        {{ currentlyViewed.name }}
                    </h3>
                    <div class="mt-1 text-lg font-semibold text-green-300">
                        {{ currentlyViewed.organisation }}
                    </div>

                    <div class="mt-6 flex flex-none flex-row gap-4">
                        <div
                            v-if="!currentlyViewed.cost_options_count"
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
                            v-if="currentlyViewed.distance"
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
                            {{ currentlyViewed.distance }} miles away
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-row justify-end gap-2 border-t bg-white py-2 px-4"
                >
                    <button
                        class="rounded-2xl bg-blue-200 px-6 py-2 font-semibold"
                        @click.prevent="currentlyViewed = null"
                    >
                        Close
                    </button>
                    <Link
                        :href="
                            route('service.show', { id: currentlyViewed.id })
                        "
                        @click.prevent=""
                        class="rounded-2xl bg-yellow px-6 py-2 font-semibold"
                    >
                        View
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.leaflet-pane {
    z-index: 30;
}

.leaflet-top,
.leaflet-bottom {
    z-index: 40;
}

.leaflet-control-attribution.leaflet-control {
    display: none;
}
</style>
