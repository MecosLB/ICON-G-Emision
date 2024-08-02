export const getCEjercicio = async (token) => {
    try {
        /*  const options = {
            method: 'POST',
            headers: {
                accept: 'application/json',
                'content-type': 'application/*+json',
                authorization: `Bearer ${token}`
            },
            body: '{"claveCatalogo":84}'
        };
    
        const response = await fetch('https://testapi.facturoporti.com.mx/catalogos/consultar', options)
            .then(response => response.json())
            .catch(err => console.error(err));
    
        const catalog = response.map(({ codigo, descripcion }) => {
            return {
                value: codigo,
                label: `${codigo} - ${descripcion}`
            }
        });

        catalog.sort((a, b) => a.value - b.value); */

        const catalog = [];

        const yearInitial = 2021;
        const yearEnd = 2027;

        for (let index = yearInitial; index <= yearEnd; index ++) {
            catalog.push({ value: `${index}`, label: `${index}` });
        }
    
        return { error: false, message: '', catalog: catalog };
    } catch (error) {
        return { error: true, message: error, catalog: [] };
    }
}

export const getCPeriodo = async (token) => {
    try {
        /*  const options = {
            method: 'POST',
            headers: {
                accept: 'application/json',
                'content-type': 'application/*+json',
                authorization: `Bearer ${token}`
            },
            body: '{"claveCatalogo":84}'
        };
    
        const response = await fetch('https://testapi.facturoporti.com.mx/catalogos/consultar', options)
            .then(response => response.json())
            .catch(err => console.error(err));
    
        const catalog = response.map(({ codigo, descripcion }) => {
            return {
                value: codigo,
                label: `${codigo} - ${descripcion}`
            }
        });

        catalog.sort((a, b) => a.value - b.value); */

        const catalog = [
            { value: '01', label: '01 - Enero' },
            { value: '02', label: '02 - Febrero' },
            { value: '03', label: '03 - Marzo' },
            { value: '04', label: '04 - Abril' },
            { value: '05', label: '05 - Mayo' },
            { value: '06', label: '06 - Junio' },
            { value: '07', label: '07 - Julio' },
            { value: '08', label: '08 - Agosto' },
            { value: '09', label: '09 - Septiembre' },
            { value: '10', label: '10 - Octubre' },
            { value: '11', label: '11 - Noviembre' },
            { value: '12', label: '12 - Diciembre' }
        ];
    
        return { error: false, message: '', catalog: catalog };
    } catch (error) {
        return { error: true, message: error, catalog: [] };
    }
}

export const getCveRetenc = async (token) => {
    try {
        const options = {
            method: 'POST',
            headers: {
                accept: 'application/json',
                'content-type': 'application/*+json',
                authorization: `Bearer ${token}`
            },
            body: '{"claveCatalogo":17}'
        };
    
        const response = await fetch('https://testapi.facturoporti.com.mx/catalogos/consultar', options)
            .then(response => response.json())
            .catch(err => console.error(err));
    
        const catalog = response.map(({ codigo, descripcion }) => {
            return {
                value: codigo,
                label: `${codigo} - ${descripcion}`
            }
        });

        catalog.sort((a, b) => a.value - b.value);
    
        return { error: false, message: '', catalog: catalog };
    } catch (error) {
        return { error: true, message: error, catalog: [] };
    }
}

export const getCTipoRelacion = async (token) => {
    try {
        const options = {
            method: 'POST',
            headers: {
                accept: 'application/json',
                'content-type': 'application/*+json',
                authorization: `Bearer ${token}`
            },
            body: '{"claveCatalogo":7}'
        };
    
        const response = await fetch('https://testapi.facturoporti.com.mx/catalogos/consultar', options)
            .then(response => response.json())
            .catch(err => console.error(err));
    
        const catalog = response.map(({ codigo, descripcion }) => {
            return {
                value: codigo,
                label: `${codigo} - ${descripcion}`
            }
        });

        catalog.sort((a, b) => a.value - b.value);
    
        return { error: false, message: '', catalog: catalog };
    } catch (error) {
        return { error: true, message: error, catalog: [] };
    }
}

export const getToken = async () => {
    try {
        const options = { method: 'GET', headers: { accept: 'application/json' } };

        const response = await fetch('https://testapi.facturoporti.com.mx/token/crear?Usuario=PruebasTimbrado&Password=%40Notiene1', options)
            .then(response => response.json())
            .catch(err => console.error(err));

        const { token } = response;

        return { error: false, message: '', token: token };
    } catch (error) {
        return { error: true, message: '', token: '' };
    }
}