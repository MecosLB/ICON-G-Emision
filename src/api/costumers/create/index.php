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
    $message = 'C001 - Ocurrió un error al intentar realizar una solicitud. Metodo invalido';
    goto End;
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if ((!isset($_POST['rfcEmisor'])) || (empty($_POST['rfcEmisor']))) {
        $error = true;
        $message = 'C002 - Se necesita el RFC emisor';
        goto End;
    }

    if ((!isset($_POST['costumer'])) || (empty($_POST['costumer']))) {
        $error = true;
        $message = 'C003 - Cuerpo de petición invalida. ';
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


    $rfcEmisor = trim($_POST['rfcEmisor']);

    $id = trim($costumer['id']);
    
    $rfc = trim($costumer['rfc']);
    if (!validateRfc($rfc)) {
        $error = true;
        $message = 'C000r - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $curp = trim($costumer['curp']);
    if (!validateCurp($curp)) {
        $error = true;
        $message = 'C000c - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $nombres = trim($costumer['nombres']);
    $apellidoPaterno = trim($costumer['apellidoPaterno']);
    $apellidoMaterno = trim($costumer['apellidoMaterno']);
    $razonSocial = trim($costumer['razonSocial']);

    $correo = trim($costumer['correo']);
    if (!validateEmail($correo)) {
        $error = true;
        $message = 'C00001 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $telefono = trim($costumer['telefono']);
    if (!validatePhone($telefono)) {
        $error = true;
        $message = 'C00002 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $fechaDeNacimiento = trim($costumer['fechaDeNacimiento']);
    $fechaDeNacimiento = date('Y-m-d', strtotime($fechaDeNacimiento));

    $codigoPostal = trim($costumer['codigoPostal']);
    if (!validateCP($codigoPostal)) {
        $error = true;
        $message = 'C00003 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }
    $query_unique_constrait = 'SELECT * FROM `clientes` WHERE `clientes`.`rfc` = "'.$rfc.'" AND `clientes`.`curp`="'.$curp.'" AND `clientes`.`estatus` <> "Eliminado"';
    $result_validation = $connection ->query($query_unique_constrait);

    if($result_validation->num_rows>0){
        $error = true;
        $message = 'C00004 - Ya existe un registro con este CURP y RFC';
        goto End;
    }

    $query = 'INSERT INTO `clientes` (`nombres`, `apellidoPaterno`, `apellidoMaterno`, `razonSocial`, `correo`, `telefono`, `fechaDeNacimiento`, `codigoPostal`, `rfcEmisor`,`rfc`,`curp`) 
          VALUES ("'.$nombres.'", "'.$apellidoPaterno.'", "'.$apellidoMaterno.'","'.$razonSocial.'", "'.$correo.'", "'.$telefono.'", "'.$fechaDeNacimiento.'", "'.$codigoPostal.'", "'.$rfcEmisor.'","'.$rfc.'","'.$curp.'")';
    $result = $connection->query($query);

    if (!$result) {
        $error = true;
        $message = 'C000Re - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>' . base64_encode($connection->error);
        goto End;
    }

    $message = 'Se creo con exito el cliente.';
}

End:

$response = array(
    'error' => $error,
    'message' => $message
);

echo json_encode($response);