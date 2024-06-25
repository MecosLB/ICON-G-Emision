<script setup>
import { onMounted } from 'vue';

import { RouterView } from 'vue-router';

import '@/modules/auth/assets/css/auth.css';

onMounted(() => {
   document.title = 'Icon G | Login' 
});
</script>

<template>
    <div id="auth-layout">
        <RouterView />
    </div>
</template>