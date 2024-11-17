import './bootstrap';

import { createApp } from 'vue';

import Index from './components/Index.vue';

let app = createApp({});

app.component('index', Index);
app.mount('#zartar-vue');
