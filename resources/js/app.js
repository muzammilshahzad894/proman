import './assets';
import {createApp} from 'vue'
import App from './App.vue'
import utilsPlugin from '@/plugins/utilsPlugin';
import router from './router'
import FontAwesomeIcon from "./fontawesome"

const app = createApp(App)

app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(utilsPlugin);

app.mount("#app")