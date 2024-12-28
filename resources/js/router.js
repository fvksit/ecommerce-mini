import { createRouter, createWebHistory } from 'vue-router';
import AdminLogin from './components/admin/auth/AdminLogin.vue';
import AdminRegister from './components/admin/auth/AdminRegister.vue';
import Dashboard from './components/admin/Dashboard.vue';
import CategoryList from './components/admin/category/CategoryList.vue';
import ProductList from './components/admin/product/ProductList.vue';
import OrderList from './components/admin/order/OrderList.vue';
import NotFound from './components/NotFound.vue';

const routes = [
    { path: '/admin/login', component: AdminLogin, name: 'admin.login' },
    { path: '/admin/register-admin', component: AdminRegister, name: 'admin.register' },
    { path: '/admin/dashboard', component: Dashboard, name: 'admin.dashboard', meta: { requiresAuth: true } },
    { path: '/admin/categories', component: CategoryList, name: 'admin.categories', meta: { requiresAuth: true } },
    { path: '/admin/products', component: ProductList, name: 'admin.products', meta: { requiresAuth: true } },
    { path: '/admin/orders', component: OrderList, name: 'admin.orders', meta: { requiresAuth: true } },
    { path: '/:pathMatch(.*)*', component: NotFound, name: 'not-found' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isLoggedIn = !!localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth) && !isLoggedIn) {
        next({ name: 'admin.login' });
    } else {
        next();
    }
});

export default router;