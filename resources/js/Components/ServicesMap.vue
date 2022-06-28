<script setup>
import { ref, computed } from "vue";
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

let props = defineProps({ services: Array, iframe: Boolean });

const zoom = ref(10);

const currentlyViewed = ref(null);

const servicesAtLocation = computed(() => {
    return props.services.filter((service) => {
        return (
            service.latitude == currentlyViewed.value[0] &&
            service.longitude == currentlyViewed.value[1]
        );
    });
});
</script>

<template>
    <div class="relative pt-[120%] md:pt-[75%]">
        <l-map
            v-model="zoom"
            :maxZoom="18"
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
                @click="currentlyViewed = [service.latitude, service.longitude]"
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
                <div
                    class="border-b bg-white px-8 py-4 text-lg font-semibold"
                    v-if="servicesAtLocation.length > 1"
                >
                    {{ servicesAtLocation.length }} services at this location
                </div>
                <div class="max-h-48 divide-y overflow-y-scroll">
                    <div class="p-8" v-for="entry in servicesAtLocation">
                        <h3 class="text-xl font-semibold leading-tight">
                            {{ entry.name }}
                        </h3>
                        <div class="mt-1 text-lg font-semibold text-green-300">
                            {{ entry.organisation }}
                        </div>

                        <div
                            class="mt-6 flex flex-none flex-row items-center gap-4"
                        >
                            <div
                                v-if="!entry.cost_options_count"
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
                                v-if="entry.distance"
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
                                {{ entry.distance }} miles away
                            </div>
                            <component
                                :is="iframe ? 'a' : Link"
                                target="_blank"
                                class="rounded-2xl bg-blue-200 px-6 py-2 font-semibold"
                                :href="
                                    route('service.show', { service: entry.id })
                                "
                            >
                                View
                            </component>
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
