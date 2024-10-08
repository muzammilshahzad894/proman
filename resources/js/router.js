import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        component: () => import('./layouts/Layout.vue'),
        children: [
            {
                path: '/front/home',
                name: 'home',
                component: () => import('./pages/Home.vue')
            },
            {
                path: 'front/property/:id',
                name: 'propertyDetail',
                component: () => import('./pages/properties/details/Detail.vue')
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router