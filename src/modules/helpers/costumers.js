import axios from 'axios';

import store from '@/store';

const api = store.state.api;
const endPoint = `${api}/costumers`;

export const createCostumer = async (rfcEmisor, costumer) => {
    try {
        const url = `${endPoint}/create/`;

        const formData = new FormData();

        formData.append('rfcEmisor', rfcEmisor);

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const deleteCostumer = async (rfcEmisor, costumer) => {
    try {
        const url = `${endPoint}/delete/`;

        const formData = new FormData();

        formData.append('rfcEmisor', rfcEmisor);

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}

export const getCostumers = async (rfcEmisor, pagination = {}) => {
    try {
        const url = `${endPoint}/read/`;

        const formData = new FormData();

        formData.append('rfcEmisor', rfcEmisor);

        formData.append('pagination', JSON.stringify(pagination));

        const response = await axios.post(url, formData);

        const { error, message, costumers, totalCostumers, totalPages } = response.data;

        return { error: error, message: message, costumers: costumers, totalCostumers: totalCostumers, totalPages: totalPages }
    } catch (error) {
        return { error: true, message: error, costumers: [], totalCostumers: 0, totalPages: 0 }
    }
}

export const updateCostumer = async (rfcEmisor, costumer) => {
    try {
        const url = `${endPoint}/update/`;

        const formData = new FormData();

        formData.append('rfcEmisor', rfcEmisor);

        formData.append('costumer', JSON.stringify(costumer));

        const response = await axios.post(url, formData);

        const { error, message } = response.data;

        return { error: error, message: message }
    } catch (error) {
        return { error: true, message: error }
    }
}