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
            },
            {
                path: 'front/property/:id/reservation/step_1',
                name: 'reservationStep1',
                component: () => import('./pages/properties/reservation/Step1.vue')
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router