<script setup>
import { ref } from 'vue';

const props = defineProps([ 'pagination' ]);

const emit = defineEmits([ 'updatePagination' ])

const pagination = ref({
    page: 1,
    totalPages: 1,
    totalRows: 10,
});

if (props.pagination) {
    pagination.value = props.pagination;
}

const changePage = (page) => {
    pagination.value.page = page;
    emit('updatePagination', pagination.value)
}

const next = () => {
    pagination.value.page = parseInt(pagination.value.page) + 1;
    emit('updatePagination', pagination.value)
}

const prev = () => {
    pagination.value.page = parseInt(pagination.value.page) - 1;
    emit('updatePagination', pagination.value)
}

const setClass = (page) => {
    if (parseInt(page) === parseInt(pagination.value.page)) return 'page-link rounded active';
    else return 'page-link rounded';
}
</script>

<template>
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center gap-2">
            <li v-if="pagination.page > 1" class="page-item">
                <a class="page-link rounded" @click="prev">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </li>
            
            <li v-for="page in pagination.totalPages" class="page-item">
                <a :class="setClass(page)" v-html="page" @click="changePage(page)"></a>
            </li>

            <li v-if="pagination.page < pagination.totalPages" class="page-item">
                <a class="page-link rounded" @click="next">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>