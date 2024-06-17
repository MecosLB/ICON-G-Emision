export const removeError = ({ id }) => {
    const input = document.getElementById(id);

    const classList = input.classList;

    classList.remove('is-invalid');
}

export const removeErrors = () => {
    const input = document.querySelectorAll('.is-invalid');
    for (const key in input) {
        if (Object.hasOwnProperty.call(input, key)) {
            const { id } = input[key];
            removeError({id: id})
        }
    }
}

export const setError = ({ id, message }) => {
    const input = document.getElementById(id);
    
    input.focus();

    const classList = input.classList;

    classList.add('is-invalid');
}