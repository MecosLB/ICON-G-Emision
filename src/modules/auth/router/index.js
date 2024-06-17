export default {
    name: 'auth',
    component: () => import( /* webpackChunckName:'Auth Layout' */ '@/modules/auth/layout/AuthLayout.vue'),
    children: [
        {
            path: '',
            name: 'login',
            component: () => import( /* webpackChunckName:'Login' */ '@/modules/auth/views/Login.vue'),
        },
    ]
};