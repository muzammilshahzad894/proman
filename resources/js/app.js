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

const app = createApp(App)

app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(utilsPlugin);
app.use(VCalendar, {});

app.mount("#app")