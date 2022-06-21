<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import {
    LMap,
    LIcon,
    LTileLayer,
    LMarker,
    LControlZoom,
    LControlLayers,
    LGeoJson,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LRectangle,
} from "@vue-leaflet/vue-leaflet";
import { latLng } from "leaflet";

import "leaflet/dist/leaflet.css";

defineProps({ locations: Array });

const zoom = ref(10);
const map = ref(null);
const center = ref([53.8351134, -1.4990645]);

const currentlySelected = ref(null);

const select = (location) => {
    if (currentlySelected.value == location.id) {
        currentlySelected.value = null;
        map.value.leafletObject.flyTo(
            latLng(center.value[0], center.value[1]),
            10
        );
    } else {
        currentlySelected.value = location.id;
        map.value.leafletObject.flyTo(
            latLng(location.latitude, location.longitude),
            14
        );
    }
};
</script>

<template>
    <div class="space-y-1 rounded-2xl bg-blue-100 p-8 pb-12">
        <h3 class="!mb-4 text-2xl font-semibold">
            {{ locations.length == 1 ? "Location" : "Locations" }}
        </h3>

        <div
            v-for="location in locations"
            class="-mx-2 flex cursor-pointer flex-row rounded-lg border border-transparent py-3 px-2 hover:border-blue-200"
            :class="{ 'bg-blue-200': currentlySelected == location.id }"
            @click="select(location)"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mr-2 inline-block h-10 w-10 rounded-full bg-green-200 p-1 text-white"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5"
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
            <div>
                <div class="font-medium leading-tight">
                    {{ location.name }}
                </div>

                <div
                    class="text-sm leading-tight"
                    v-if="location.physical_address.address_1"
                >
                    {{ location.physical_address.address_1 }}
                </div>
            </div>
        </div>
        <div class="relative !mt-4 overflow-hidden rounded-lg pt-[75%]">
            <l-map
                ref="map"
                v-model="zoom"
                :maxZoom="18"
                :minZoom="10"
                :center="center"
                :options="{ zoomControl: false }"
                class="absolute inset-0"
            >
                <l-tile-layer
                    url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                    attribution=""
                ></l-tile-layer>

                <l-control-zoom position="topright"></l-control-zoom>

                <l-marker
                    v-for="location in locations"
                    @click="currentlySelected = service.id"
                    :lat-lng="[location.latitude, location.longitude]"
                >
                    <l-icon
                        :popupAnchor="[0, 20]"
                        :iconSize="[35, 53]"
                        icon-url="/images/marker-icon.svg"
                    />
                </l-marker>
            </l-map>

            <a
                target="_blank"
                v-if="currentlySelected"
                class="absolute left-4 right-4 bottom-4 z-50 rounded-2xl bg-white py-3 text-center font-semibold shadow"
                :href="`https://www.google.com/maps/dir/?api=1&travelmode=walking&destination=${
                    locations.find(
                        (location) => location.id == currentlySelected
                    ).latitude
                }%2C${
                    locations.find(
                        (location) => location.id == currentlySelected
                    ).longitude
                }`"
                >Get directions</a
            >
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
