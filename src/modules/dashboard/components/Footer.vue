<script setup>
const date = new Date();
const year = date.getFullYear();
</script>

<template>
    <footer>
        <div class="d-flex align-items-center justify-content-center">
            <p class="mb-0 fw-bold">
                <i class="fa-solid fa-copyright"></i>
                
                {{ year }}.
                
                Todos los derechos reservados.
            </p>
        </div>
    </footer>
</template>