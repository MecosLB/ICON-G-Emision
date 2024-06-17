import { createRouter, createWebHistory } from 'vue-router';

import AuthRouter from '@/modules/auth/router';
import DashboardRouter from '@/modules/dashboard/router'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/auth',
            ...AuthRouter
        },
        {
            path: '/dashboard',
            ...DashboardRouter
        }
    ]
});

export default router;