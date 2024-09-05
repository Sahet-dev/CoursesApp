import { createApp } from 'vue'
import store from './store/index.js'
import './style.css'
import App from './App.vue'
import './index.css'
import router from "./router/index.js";
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';

createApp(App)
    .use(store)
    .use(router)
    .use(CkeditorPlugin)

    .mount('#app')
