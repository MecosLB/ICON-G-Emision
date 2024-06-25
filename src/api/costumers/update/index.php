<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require_once('../../bd/connection.php');

require_once('../../shared/validations.php');

$response = array();

$error = false;
$message = '';

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    $error = true;
    $message = 'C001 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
    goto End;
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if ((!isset($_POST['rfcEmisor'])) || (empty($_POST['rfcEmisor']))) {
        $error = true;
        $message = 'C002 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    if ((!isset($_POST['costumer'])) || (empty($_POST['costumer']))) {
        $error = true;
        $message = 'C002 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    if ((!isset($_POST['token'])) || (empty($_POST['token']))) {
        $error = true;
        $message = 'C010 - No se encontro sesion activa';
        goto End;
    }

    if(!validarToken(trim($_POST['token']),$connection)){
        $error = true;
        $message = 'C010 - Token invalido';
        goto End;
    }
    
    $costumer = json_decode(trim($_POST['costumer']), true);

    if (is_null($costumer)) {
        $error = true;
        $message = 'C003 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    if (count($costumer) == 0) {
        $error = true;
        $message = 'C004 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    if ((!isset($costumer['id'])) || (empty($costumer['id']))) {
        $error = true;
        $message = 'C005 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $rfcEmisor = trim($_POST['rfcEmisor']);

    $id = trim($costumer['id']);
    
    $rfc = trim($costumer['rfc']);
    if (!validateRfc($rfc)) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $curp = trim($costumer['curp']);
    if (!validateCurp($curp)) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $nombres = trim($costumer['nombres']);
    $apellidoPaterno = trim($costumer['apellidoPaterno']);
    $apellidoMaterno = trim($costumer['apellidoMaterno']);
    $razonSocial = trim($costumer['razonSocial']);

    $correo = trim($costumer['correo']);
    if (!validateEmail($correo)) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $telefono = trim($costumer['telefono']);
    if (!validatePhone($telefono)) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $fechaDeNacimiento = trim($costumer['fechaDeNacimiento']);
    $fechaDeNacimiento = date('Y-m-d', strtotime($fechaDeNacimiento));

    $codigoPostal = trim($costumer['codigoPostal']);
    if (!validateCP($codigoPostal)) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $query = 'UPDATE `clientes` SET `nombres` = "' . $nombres . '", `apellidoPaterno` = "' . $apellidoPaterno . '", `apellidoMaterno` = "' . $apellidoMaterno . '", ';
    $query.= '`razonSocial` = "' . $razonSocial . '", `correo` = "' . $correo . '", `telefono` = "' . $telefono . '", `fechaDeNacimiento` = "' . $fechaDeNacimiento . '", ';
    $query.= '`codigoPostal` = "' . $codigoPostal . '" WHERE `id` = "' . $id . '" AND `rfcEmisor` = "' . $rfcEmisor . '" ';

    $result = $connection->query($query);

    if (!$result) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>' . base64_encode($connection->error);
        goto End;
    }

    $message = 'Se actualizó con éxito el cliente seleccionado.';
}

End:

$response = array(
    'error' => $error,
    'message' => $message
);

echo json_encode($response);