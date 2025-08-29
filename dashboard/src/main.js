// src/main.js
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import './assets/main.css';

const app = createApp(App);

const pinia = createPinia();
app.use(pinia);
app.use(router);
app.use(Vue3Toastify, {
  autoClose: 3000,
  position: "top-right",
});

// ** استدعاء تهيئة الثيم مباشرة بعد إنشاء المتجر **
import { useThemeStore } from '@/stores/theme';
const themeStore = useThemeStore(pinia);
themeStore.initializeTheme();



app.mount('#app');

