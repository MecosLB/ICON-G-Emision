export default {
    name: 'dashboard',
    component: () => import( /* webpackChunckName:'Dashboard Layout' */ '@/modules/dashboard/layout/Dashboard.vue'),
    children: [
        {
            path: '',
            name: 'home',
            component: () => import( /* webpackChunckName:'Costumers' */ '@/modules/dashboard/views/Home.vue'),
        },
        {
            path: 'issue-cfdi',
            name: 'issue-cfdi',
            component: () => import( /* webpackChunckName:'Issue CFDI' */ '@/modules/dashboard/views/IssueCfdi.vue'),
        },
        {
            path: 'costumers',
            name: 'costumers',
            component: () => import( /* webpackChunckName:'Costumers' */ '@/modules/dashboard/views/Costumers.vue'),
        },
    ]
};