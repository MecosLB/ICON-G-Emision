import axios from 'axios';

import store from '@/store';

const api = store.state.api;
const endPoint = `${api}/clientes`;

export const deleteCostumer = async (costumer) => {
    try {
        const url = ``;

        const formData = new FormData();

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const getCostumers = async (rfc, pagination = {}) => {
    try {
        const url = `${endPoint}/consultar/`;

        const formData = new FormData();

        formData.append('rfc', rfc);

        formData.append('pagination', JSON.stringify(pagination));

        const response = await axios.post(url, formData);

        const { error, message, costumers, totalCostumers, totalPages } = response.data;

        return { error: error, message: message, costumers: costumers, totalCostumers: totalCostumers, totalPages: totalPages }
    } catch (error) {
        return { error: true, message: error, costumers: [], totalCostumers: 0, totalPages: 0 }
    }
}

export const newCostumer = async (costumer) => {
    try {
        const url = ``;

        const formData = new FormData();

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const updateCostumer = async (costumer) => {
    try {
        const url = ``;

        const formData = new FormData();

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}