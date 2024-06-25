<script setup>
import { onMounted, ref } from 'vue';

import LoaderView from '@/modules/components/LoaderView.vue';

const rfcEmisor = ref('XAXX010101000');

onMounted(async () => {
    await loadView();
});

const loadView = async () => {
    showView.value = true;
}

const showView = ref(false);
</script>

<template>
    <LoaderView v-show="!showView" />

    <section v-show="showView" id="home">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    Inicio
                </li>
            </ol>
        </nav>
    </section>
</template>