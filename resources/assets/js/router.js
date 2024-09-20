import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/products',
        name: 'products-list',
        component: () => import('./views/ProductsList.vue')
    },
]

const router = createRouter({
    history: createWebHistory('/customer-checkout/'),
    routes,
})

export default router
