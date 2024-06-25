<?php
function validateCurp ($value) {
    $regEx = '/^[A-Z]{4}\d{6}[A-Z]{6}\d{2}$/';

    if (!preg_match($regEx, $value)) {
        return false;
    }

    return true;
};

function validateCP ($value) {
    $regEx = '/^\d{5}$/';

    if (!preg_match($regEx, $value)) {
        return false;
    }

    return true;
};

function validateEmail ($value) {
    $regEx = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (!preg_match($regEx, $value)) {
        return false;
    }

    return true;
};

function validatePhone ($value) {
    $regEx = '/^\d{10}$/';

    if (!preg_match($regEx, $value)) {
        return false;
    }

    return true;
};

function validateRfc ($value) {
    $regEx = '/^[A-Z&Ñ]{4}\d{6}[A-Z&Ñ0-9]{3}$/';

    if (!preg_match($regEx, $value)) {
        return false;
    }

    return true;
};

function validarToken($token,$connection) {
    $query = 'SELECT * FROM `tokens` WHERE `tokens`.`token` = "' . $token . '"';
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $reg_token = $result->fetch_assoc();
        $fecha_caducidad = new DateTime($reg_token['fechaCaducidad']);
        $fecha_hoy = new DateTime();
        
        if ($reg_token['token'] == $token && $fecha_hoy <= $fecha_caducidad) {
            return true;
        }
    }
    return false;
};
