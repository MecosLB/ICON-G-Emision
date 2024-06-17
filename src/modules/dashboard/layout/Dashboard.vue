<script setup>
import { onMounted } from 'vue';

import { RouterView } from 'vue-router';

import Header from '@/modules/dashboard/components/Header.vue';

import Footer from '@/modules/dashboard/components/Footer.vue';

import '@/modules/dashboard/assets/css/dashboard.css';

onMounted(() => {
   document.title = 'Dashboard | ' 
});
</script>

<template>
    <div id="dashboard-layout" class="container-fluid">
        <Header />

        <main>
            <RouterView />
        </main>

        <Footer />
    </div>
</template>