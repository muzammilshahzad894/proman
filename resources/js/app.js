import './assets';
import 'swiper/css';
import 'glightbox/dist/css/glightbox.css';
import 'v-calendar/style.css';
import {createApp} from 'vue';
import App from './App.vue';
import utilsPlugin from '@/plugins/utilsPlugin';
import router from './router';
import FontAwesomeIcon from "./fontawesome";
import VCalendar from 'v-calendar';

import PrimeVue from 'primevue/config';
import "primevue/resources/themes/mdc-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

const app = createApp(App)

app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(utilsPlugin);
app.use(VCalendar, {});

app.use(PrimeVue);

app.mount("#app")