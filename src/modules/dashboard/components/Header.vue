<script setup>
const closeSidebar = () => {
    const btn = document.getElementById('btn-sidebar');
    btn.click();
}
</script>

<template>
    <header>
        <nav>
            <button id="btn-sidebar" class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                aria-controls="sidebar">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>

            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="sidebar"
                aria-labelledby="sidebarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="sidebarLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="list-group">
                        <router-link :to="{ name: 'home' }" class="list-group-item list-group-item-action" @click="closeSidebar">
                            <i class="fa-solid fa-house me-2"></i>
                            Inicio
                        </router-link>

                        <router-link :to="{ name: 'costumers' }" class="list-group-item list-group-item-action" @click="closeSidebar">
                            <i class="fa-solid fa-address-book me-2"></i>
                            Clientes
                        </router-link>
                    </ul>
                </div>

                <div class="offcanvas-footer">
                    <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </nav>
    </header>
</template>