import Swal from 'sweetalert2';

export const showAlert = ({ icon, title, message }) => {
    Swal.fire({
        title: title,
        html: message,
        icon: icon,
        showConfirmButton: false,
    });
}

export const showToast = ({ icon, message }) => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    Toast.fire({
        icon: icon,
        html: message
    });
}