<script setup>
import { ref } from 'vue';
import { removeErrors, setError } from '@/modules/composables/forms';
import { validateCurp, validateRfc } from '@/modules/composables/inputs';
import { showToast } from '@/modules/composables/alert';
import LoaderData from '@/modules/components/LoaderData.vue';
import Footer from '@/modules/components/Footer.vue';
import { useRouter } from 'vue-router';
import { login } from '@/modules/helpers/auth';

const router = useRouter();

// refs
const loginForm = ref({
    rfc: '',
    password: '',
});

const showLoaderForm = ref(false);

// vars
const date = new Date();
const year = date.getFullYear();

const validateForm = async () => {
    removeErrors();

    const form = loginForm.value;

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

    if (form.password == '') {
        showToast({ icon: 'error', message: 'Campo Requerido' });
        setError({ id: 'password-input', message: '' });
        return;
    }

    const data = {
        rfc: form.rfc,
        curp: form.password
    };

    showLoaderForm.value = true;
    const { error, message } = await login(data);
    
    if (error) {
        showLoaderForm.value = false;
        showToast({ icon: 'error', message: message });
        return; 
    }

    router.push('/dashboard');
}

</script>

<template>
    <section id="login" class="container">
        <h5 class="logo">
            icon•<span>G</span>
        </h5>

        <div class="row px-4 px-md-0">
            <form class="d-flex flex-column col-12 col-md-6 col-lg-4 col-xl-3 gap-4">
                <h2 class="title fw-bold text-center text-lg-start">
                    Ingresar<br class="d-none d-lg-block">
                    al Sistema
                </h2>

                <span>
                    <label for="" class="fw-bold"> R.F.C. </label>
                    <input type="text" id="rfc-input" class="form-control shadow" v-model="loginForm.rfc">
                </span>

                <span>
                    <label for="" class="fw-bold"> Contraseña </label>
                    <input type="password" id="password-input" class="form-control shadow" v-model="loginForm.password">
                </span>

                <LoaderData v-if="showLoaderForm" />

                <button type="button" class="btn btn-sm btn-primary mx-auto shadow-sm" @click="validateForm">
                    Iniciar Sesión
                </button>
            </form>
        </div>

        <Footer />
    </section>
</template>