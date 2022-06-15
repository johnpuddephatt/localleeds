<template>
    <div>
        <div class="col-span-6 mt-1 flex flex-row gap-4 sm:col-span-4">
            <input
                id="all_postcodes"
                type="checkbox"
                v-model="allPostcodes"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            />
            <label
                for="all_postcodes"
                class="block text-sm font-medium text-blue-300"
            >
                This services covers all Leeds postcodes</label
            >
        </div>
        <div v-show="!allPostcodes" class="mt-1 rounded border shadow-sm">
            <div class="relative flex flex-col xl:h-[40vh] xl:flex-row">
                <div class="h-[40vh] w-full xl:w-[40vh]">
                    <l-map
                        v-model="zoom"
                        v-model:zoom="zoom"
                        :maxZoom="12"
                        :minZoom="10"
                        :center="[53.8351134, -1.4990645]"
                    >
                        <l-tile-layer
                            url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                            attribution="foo"
                        ></l-tile-layer>
                        <l-geo-json
                            v-if="geojson"
                            @click="toggleLayer"
                            :options="options"
                            :geojson="geojson"
                        ></l-geo-json>
                    </l-map>
                </div>
                <div
                    class="h-64 w-full divide-y overflow-y-auto border-l xl:h-auto xl:flex-1"
                >
                    <button
                        class="space-between flex w-full flex-row gap-4 p-2 text-sm"
                        @click="togglePostcode(postcode)"
                        :class="{
                            'bg-gray-100': [...modelValue].find(
                                (entry) => entry.service_area == postcode
                            ),
                        }"
                        v-for="postcode in postcodes"
                    >
                        {{ postcode }}
                        <CheckIcon
                            :class="{
                                'text-green-500': [...modelValue].find(
                                    (entry) => entry.service_area == postcode
                                ),
                            }"
                            class="h-5 w-5 text-white"
                            aria-hidden="true"
                        />
                    </button>
                </div>
            </div>
            <div
                class="flex flex-row border-t bg-gray-50 text-sm text-gray-500"
            >
                <div class="w-full py-2 text-center xl:w-[40vh]">
                    {{ modelValue.length }} postcodes selected.
                </div>
                <button
                    class="flex-grow border-l border-gray-200 bg-blue-300 p-2 text-center text-sm font-semibold text-white"
                    @click="
                        modelValue.length
                            ? deselectAllPostcodes()
                            : selectAllPostcodes()
                    "
                >
                    {{
                        modelValue.length
                            ? "Remove all postcodes"
                            : "Select all postcodes"
                    }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>
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
import { CheckIcon } from "@heroicons/vue/solid";

export default {
    components: {
        CheckIcon,
        LMap,
        LGeoJson,
        LIcon,
        LTileLayer,
        LMarker,
        LControlLayers,
        LTooltip,
        LPopup,
        LPolyline,
        LPolygon,
        LRectangle,
    },
    props: ["modelValue", "newService"],
    emits: ["update:modelValue"],
    data() {
        return {
            allPostcodes: false,
            geojson: null,
            zoom: 10,
            postcodes: [
                "LS1",
                "LS2",
                "LS3",
                "LS4",
                "LS5",
                "LS6",
                "LS7",
                "LS8",
                "LS9",
                "LS10",
                "LS11",
                "LS12",
                "LS13",
                "LS14",
                "LS15",
                "LS16",
                "LS17",
                "LS18",
                "LS19",
                "LS20",
                "LS21",
                "LS22",
                "LS23",
                "LS24",
                "LS25",
                "LS26",
                "LS27",
                "LS28",
                "LS29",
            ],
            options: {
                style: (feature) => {
                    return {
                        weight: 2,
                        color: this.geoJsonColor(feature),
                    };
                },
                onEachFeature: (feature, layer) => {
                    // does this feature have a property named popupContent?
                    // if (feature.properties.name) {
                    //     layer.bindPopup("Name is " + feature.properties.name);
                    // }
                },
            },
        };
    },
    computed: {},
    mounted() {
        fetch("/js/LS.geojson")
            .then((response) => response.json())
            .then((data) => (this.geojson = data))
            .then((data) => {
                if (this.newService) {
                    this.allPostcodes = true;
                } else if (
                    this.modelValue.length == this.geojson.features.length
                ) {
                    this.allPostcodes = true;
                }
            });
    },
    methods: {
        geoJsonColor(feature) {
            return feature.properties &&
                [...this.modelValue].find(
                    (entry) => entry.service_area == feature.properties.name
                )
                ? "#5ec269"
                : "#AAAAAA";
        },
        toggleLayer(e) {
            this.togglePostcode(e.layer.feature.properties.name);
        },
        selectAllPostcodes() {
            if (!this.allPostcodes) {
                this.allPostcodes = true;
            }
            this.$emit(
                "update:modelValue",
                this.geojson.features.map((postcode) => ({
                    service_area: postcode.properties.name,
                    extent: postcode,
                }))
            );
        },
        deselectAllPostcodes() {
            this.$emit("update:modelValue", []);
        },
        togglePostcode(postcode) {
            if ([...this.modelValue].includes(postcode)) {
                this.$emit(
                    "update:modelValue",
                    [...this.modelValue].filter((value) => {
                        return value.service_area !== postcode;
                    })
                );
            } else {
                let selected = this.geojson.features.find(
                    (elem) => elem.properties.name == postcode
                );

                this.$emit("update:modelValue", [
                    ...this.modelValue,
                    {
                        service_area: selected.properties.name,
                        extent: selected,
                    },
                ]);
            }
        },
    },
    watch: {
        allPostcodes(newValue) {
            if (newValue) {
                this.selectAllPostcodes();
            } else {
                this.deselectAllPostcodes();
            }
        },

        modelValue() {
            let oldGeoJson = this.geojson;
            this.geojson = null;
            this.$nextTick(() => {
                this.geojson = oldGeoJson;
            });
        },
    },
};
</script>

<style>
.leaflet-interactive {
    stroke-width: 1;
}

.leaflet-control-attribution.leaflet-control {
    display: none;
}

.leaflet-pane {
    z-index: 10;
}

.leaflet-top,
.leaflet-bottom {
    z-index: 20;
}
</style>
