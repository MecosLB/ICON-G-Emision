import axios from 'axios';

import store from '@/store';

const api = store.state.api;
const endPoint = `${api}/auth`;

export const login = async (data) => {
    try {
        const url = `${endPoint}/login/`;

        const formData = new FormData();

        formData.append('data', data);

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const logout = async () => {
    try {
        const url = `${endPoint}/logout/`;

        const formData = new FormData();

        const token = 'a';

        formData.append('token', token);

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}