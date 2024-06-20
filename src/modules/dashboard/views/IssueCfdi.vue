<script setup>
import { onMounted, ref } from 'vue';

import Multiselect from '@vueform/multiselect';

import LoaderView from '@/modules/dashboard/components/LoaderView.vue';
import LoaderData from '@/modules/dashboard/components/LoaderData.vue';
import Alert from '@/modules/dashboard/components/Alert.vue';

const rfcEmisor = ref('XAXX010101000');

onMounted(async () => {
    await loadView();
});

const isNational = ref(true);

const loadView = async () => {
    showView.value = true;
}

const nacionalidades = ref([
    { label: 'Nacional', value: 'Nacional' },
    { label: 'Extranjero', value: 'Extranjero' },
]);

const selectNacionalidad = (value) => {
    if (value == 'Nacional') isNational.value = true;
    if (value == 'Extranjero') isNational.value = false;
    if (value == null) isNational.value = true;
}

const showView = ref(false);
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <LoaderView v-show="!showView" />

    <section v-show="showView" id="issue-cfdi">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link :to="{ name: 'home' }" class="list-group-item list-group-item-action">
                        Inicio
                    </router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Emitir C.F.D.I.
                </li>
            </ol>
        </nav>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="retenciones-tab" data-bs-toggle="tab"
                    data-bs-target="#retenciones-tab-pane" type="button" role="tab" aria-controls="retenciones-tab-pane"
                    aria-selected="true">
                    Retenciones
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cfdisRelacionados-tab" data-bs-toggle="tab"
                    data-bs-target="#cfdisRelacionados-tab-pane" type="button" role="tab"
                    aria-controls="cfdisRelacionados-tab-pane" aria-selected="false">
                    C.F.D.I. (s) Relacionados
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="receptor-tab" data-bs-toggle="tab" data-bs-target="#receptor-tab-pane"
                    type="button" role="tab" aria-controls="receptor-tab-pane" aria-selected="false">
                    Receptor
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="periodo-tab" data-bs-toggle="tab" data-bs-target="#periodo-tab-pane"
                    type="button" role="tab" aria-controls="periodo-tab-pane" aria-selected="false">
                    Periodo
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="totales-tab" data-bs-toggle="tab" data-bs-target="#totales-tab-pane"
                    type="button" role="tab" aria-controls="totales-tab-pane" aria-selected="false">
                    Totales
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="retenciones-tab-pane" role="tabpanel" aria-labelledby="retenciones-tab" tabindex="0">
                <div class="p-2">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Folio </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Fecha </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Cve. Retención </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Descripción </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cfdisRelacionados-tab-pane" role="tabpanel" aria-labelledby="cfdisRelacionados-tab" tabindex="1">
                <div class="p-2">
                    <div class="row mb-4">
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Tipo de Relación </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> U.U.I.D. </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 d-flex align-items-center p-2">
                            <button type="button" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Agregar
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col"> Tipo de Relación </th>
                                        <th scope="col"> U.U.I.D. </th>
                                        <th scope="col"> </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="receptor-tab-pane" role="tabpanel" aria-labelledby="receptor-tab" tabindex="2">
                <div class="p-2">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold mb-1"> Nacionalidad </label>
                            <Multiselect :options="nacionalidades"
                                :searchable="true" trackBy="label" label="label" @change="selectNacionalidad" />
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> R.F.C. </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Nombre  / Razon Social </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> C.U.R.P. </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Domicilio Fiscal </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div v-if="!isNational" class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Num. Reg. ID Trib. </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="periodo-tab-pane" role="tabpanel" aria-labelledby="periodo-tab" tabindex="3">
                <div class="p-2">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Mes Inicio </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Mes Fin </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Ejercicio </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="totales-tab-pane" role="tabpanel" aria-labelledby="totales-tab" tabindex="4">
                <div class="p-2">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Monto Total Operacion </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Monto Total Gravado </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Monto Total Exento </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Monto Total Retenido </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> Utilidad Bimestral </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4">
                            <label for="" class="fw-bold"> I.S.R. Correspondiente </label>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col"> Base </th>
                                        <th scope="col"> Impuesto </th>
                                        <th scope="col"> Monto </th>
                                        <th scope="col"> Tipo Pago </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-sm btn-primary">
                Emitir Comprobante
            </button>

            <button type="button" class="btn btn-sm btn-primary">
                Limpiar Comprobante
            </button>
        </div>
    </section>
</template>