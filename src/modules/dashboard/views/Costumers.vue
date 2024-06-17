<script setup>
import { onMounted, ref } from 'vue';

import Swal from 'sweetalert2';

import LoaderView from '@/modules/dashboard/components/LoaderView.vue';
import LoaderData from '@/modules/dashboard/components/LoaderData.vue';
import Alert from '@/modules/dashboard/components/Alert.vue';
import Pagination from '@/modules/dashboard/components/Pagination.vue';

import { getCostumers } from '@/modules/helpers/costumers.js'

import { showAlert, showToast } from '@/modules/composables/alert.js';
import { removeError, removeErrors, setError } from '@/modules/composables/forms.js';
import { validateCurp, validateCP, validateEmail, validateRfc, validatePhone } from '@/modules/composables/inputs.js';

onMounted(async () => {
    await loadView();
});

const actionCostumer = async () => {
    if (actionModal.value == 'Agregar') newCostumer();
    if (actionModal.value == 'Editar') editCostumer();

}

const actionModal = ref('Agregar');

const changePagination = async (pagination) => {
    showLoaderData.value = true;
    costumers.value = [];

    setTimeout(async () => {
        await loadCostumers();
        showLoaderData.value = false;
    }, 1000);
}

const closeModalCostumer = () => {
    removeErrors();

    actionModal.value = 'Agregar';
}

const configAlert = ref({
    type: 'info',
    message: 'No existen clientes registrados.',
});

const configPagination = ref({
    page: 1,
    totalPages: 0,
    totalRows: 10,
});

const confirmDelete = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-sm btn-primary',
            cancelButton: 'btn btn-sm btn-primary me-2'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Eliminar Cliente',
        icon: 'question',
        text: '¿Realmente desea eliminar este cliente?',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check text-success"></i> Si, Eliminar',
        cancelButtonText: '<i class="fa-solid fa-xmark text-danger"></i> No, cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showAlert({ icon: 'success', title: '¡ÉXITO!', message: 'Se eliminó con éxito el cliente seleccionado.' });
        }
    });
}

const costumerForm = ref({
    id: '',
    rfc: '',
    curp: '',
    nombres: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    razonSocial: '',
    correo: '',
    telefono: '',
    fechaDeNacimiento: '',
    codigoPostal: '',
});

const costumers = ref([]);

const editCostumer = async () => {
    setTimeout(async () => {
        showLoaderForm.value = false;
        resetFormCostumer();
        document.getElementById('btnCloseModal').click();
        showAlert({ icon: 'success', title: '¡ÉXITO!', message: 'Se actualizó con éxito el cliente.' });
    }, 1000);
}

const loadCostumers = async () => {
    const rfc = 'XAXX010101000';

    const response = await getCostumers(rfc, configPagination.value);

    if (response.error) {
        configAlert.value = {
            type: 'danger',
            message: response.message,
        }
        return;
    }

    costumers.value = response.costumers;
    configPagination.value.totalPages = response.totalPages;
}

const loadView = async () => {
    setTimeout(async () => {
        await loadCostumers();
        showView.value = true;
    }, 1000);
}

const modalCostumer = (action) => {
    resetFormCostumer();
}

const newCostumer = async () => {
    setTimeout(async () => {
        showLoaderForm.value = false;
        resetFormCostumer();
        document.getElementById('btnCloseModal').click();
        showAlert({ icon: 'success', title: '¡ÉXITO!', message: 'Se registró con éxito el nuevo cliente.' });
    }, 1000);
}

const resetFormCostumer = () => {
    costumerForm.value = {
        id: '',
        rfc: '',
        curp: '',
        nombres: '',
        apellidoPaterno: '',
        apellidoMaterno: '',
        razonSocial: '',
        correo: '',
        telefono: '',
        fechaDeNacimiento: '',
        codigoPostal: '',
    };
}

const setCostumer = (costumer) => {
    actionModal.value = 'Editar';

    const form = costumerForm.value;

    form.id = costumer.id;
    form.rfc = costumer.rfc;
    form.curp = costumer.curp;
    form.nombres = costumer.nombres;
    form.apellidoPaterno = costumer.apellidoPaterno;
    form.apellidoMaterno = costumer.apellidoMaterno;
    form.razonSocial = costumer.razonSocial;
    form.correo = costumer.correo;
    form.telefono = costumer.telefono;
    form.fechaDeNacimiento = costumer.fechaDeNacimiento;
    form.codigoPostal = costumer.codigoPostal;
}

const showLoaderData = ref(false);

const showLoaderForm = ref(false);

const showView = ref(false);

const validateForm = () => {
    removeErrors();

    const form = costumerForm.value;

    if (form.rfc == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'rfc-input', message: '' });
        return;
    }

    if (!validateRfc(form.rfc)) {
        showToast({ icon: 'error', message: 'R.F.C. Inválido' });
        setError({ id: 'rfc-input', message: '' });
        return;
    }

    if (form.curp == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'curp-input', message: '' });
        return;
    }

    if (!validateCurp(form.curp)) {
        showToast({ icon: 'error', message: 'C.U.R.P. Inválido' });
        setError({ id: 'curp-input', message: '' });
        return;
    }

    if (form.nombres == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'nombres-input', message: '' });
        return;
    }

    if (form.apellidoPaterno == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'apellidoPaterno-input', message: '' });
        return;
    }

    if (form.apellidoMaterno == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'apellidoMaterno-input', message: '' });
        return;
    }

    if (form.razonSocial == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'razonSocial-input', message: '' });
        return;
    }

    if (form.correo == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'correo-input', message: '' });
        return;
    }

    if (!validateEmail(form.correo)) {
        showToast({ icon: 'error', message: 'Correo Inválido' });
        setError({ id: 'correo-input', message: '' });
        return;
    }

    if (form.telefono == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'telefono-input', message: '' });
        return;
    }

    if (!validatePhone(form.telefono)) {
        showToast({ icon: 'error', message: 'Teléfono Inválido' });
        setError({ id: 'telefono-input', message: '' });
        return;
    }

    if (form.fechaDeNacimiento == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'fechaDeNacimiento-input', message: '' });
        return;
    }

    if (form.codigoPostal == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'codigoPostal-input', message: '' });
        return;
    }

    if (!validateCP(form.codigoPostal)) {
        showToast({ icon: 'error', message: 'Código Postal Inválido' });
        setError({ id: 'codigoPostal-input', message: '' });
        return;
    }

    showLoaderForm.value = true;

    actionCostumer();
}
</script>

<template>
    <LoaderView v-show="!showView" />

    <section v-show="showView" id="costumers">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link :to="{ name: 'home' }" class="list-group-item list-group-item-action">
                        Inicio
                    </router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Clientes
                </li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-end gap-3">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#costumerModal"
                @click="modalCostumer('Agregar')">
                Agregar Cliente
            </button>
        </div>

        <div class="row mt-5">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th scope="col"> Nombre Completo </th>
                            <th scope="col"> R.F.C </th>
                            <th scope="col"> C.U.R.P. </th>
                            <th scope="col"> Correo </th>
                            <th scope="col"> Teléfono </th>
                            <th scope="col"> Fecha de Nacimiento </th>
                            <th scope="col"> </th>
                        </tr>
                    </thead>

                    <tbody v-if="costumers.length > 0">
                        <tr v-for="costumer in costumers" :key="costumer.id">
                            <td class="align-middle" v-html="costumer.razonSocial"></td>
                            <td class="align-middle" v-html="costumer.rfc"></td>
                            <td class="align-middle" v-html="costumer.curp"></td>
                            <td class="align-middle" v-html="costumer.correo"></td>
                            <td class="align-middle" v-html="costumer.telefono"></td>
                            <td class="align-middle" v-html="costumer.fechaDeNacimiento"></td>
                            <td class="align-middle">
                                <div class="dropdown dropstart">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item" type="button" data-bs-toggle="modal"
                                                data-bs-target="#costumerModal" @click="setCostumer(costumer)">
                                                <i class="fa-solid fa-user-pen text-warning me-2"></i>
                                                Editar
                                            </button>
                                        </li>

                                        <li>
                                            <button class="dropdown-item" type="button"
                                                @click="confirmDelete(costumer)">
                                                <i class="fa-solid fa-user-xmark text-danger me-2"></i>
                                                Eliminar
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <LoaderData v-if="showLoaderData" />

                <Pagination v-if="(costumers.length > 0) && (configPagination.totalPages > 1)"
                    :pagination="configPagination" @updatePagination="changePagination" />

                <Alert v-if="(costumers.length == 0) && (!showLoaderData)" :configuration="configAlert" />
            </div>
        </div>
    </section>

    <div class="modal fade" id="costumerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="costumerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="costumerModalLabel">
                        {{ actionModal }} Cliente
                    </h6>
                    <button type="button" id="btnCloseModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="closeModalCostumer"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> R.F.C. </label>
                                <input type="text" id="rfc-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.rfc">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> C.U.R.P </label>
                                <input type="text" id="curp-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.curp">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="" class="fw-bold"> Nombre (s) </label>
                                <input type="text" id="nombres-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.nombres">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Apellido Paterno </label>
                                <input type="text" id="apellidoPaterno-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.apellidoPaterno">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Apellido Materno </label>
                                <input type="text" id="apellidoMaterno-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.apellidoMaterno">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-12">
                                <label for="" class="fw-bold"> Razón Social </label>
                                <input type="text" id="razonSocial-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.razonSocial">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Correo </label>
                                <input type="text" id="correo-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.correo">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Teléfono </label>
                                <input type="text" id="telefono-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.telefono">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Fecha de Nacimiento </label>
                                <input type="text" id="fechaDeNacimiento-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.fechaDeNacimiento">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Código Postal </label>
                                <input type="text" id="codigoPostal-input" class="form-control form-control-sm"
                                    autocomplete="off" v-model="costumerForm.codigoPostal">
                            </div>
                        </div>
                    </form>

                    <LoaderData v-if="showLoaderForm" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" @click="closeModalCostumer">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-sm btn-primary" @click="validateForm">
                        {{ actionModal }} Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>