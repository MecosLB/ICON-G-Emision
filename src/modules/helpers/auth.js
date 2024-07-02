import axios from 'axios';

import store from '@/store';

const api = store.state.api;
const endPoint = `${api}/auth`;

export const login = async ({ rfc, curp }) => {
    try {
        const url = `${endPoint}/login/`;
        const formData = new FormData();
        formData.append('rfc', rfc);
        formData.append('curp', curp);

        const { data } = await axios.post(url, formData);
        const { error, message, emisor, token } = data;

        if (!error)
            localStorage.setItem('iconG', JSON.stringify({
                token: token,
                emisor: {
                    id: emisor.id,
                    rfc: emisor.rfc,
                    razonSocial: emisor.razonSocial,
                }
            }));

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const logout = async () => {
    try {
        const url = `${endPoint}/logout/`;
        const formData = new FormData();
        const { token } = JSON.parse(localStorage.getItem('iconG'));
        formData.append('token', token);

        const { data } = await axios.post(url, formData);
        const { error, message } = data;

        if (!error)
            localStorage.removeItem('iconG');

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const validateToken = async () => {
    try {
        const url = `${endPoint}/validate/`;
        const formData = new FormData();
        const { token } = JSON.parse(localStorage.getItem('iconG'));
        formData.append('token', token);

        const { data } = await axios.post(url, formData);
        const { error, message } = data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}