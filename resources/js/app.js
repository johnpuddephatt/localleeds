require("./bootstrap");

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import VueGoogleMaps from "@fawmi/vue-google-maps";
import SvgVue from "svg-vue3";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "Local Leeds";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(SvgVue)
            .use(VueGoogleMaps, {
                load: {
                    key: window.google_maps_key,
                    libraries: "places",
                },
            })
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#6fbda6" });
