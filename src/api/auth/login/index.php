<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require_once('../../bd/connection.php');

$response = array();

$error = false;
$message = '';
$razonSocial = '';
$codigoPostal = '';
$regimenFiscal = '';
$rfcEmisor = '';
$curpEmisor = '';
$token = null; // Definir la variable $token
$id = null; // Definir la variable $id

function generateUuidv4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $error = true;
    $message = 'C001 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción del error: </br>';
    goto End;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['rfc']) || empty($_POST['rfc'])) {
        $error = true;
        $message = 'C001 - El campo RFC está vacío';
        goto End;
    }
    if (!isset($_POST['curp']) || empty($_POST['curp'])) {
        $error = true;
        $message = 'C002 - El campo CURP está vacío.';
        goto End;
    }

    $rfc = trim($_POST['rfc']);
    $curp = trim($_POST['curp']);

    $query = 'SELECT * FROM `emisores` WHERE `emisores`.`rfc` = "' . $rfc . '" AND `emisores`.`curp` = "' . $curp . '" AND `emisores`.`estatus` <> "Eliminado" ';
    $result = $connection->query($query);

    if (!$result) {
        $error = true;
        $message = 'C002 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción del error: </br>' . base64_encode($connection->error);
        goto End;
    }

    if ($result->num_rows == 0) {
        $error = true;
        $message = 'C003 - No se encontraron clientes registrados.';
        goto End;
    }

    while ($row = $result->fetch_assoc()) {
        // Procesar cada fila
        $id = $row['id'];
        $razonSocial = $row['razonSocial'];
        $codigoPostal = $row['codigoPostal'];
        $regimenFiscal = $row['regimenFiscal'];
        $rfcEmisor = $rfc;
        $curpEmisor = $curp;
        $token = generateUuidv4();
    }

    if ($token != null) {
        $fecha_hoy = new DateTime();
        $fecha_caducidad = clone $fecha_hoy; // Clonar el objeto DateTime
        $fecha_caducidad->modify('+2 hours');

        $query = 'INSERT INTO `tokens`(`token`,`fechaCreacion`,`fechaCaducidad`) VALUES("' . $token . '", "' . $fecha_hoy->format('Y-m-d H:i:s') . '", "' . $fecha_caducidad->format('Y-m-d H:i:s'). '")';
        $result_token = $connection->query($query);

        if (!$result_token) {
            $error = true;
            $message = 'C009 - Hubo un error al iniciar sesión.';
            goto End;
        }
    }
}

End:

$response = array(
    'error' => $error,
    'message' => $message,
    'token' => $token,
    'emisor' => array(
        'id' => $id,
        'rfc' => $rfcEmisor,
        'curp' => $curpEmisor,
        'razonSocial' => $razonSocial,
        'codigoPostal' => $codigoPostal,
        'regimenFiscal' => $regimenFiscal
    )
);

echo json_encode($response);
?>
