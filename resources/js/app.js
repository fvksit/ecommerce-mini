// import { createApp } from 'vue';
// import App from './App.vue';
// import { createRouter, createWebHistory } from 'vue-router';
// import '../css/app.css';
// import axios from 'axios';

// import Login from './components/auth/Login.vue';
// import Register from './components/auth/Register.vue';
// import CategoryTable from './components/category/CategoryTable.vue';

// axios.defaults.baseURL = 'http://localhost:8000';
// axios.defaults.withCredentials = true;
// const routes = [
//     { path: '/admin/login', component: Login },
//     { path: '/admin/register', component: Register },
//     { path: '/admin/categories', component: CategoryTable },
// ];

// const router = createRouter({
//     history: createWebHistory(),
//     routes
// });

// const app = createApp(App);
// app.config.globalProperties.$axios = axios;
// app.use(router);
// app.mount('#app');

import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import '@fortawesome/fontawesome-free/css/all.css';

const app = createApp(App);
app.use(router);
app.mount('#app');