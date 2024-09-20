
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import router from './router.js'

import App from './App.vue'

import PrimeVue from 'primevue/config';

import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)


createApp(App).use(router).use(pinia).use(PrimeVue).mount("#app")
