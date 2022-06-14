<template>
    <div v-if="modelValue">
        <div
            class="h-[300px] w-[250px] overflow-hidden rounded-md border border-gray-300 pb-[50px] shadow-sm"
        >
            <img ref="croppieEl" :src="modelValue" />
        </div>
        <p class="mt-2 text-sm text-gray-500">
            Adjust the slider so that your logo fits within the circle.
            <button class="underline" @click.prevent="reset">
                Start again.
            </button>
        </p>
    </div>

    <div v-else class="relative mt-4 mb-6 pt-4">
        <jet-secondary-button>Select an image</jet-secondary-button>
        <input
            class="absolute inset-0 block cursor-pointer opacity-0"
            id="logo"
            type="file"
            accept="image/png, image/jpeg"
            @change="fileUploaded"
        />
        <p class="mt-4 text-sm text-gray-500">
            Your logo must be an image file – either JPEG or PNG format.
        </p>
    </div>
</template>

<script setup>
import JetButton from "@/Jetstream/Button";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import InputHelp from "@/Jetstream/InputHelp";
import { ref, nextTick, reactive, onMounted, watch } from "vue";
import Croppie from "croppie";

const croppieOptions = {
    type: "base64",
    enforceBoundary: false,
    viewport: { width: 250, height: 250, type: "circle" },
    maxZoom: 3,
    minZoom: 0.2,
    size: {
        width: 250,
        height: 250,
    },
};

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

const croppieEl = ref(null);

watch(
    () => props.modelValue,
    (first, second) => {
        if (first && !second) {
            initialiseCroppie();
        }
    }
);

onMounted(() => {
    if (props.modelValue && props.modelValue.startsWith("http")) {
        readFileAsBlob();
        initialiseCroppie();
    }
});

function reset() {
    emit("update:modelValue", null);
}

function readFileAsBlob() {
    var request = new XMLHttpRequest();
    request.open("GET", props.modelValue, true);
    request.responseType = "blob";
    request.onload = function () {
        croppieEl.value = request.response;
    };
    request.send();
}

async function initialiseCroppie() {
    let isProcessing = false;
    await nextTick();
    let croppie = new Croppie(croppieEl.value, croppieOptions);

    croppieEl.value.parentElement.addEventListener("update", (e) => {
        if (isProcessing == false) {
            let isProcessing = true;
            croppie.result("base64").then(function (output) {
                emit("update:modelValue", output);
                isProcessing = false;
            });
        }
    });
}

function fileUploaded(e) {
    var files = e.target.files || e.dataTransfer.files;
    if (!files.length) return;
    var reader = new FileReader();
    reader.onload = (e) => {
        emit("update:modelValue", e.target.result);
    };
    reader.readAsDataURL(files[0]);
}
</script>

<style>
.croppie-container {
    width: 100%;
    height: 100%;
}

.croppie-container .cr-image {
    z-index: -1;
    position: absolute;
    top: 0;
    left: 0;
    transform-origin: 0 0;
    max-height: none;
    max-width: none;
}

.croppie-container .cr-boundary {
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    z-index: 1;
    width: 100%;
    height: 100%;
}

.croppie-container .cr-viewport,
.croppie-container .cr-resizer {
    position: absolute;
    border: 2px solid #fff;
    margin: auto;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    box-shadow: 0 0 2000px 2000px rgba(0, 0, 0, 0.5);
    z-index: 0;
}

.croppie-container .cr-resizer {
    z-index: 2;
    box-shadow: none;
    pointer-events: none;
}

.croppie-container .cr-resizer-vertical,
.croppie-container .cr-resizer-horisontal {
    position: absolute;
    pointer-events: all;
}

.croppie-container .cr-resizer-vertical::after,
.croppie-container .cr-resizer-horisontal::after {
    display: block;
    position: absolute;
    box-sizing: border-box;
    border: 1px solid black;
    background: #fff;
    width: 10px;
    height: 10px;
    content: "";
}

.croppie-container .cr-resizer-vertical {
    bottom: -5px;
    cursor: row-resize;
    width: 100%;
    height: 10px;
}

.croppie-container .cr-resizer-vertical::after {
    left: 50%;
    margin-left: -5px;
}

.croppie-container .cr-resizer-horisontal {
    right: -5px;
    cursor: col-resize;
    width: 10px;
    height: 100%;
}

.croppie-container .cr-resizer-horisontal::after {
    top: 50%;
    margin-top: -5px;
}

.croppie-container .cr-original-image {
    display: none;
}

.croppie-container .cr-vp-circle {
    border-radius: 50%;
}

.croppie-container .cr-overlay {
    z-index: 1;
    position: absolute;
    cursor: move;
    touch-action: none;
}

.croppie-container .cr-slider-wrap {
    width: 75%;
    margin: 15px auto;
    text-align: center;
}

.croppie-result {
    position: relative;
    overflow: hidden;
}

.croppie-result img {
    position: absolute;
}

.croppie-container .cr-image,
.croppie-container .cr-overlay,
.croppie-container .cr-viewport {
    -webkit-transform: translateZ(0);
    -moz-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
}

/*************************************/
/***** STYLING RANGE INPUT ***********/
/*************************************/
/*http://brennaobrien.com/blog/2014/05/style-input-type-range-in-every-browser.html */
/*************************************/

.cr-slider {
    -webkit-appearance: none;
    /*removes default webkit styles*/
    /*border: 1px solid white; */ /*fix for FF unable to apply focus style bug */
    width: 300px;
    /*required for proper track sizing in FF*/
    max-width: 100%;
    padding-top: 8px;
    padding-bottom: 8px;
    background-color: transparent;
}

.cr-slider::-webkit-slider-runnable-track {
    width: 100%;
    height: 3px;
    background: rgba(0, 0, 0, 0.5);
    border: 0;
    border-radius: 3px;
}

.cr-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    border: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #ddd;
    margin-top: -6px;
}

.cr-slider:focus {
    outline: none;
}
/*
.cr-slider:focus::-webkit-slider-runnable-track {
background: #ccc;
}
*/

.cr-slider::-moz-range-track {
    width: 100%;
    height: 3px;
    background: rgba(0, 0, 0, 0.5);
    border: 0;
    border-radius: 3px;
}

.cr-slider::-moz-range-thumb {
    border: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #ddd;
    margin-top: -6px;
}

/*hide the outline behind the border*/
.cr-slider:-moz-focusring {
    outline: 1px solid white;
    outline-offset: -1px;
}

.cr-slider::-ms-track {
    width: 100%;
    height: 5px;
    background: transparent;
    /*remove bg colour from the track, we'll use ms-fill-lower and ms-fill-upper instead */
    border-color: transparent; /*leave room for the larger thumb to overflow with a transparent border */
    border-width: 6px 0;
    color: transparent; /*remove default tick marks*/
}
.cr-slider::-ms-fill-lower {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
}
.cr-slider::-ms-fill-upper {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
}
.cr-slider::-ms-thumb {
    border: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #ddd;
    margin-top: 1px;
}
.cr-slider:focus::-ms-fill-lower {
    background: rgba(0, 0, 0, 0.5);
}
.cr-slider:focus::-ms-fill-upper {
    background: rgba(0, 0, 0, 0.5);
}
/*******************************************/
</style>
