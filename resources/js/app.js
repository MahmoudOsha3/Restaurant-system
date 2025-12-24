require('./bootstrap');

import { createApp } from 'vue'
import App from './App.vue'
import router from './router';
import './assets/css/dashboard.css';


createApp(App).use(router).mount('#app')



