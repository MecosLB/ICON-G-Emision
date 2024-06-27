<script setup>
import { onMounted, ref } from 'vue';

import { RouterView } from 'vue-router';

import Header from '@/modules/dashboard/components/Header.vue';

import Footer from '@/modules/components/Footer.vue';

import LoaderModal from '@/modules/components/LoaderModal.vue';

import '@/modules/dashboard/assets/css/dashboard.css';

onMounted(() => {
   document.title = 'Icon G | Dashboard';
});
</script>

<template>
    <div id="dashboard-layout" class="container-fluid">
        <Header />

        <main>
            <RouterView />
        </main>

        <Footer />

        <LoaderModal />
    </div>
</template>