import axios from 'axios';

import store from '@/store';

const api = store.state.api;
const endPoint = `${api}/issue`;
const { token, emisor } = JSON.parse(localStorage.getItem('iconG'));

export const retenciones = async (data) => {
    try {
        const url = `${endPoint}/retenciones/`;

        const formData = new FormData();

        formData.append('data', JSON.stringify(data));

        formData.append('token', token);

        formData.append('emisor', btoa(JSON.stringify(emisor)));

        const response = await axios.post(url, formData);

        const { error, message, xml } = response.data;

        return { error: error, message: message, xml: xml }
    } catch (error) {
        return { error: true, message: error, xml: '' }
    }
}