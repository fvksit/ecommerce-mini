// import '../css/app.css';

import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import { DataTable } from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net-bs5';
import '@fortawesome/fontawesome-free/css/all.css';
import 'vue-select/dist/vue-select.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-bs5';
import 'datatables.net';

DataTable.use(DataTablesLib);
const app = createApp(App);
app.component('DataTable', DataTable);
app.use(router);
app.mount('#app');