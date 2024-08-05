export const validateCurp = (value) => {
    const regExp = /^[A-Z]{4}\d{6}[A-Z]{6}\d{2}$/;
    if (!regExp.test(value)) return false;
    return true;
}

export const validateCP = (value) => {
    const regExp = /^\d{5}$/;
    if (!regExp.test(value)) return false;
    return true;
}

export const validateEmail = (value) => {
    const regExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!regExp.test(value)) return false;
    return true;
}

export const validateRfc = (value) => {
    const regExp = /^[A-Z&Ñ]{4}\d{6}[A-Z&Ñ0-9]{3}$/;
    if (!regExp.test(value)) return false;
    return true;
}

export const validateNumber =  (value, decimal = 2) => {
    const regExp = new RegExp(`^-?\\d+(\\.\\d{1,${decimal}})?$`);
    if (!regExp.test(value)) return false;
    return true;
}

export const validatePhone = (value) => {
    const regExp = /^\d{10}$/;
    if (!regExp.test(value)) return false;
    return true;
}

export const validateUUID =  (value) => {
    const regExp = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
    if (!regExp.test(value)) return false;
    return true;
}