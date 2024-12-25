// import '../css/app.css';

import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import '@fortawesome/fontawesome-free/css/all.css';
import 'vue-select/dist/vue-select.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-bs5';
import 'datatables.net';
import $ from 'jquery';

import { Modal } from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const addUserButton = document.querySelector('[data-bs-toggle="modal"]');
    if (addUserButton) {
        addUserButton.addEventListener('click', () => {
            const modalElement = document.getElementById('addUserModal');
            if (modalElement) {
                const modal = new Modal(modalElement);
                modal.show();
            }
        });
    }
});

const app = createApp(App);
app.use(router);
app.mount('#app');