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

$costumers = array();
$totalCostumers = 0;

$page = 1;
$totalPages = 1;
$totalRows = 10;

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    $error = true;
    $message = 'C001 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
    goto End;
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if ((isset($_POST['pagination']))) {
        $pagination = json_decode($_POST['pagination'], true);
        $totalRows = $pagination['totalRows'];
        $page = $pagination['page'];
    }

    if ((!isset($_POST['rfcEmisor'])) || (empty($_POST['rfcEmisor']))) {
        $error = true;
        $message = 'C000 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
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
    
    $rfcEmisor = trim($_POST['rfcEmisor']);

    $query = 'SELECT * FROM `clientes` WHERE `clientes`.`rfcEmisor` = "' . $rfcEmisor . '" AND `clientes`.`estatus` <> "Eliminado" ';

    if (isset($_POST['filters'])) {
        $filters = json_decode($_POST['filters'], true);
        foreach ($filters as $key => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $query.= 'AND `clientes`.`' . $key . '` LIKE "%' . $value . '%" ';
            }
        }
    }

    $result = $connection->query($query);

    if (!$result) {
        $error = true;
        $message = 'C002 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>' . base64_encode($connection->error);
        goto End;
    }

    if ($result->num_rows == 0) {
        $error = true;
        $message = 'C003 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br> No se encontraron clientes registrados.';
        goto End;
    }

    $totalCostumers = intval($result->num_rows);

    $offset = ($page - 1) * $totalRows;

    $query .= 'ORDER BY `clientes`.`fechaCreacion` DESC LIMIT ' . $offset . ', ' . $totalRows . ' ';

    $totalPages = $totalCostumers / intval($totalRows);
    $totalPages = ceil($totalPages);

    $result = $connection->query($query);

    if (!$result) {
        $error = true;
        $message = 'C004 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>' . base64_encode($connection->error);
        goto End;
    }

    if ($result->num_rows == 0) {
        $error = true;
        $message = 'C005 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br> No se encontraron clientes registrados.';
        goto End;
    }

    $ir = 0;
    while ($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            $costumers[$ir][$key] = trim($value);
        }
        $costumers[$ir]['fechaDeNacimiento'] = date('d/m/Y', strtotime($costumers[$ir]['fechaDeNacimiento']));
        $ir ++;
    }
}

End:

$response = array(
    'error' => $error,
    'message' => $message,
    'costumers' => $costumers,
    'totalCostumers' => $totalCostumers,
    'totalPages' => $totalPages,
);

echo json_encode($response);