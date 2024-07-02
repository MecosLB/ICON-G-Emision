import { createRouter, createWebHistory } from 'vue-router';

import AuthRouter from '@/modules/auth/router';

import { dashboardGuard } from '@/modules/dashboard/router/guard';
import DashboardRouter from '@/modules/dashboard/router'
import { authGuard } from '@/modules/auth/router/guard';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/auth',
            beforeEnter: [authGuard],
            ...AuthRouter
        },
        {
            path: '/dashboard',
            beforeEnter: [dashboardGuard],
            ...DashboardRouter
        }
    ]
});

export default router;