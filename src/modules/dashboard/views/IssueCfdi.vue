<script setup>
import { onMounted, ref } from 'vue';

import Swal from 'sweetalert2';

import Multiselect from '@vueform/multiselect';

import LoaderView from '@/modules/components/LoaderView.vue';
import LoaderData from '@/modules/components/LoaderData.vue';
import Alert from '@/modules/components/Alert.vue';

import { showAlert, showToast } from '@/modules/composables/alert.js';
import { removeError, removeErrors, setError } from '@/modules/composables/forms.js';

import { retenciones } from '@/modules/helpers/issue.js'

import { getCEjercicio, getCPeriodo, getCveRetenc, getCTipoRelacion, getToken } from '@/modules/helpers/apiCatSat.js'

const actionTaxes = ref('Agregar');

const rfcEmisor = ref('XAXX010101000');

onMounted(async () => {
    await loadView();
});

const addEditTaxes = () => {
    removeErrors();

    const { index, BaseRet, ImpuestoRet, MontoRet, TipoPagoRet } = taxesForm.value;

    if (BaseRet == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'BaseRet-inputTax', message: '' });
        return;
    }

    if (ImpuestoRet == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'ImpuestoRet-inputTax', message: '' });
        return;
    }

    if (MontoRet == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MontoRet-inputTax', message: '' });
        return;
    }

    if (TipoPagoRet == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'TipoPagoRet-inputTax', message: '' });
        return;
    }

    if (actionTaxes.value == 'Agregar') {
        cfdiForm.value.Totales.ImpRetenidos.push({ BaseRet: BaseRet, ImpuestoRet: ImpuestoRet, MontoRet: MontoRet, TipoPagoRet: TipoPagoRet });
    }

    if (actionTaxes.value == 'Editar') {
        const tax = cfdiForm.value.Totales.ImpRetenidos[index];

        tax.BaseRet = BaseRet;
        tax.ImpuestoRet = ImpuestoRet;
        tax.MontoRet = MontoRet;
        tax.TipoPagoRet = TipoPagoRet;
    }

    document.getElementById('btnCloseModalTaxes').click();
}

const catalogs = ref({
    cveRetenc: [],
    tipoRelacion: [],
    periodo: [],
    ejercicio: []
});

const cfdiForm = ref({
    Retenciones: {
        Version: '',
        FolioInt: '',
        Sello: '',
        NoCertificado: '',
        Certificado: '',
        FechaExp: '',
        LugarExpRetenc: '',
        CveRetenc: null,
        DescRetenc: ''
    },
    CfdiRetenRelacionados: [],
    Emisor: {
        RfcE: '',
        NomDenRazSocE: '',
        RegimenFiscalE: '',
    },
    Receptor: {
        NacionalidadR: null,
        RfcR: '',
        NomDenRazSocR: '',
        CurpR: '',
        DomicilioFiscalR: '',
        NumRegIdTribR: ''
    },
    Periodo: {
        MesIni: null,
        MesFin: null,
        Ejercicio: null
    },
    Totales: {
        MontoTotOperacion: '',
        MontoTotGrav: '',
        MontoTotExent: '',
        MontoTotRet: '',
        UtilidadBimestral: '',
        ISRCorrespondiente: '',
        ImpRetenidos: []
    }
});

const CfdiRetRelInputs = ref({
    TipoRelacion: '',
    UUID: ''
});

const configAlertTaxes = ref({
    type: 'info',
    message: 'No existen impuestos registrados.',
});

const configAlertCfdiRel = ref({
    type: 'info',
    message: 'No existen C.F.D.I.S. Relacionados registrados.',
});

const confirmDelete = (index) => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-sm btn-primary',
            cancelButton: 'btn btn-sm btn-primary me-2'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Eliminar Impuesto',
        icon: 'question',
        text: '¿Realmente desea eliminar este impuesto?',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check text-success"></i> Si, Eliminar',
        cancelButtonText: '<i class="fa-solid fa-xmark text-danger"></i> No, cancelar',
        reverseButtons: true
    }).then(async (result) => {
        if (result.isConfirmed) {
            const taxes = cfdiForm.value.Totales.ImpRetenidos;
            cfdiForm.value.Totales.ImpRetenidos = taxes.filter((key, i) => i !== index);
        }
    });
}

const getCatalogs = async () => {
    const resGetToken = await getToken();

    if (resGetToken.error) return;

    const resCveRetenc = await getCveRetenc(resGetToken.token);
    if (!resGetToken.error) catalogs.value.cveRetenc = resCveRetenc.catalog;

    const resCTipoRelacion = await getCTipoRelacion(resGetToken.token);
    if (!resGetToken.error) catalogs.value.tipoRelacion = resCTipoRelacion.catalog;

    const resCPeriodo = await getCPeriodo(resGetToken.token);
    if (!resGetToken.error) catalogs.value.periodo = resCPeriodo.catalog;

    const resCEjercicio = await getCEjercicio(resGetToken.token);
    if (!resGetToken.error) catalogs.value.ejercicio = resCEjercicio.catalog;
}

const taxesForm = ref({
    index: '',
    BaseRet: '',
    ImpuestoRet: '',
    MontoRet: '',
    TipoPagoRet: '',
});

const isNational = ref(true);

const issueCfdi = async () => {
    if (!validateCfdi()) return;

    showTab('retenciones-tab');

    showLoaderFormTaxes.value = true;

    const cfdi = cfdiForm.value;

    const response = await retenciones(cfdi);

    showLoaderFormTaxes.value = false;

    if (response.error) {
        showAlert({ icon: 'error', title: '¡ERROR!', message: response.message });
        return;
    }
}

const loadView = async () => {
    await getCatalogs();

    showView.value = true;
}

const modalTaxes = (action, index = '') => {
    switch (action) {
        case 'Agregar':
            actionTaxes.value = action;
            break;

        case 'Cerrar':
            resetFormTax();
            actionTaxes.value = 'Agregar';
            break;

        case 'Cancelar':
            resetFormTax();
            actionTaxes.value = 'Agregar';
            break;

        case 'Editar':
            actionTaxes.value = action;

            const taxes = cfdiForm.value.Totales.ImpRetenidos;

            const { BaseRet, ImpuestoRet, MontoRet, TipoPagoRet } = taxes[index];

            taxesForm.value = {
                index: index,
                BaseRet: BaseRet,
                ImpuestoRet: ImpuestoRet,
                MontoRet: MontoRet,
                TipoPagoRet: TipoPagoRet
            }
            break;

        default:
            break;
    }
}

const nacionalidades = ref([
    { label: 'Nacional', value: 'Nacional' },
    { label: 'Extranjero', value: 'Extranjero' },
]);

const resetFormTax = () => {
    removeErrors();

    taxesForm.value = {
        index: '',
        base: '',
        impuesto: '',
        monto: '',
        tipoPago: '',
    }
}

const selectNacionalidad = (value) => {
    if (value == 'Nacional') isNational.value = true;
    if (value == 'Extranjero') isNational.value = false;
    if (value == null) isNational.value = true;
}

const showLoaderForm = ref(false);
const showLoaderFormTaxes = ref(false);

const showView = ref(false);

const showTab = (id) => {
    const tab = document.getElementById(id);
    tab.click();
};

const validateCfdi = () => {
    removeErrors();
    removeErrorMultiSelects();

    const form = cfdiForm.value;

    const Retenciones = form.Retenciones;

    if (Retenciones.FechaExp == '') {
        showTab('retenciones-tab');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'FechaExp-input', message: '' });
        return false;
    }

    const Receptor = form.Receptor;

    if (Receptor.NacionalidadR == null) {
        showTab('receptor-tab');
        setErrorMultiSelect('NacionalidadR-input');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'NacionalidadR-input', message: '' });
        return false;
    }

    const NacionalidadR = Receptor.NacionalidadR;

    if (NacionalidadR == 'Nacional') {
        if (Receptor.RfcR == '') {
            showTab('receptor-tab');
            showToast({ icon: 'error', message: 'Campo Requerido' });
            setError({ id: 'RfcR-input', message: '' });
            return false;
        }

        if (Receptor.NomDenRazSocR == '') {
            showTab('receptor-tab');
            showToast({ icon: 'error', message: 'Campo Requerido' });
            setError({ id: 'NomDenRazSocR-input', message: '' });
            return false;
        }

        if (Receptor.CurpR != '') { }

        if (Receptor.DomicilioFiscalR == '') {
            showTab('receptor-tab');
            showToast({ icon: 'error', message: 'Campo Requerido' });
            setError({ id: 'DomicilioFiscalR-input', message: '' });
            return false;
        }
    }

    if (NacionalidadR == 'Extranjero') {
        if (Receptor.NumRegIdTribR != '') { }

        if (Receptor.NomDenRazSocR == '') {
            showTab('receptor-tab');
            showToast({ icon: 'error', message: 'Campo Requerido' });
            setError({ id: 'NomDenRazSocR-input', message: '' });
            return false;
        }
    }

    const Periodo = form.Periodo;

    if (Periodo.MesIni == null) {
        showTab('periodo-tab')
        setErrorMultiSelect('MesIni-input');;
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MesIni-input', message: '' });
        return false;
    }

    if (Periodo.MesFin == null) {
        showTab('periodo-tab')
        setErrorMultiSelect('MesFin-input');;
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MesFin-input', message: '' });
        return false;
    }

    if (Periodo.Ejercicio == null) {
        showTab('periodo-tab');
        setErrorMultiSelect('Ejercicio-input');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'Ejercicio-input', message: '' });
        return false;
    }

    const Totales = form.Totales;

    if (Totales.MontoTotOperacion == '') {
        showTab('totales-tab');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MontoTotOperacion-input', message: '' });
        return false;
    }

    if (Totales.MontoTotGrav == '') {
        showTab('totales-tab');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MontoTotGrav-input', message: '' });
        return false;
    }

    if (Totales.MontoTotExent == '') {
        showTab('totales-tab');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MontoTotExent-input', message: '' });
        return false;
    }

    if (Totales.MontoTotRet == '') {
        showTab('totales-tab');
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'MontoTotRet-input', message: '' });
        return false;
    }

    if (Totales.UtilidadBimestral != '') { }

    if (Totales.ISRCorrespondiente != '') { }

    if (Totales.ImpRetenidos.length > 0) { }

    return true;
}

const setErrorMultiSelect = (id) => {
    const input = document.getElementById(id);

    input.focus();
    input.style = 'border-color: var(--bs-form-invalid-border-color);box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb), .25);';
}

const removeErrorMultiSelects = () => {
    const multiselects = document.querySelectorAll('.multiselect-search');
    if (multiselects.length == 0) return;
    for (const key in multiselects) {
        if (Object.prototype.hasOwnProperty.call(multiselects, key)) {
            const element = multiselects[key];
            const input = document.getElementById(element.id);
            input.style = '';
        }
    }
}
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

        <form autocomplete="off">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="retenciones-tab-pane" role="tabpanel"
                    aria-labelledby="retenciones-tab" tabindex="0">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Folio </label>
                                <input type="text" id="FolioInt-input" class="form-control form-control-sm"
                                    placeholder="Opcional" v-model="cfdiForm.Retenciones.FolioInt">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Fecha </label>
                                <input type="text" id="FechaExp-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Retenciones.FechaExp">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Cve. Retención </label>
                                <!-- <input type="text" id="CveRetenc-input" class="form-control form-control-sm" placeholder="Requerido" v-model="cfdiForm.Retenciones.CveRetenc"> -->
                                <Multiselect :options="catalogs.cveRetenc" :id="'CveRetenc-input'" :searchable="true"
                                    trackBy="label" label="label" :placeholder="'Requerido'"
                                    v-model="cfdiForm.Retenciones.CveRetenc" />
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Descripción </label>
                                <input type="text" id="DescRetenc-input" class="form-control form-control-sm"
                                    placeholder="Opcional" v-model="cfdiForm.Retenciones.DescRetenc">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="cfdisRelacionados-tab-pane" role="tabpanel"
                    aria-labelledby="cfdisRelacionados-tab" tabindex="1">
                    <div class="p-2">
                        <div class="row mb-4">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Tipo de Relación </label>
                                <!-- <input type="text" class="form-control form-control-sm" placeholder="Requerido"> -->
                                <Multiselect :options="catalogs.tipoRelacion" :id="'tipoRelacion-input'" :searchable="true"
                                    trackBy="label" label="label" :placeholder="'Requerido'"
                                    v-model="CfdiRetRelInputs.TipoRelacion" />
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> U.U.I.D. </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Requerido">
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

                                <Alert v-if="cfdiForm.CfdiRetenRelacionados.length == 0" :configuration="configAlertCfdiRel" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="receptor-tab-pane" role="tabpanel" aria-labelledby="receptor-tab"
                    tabindex="2">
                    <div class="p-2">
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold mb-1"> Nacionalidad </label>
                                <Multiselect :options="nacionalidades" :id="'NacionalidadR-input'" :searchable="true"
                                    trackBy="label" label="label" @change="selectNacionalidad" :placeholder="'Requerido'"
                                    v-model="cfdiForm.Receptor.NacionalidadR" />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> R.F.C. </label>
                                <input type="text" id="RfcR-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Receptor.RfcR">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Nombre / Razon Social </label>
                                <input type="text" id="NomDenRazSocR-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Receptor.NomDenRazSocR">
                            </div>

                            <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> C.U.R.P. </label>
                                <input type="text" id="CurpR-input" class="form-control form-control-sm"
                                    placeholder="Opcional" v-model="cfdiForm.Receptor.CurpR">
                            </div>

                            <div v-if="isNational" class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Domicilio Fiscal </label>
                                <input type="text" id="DomicilioFiscalR-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Receptor.DomicilioFiscalR">
                            </div>

                            <div v-if="!isNational" class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Num. Reg. ID Trib. </label>
                                <input type="text" id="NumRegIdTribR-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Receptor.NumRegIdTribR">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="periodo-tab-pane" role="tabpanel" aria-labelledby="periodo-tab" tabindex="3">
                    <div class="p-2">
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Mes Inicio </label>
                                <!-- <input type="text" id="MesIni-input" class="form-control form-control-sm" placeholder="Requerido" v-model="cfdiForm.Periodo.MesIni"> -->
                                <Multiselect :options="catalogs.periodo" :id="'MesIni-input'" :searchable="true"
                                    trackBy="label" label="label" :placeholder="'Requerido'"
                                    v-model="cfdiForm.Periodo.MesIni" />
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Mes Fin </label>
                                <!-- <input type="text" id="MesFin-input" class="form-control form-control-sm" placeholder="Requerido" v-model="cfdiForm.Periodo.MesFin"> -->
                                <Multiselect :options="catalogs.periodo" :id="'MesFin-input'" :searchable="true"
                                    trackBy="label" label="label" :placeholder="'Requerido'"
                                    v-model="cfdiForm.Periodo.MesFin" />
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Ejercicio </label>
                                <!-- <input type="text" id="Ejercicio-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Periodo.Ejercicio"> -->
                                <Multiselect :options="catalogs.ejercicio" :id="'Ejercicio-input'" :searchable="true"
                                    trackBy="label" label="label" :placeholder="'Requerido'"
                                    v-model="cfdiForm.Periodo.Ejercicio" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="totales-tab-pane" role="tabpanel" aria-labelledby="totales-tab" tabindex="4">
                    <div class="p-2">
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Monto Total Operacion </label>
                                <input type="text" id="MontoTotOperacion-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Totales.MontoTotOperacion">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Monto Total Gravado </label>
                                <input type="text" id="MontoTotGrav-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Totales.MontoTotGrav">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Monto Total Exento </label>
                                <input type="text" id="MontoTotExent-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Totales.MontoTotExent">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Monto Total Retenido </label>
                                <input type="text" id="MontoTotRet-input" class="form-control form-control-sm"
                                    placeholder="Requerido" v-model="cfdiForm.Totales.MontoTotRet">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> Utilidad Bimestral </label>
                                <input type="text" id="UtilidadBimestral-input" class="form-control form-control-sm"
                                    placeholder="Opcional" v-model="cfdiForm.Totales.UtilidadBimestral">
                            </div>

                            <div class="col-12 col-sm-12 col-md-4">
                                <label for="" class="fw-bold"> I.S.R. Correspondiente </label>
                                <input type="text" id="ISRCorrespondiente-input" class="form-control form-control-sm"
                                    placeholder="Opcional" v-model="cfdiForm.Totales.ISRCorrespondiente">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end mb-2 mt-3">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#taxes" @click="modalTaxes('Agregar')">
                                <i class="fa-solid fa-plus"></i>
                                {{ actionTaxes }} Impuesto
                            </button>
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
                                            <th scope="col"> Acciones </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-if="cfdiForm.Totales.ImpRetenidos.length > 0" v-for="(tax, index) in cfdiForm.Totales.ImpRetenidos">
                                            <td class="align-middle"> {{ tax.BaseRet }} </td>
                                            <td class="align-middle"> {{ tax.ImpuestoRet }} </td>
                                            <td class="align-middle"> {{ tax.MontoRet }} </td>
                                            <td class="align-middle"> {{ tax.TipoPagoRet }} </td>
                                            <td class="align-middle text-center">
                                                <div class="dropdown dropstart">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="dropdown-item" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#taxes"
                                                                @click="modalTaxes('Editar', index)">
                                                                <i class="fa-solid fa-user-pen text-warning me-2"></i>
                                                                Editar
                                                            </button>
                                                        </li>

                                                        <li>
                                                            <button class="dropdown-item" type="button"
                                                                @click="confirmDelete(index)">
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

                                <Alert v-if="cfdiForm.Totales.ImpRetenidos.length == 0" :configuration="configAlertTaxes" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <LoaderData v-if="showLoaderFormTaxes" />

        <div class="d-flex align-items-center justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-sm btn-primary" @click="issueCfdi">
                Emitir Comprobante
            </button>

            <button type="button" class="btn btn-sm btn-primary">
                Limpiar Comprobante
            </button>
        </div>
    </section>

    <div class="modal fade" id="taxes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="taxesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="taxesLabel">
                        {{ actionTaxes }} Impuesto
                    </h6>
                    <button type="button" id="btnCloseModalTaxes" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" @click="modalTaxes('Cerrar')"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Base </label>
                                <input type="text" id="BaseRet-inputTax" class="form-control form-control-sm"
                                    autocomplete="off" v-model="taxesForm.BaseRet" placeholder="Requerido">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Impuesto </label>
                                <input type="text" id="ImpuestoRet-inputTax" class="form-control form-control-sm"
                                    autocomplete="off" v-model="taxesForm.ImpuestoRet" placeholder="Requerido">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Monto </label>
                                <input type="text" id="MontoRet-inputTax" class="form-control form-control-sm"
                                    autocomplete="off" v-model="taxesForm.MontoRet" placeholder="Requerido">
                            </div>

                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="" class="fw-bold"> Tipo Pago </label>
                                <input type="text" id="TipoPagoRet-inputTax" class="form-control form-control-sm"
                                    autocomplete="off" v-model="taxesForm.TipoPagoRet" placeholder="Requerido">
                            </div>
                        </div>
                    </form>

                    <LoaderData v-if="showLoaderFormTaxes" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"
                        @click="modalTaxes('Cancelar')">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-sm btn-primary" @click="addEditTaxes">
                        {{ actionTaxes }} Impuesto
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>