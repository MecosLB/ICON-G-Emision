<script setup>
import { ref } from 'vue';

const props = defineProps([ 'configuration' ]);

const conf = props.configuration;

const type = ref(conf.type);
const message = ref(conf.message);

const setClass = (type) => {
    switch (type) {
        case 'primary':
            return 'alert alert-primary text-center fw-bold';
        break;

        case 'secondary':
            return 'alert alert-secondary text-center fw-bold';
        break;

        case 'success':
            return 'alert alert-success text-center fw-bold';
        break;

        case 'danger':
            return 'alert alert-danger text-center fw-bold';
        break;

        case 'warning':
            return 'alert alert-warning text-center fw-bold';
        break;

        case 'info':
            return 'alert alert-info text-center fw-bold';
        break;

        case 'dark':
            return 'alert alert-dark text-center fw-bold';
        break;
    
        default:
            return 'alert alert-light text-center fw-bold';
        break;
    }
}
</script>

<template>
    <div :class="setClass(type)" role="alert">
        <i class="fa-solid fa-message me-2"></i>
        <span v-html="message"></span>
    </div>
</template>