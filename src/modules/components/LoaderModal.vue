<template>
    <div class="modal fade" id="loaderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="loaderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content d-flex align-items-center">
                <div class="modal-header visually-hidden">
                    <button id="btn-loaderModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.spinner {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: conic-gradient(#0000 10%, var(--black));
    -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 9px), #000 0);
    animation: spinner-zp9dbg 1s infinite linear;
}

@keyframes spinner-zp9dbg {
    to {
        transform: rotate(1turn);
    }
}
</style>