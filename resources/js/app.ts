import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './views/Nav.vue'
import '../css/app.css'

// Import your Vue components
import Login from './views/Auth/Login.vue'
import PurchaseOrder from './views/PurchaseOrder/PurchaseOrder.vue'
import ProductsList from './views/Products/Products.vue'
import VendorsList from './views/Vendors/VendorList.vue'
// Import store
import useAuthUser from './store/useAuthUser'

// Type declarations
declare global {
    interface Window {
        axios: typeof axios;
    }
}

// Get API URL from environment or use default
const baseURL = (import.meta as any).env?.VITE_API_URL || 'http://localhost:8000/api';

// Set up axios
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = baseURL;

// Define routes
const routes = [
    {
        path: '/',
        component: PurchaseOrder,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/products',
        component: ProductsList,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/vendors',
        component: VendorsList,
        meta: { requiresAuth: true, requiresAdmin: true }
    }
]

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthUser()

    // If user has auth but no user data, fetch user data
    if (authStore.hasAuth && !authStore.user) {
        try {
            await authStore.fetchUser()
        } catch (error) {
            // User is not authenticated
        }
    }

    // Check if route requires authentication
    if (to.meta.requiresAuth && !authStore.hasAuth) {
        next('/login')
        return
    }

    // Check if route requires guest (not authenticated)
    if (to.meta.requiresGuest && authStore.hasAuth) {
        next('/')
        return
    }

    next()
})

const app = createApp(App);
const pinia = createPinia();

app.use(pinia).use(router).mount('#app');
