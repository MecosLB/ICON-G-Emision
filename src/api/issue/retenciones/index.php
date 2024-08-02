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
$xml = '';

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    $error = true;
    $message = 'C001 - Ocurrió un error al intentar realizar una solicitud. Metodo invalido';
    goto End;
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if ((!isset($_POST['token'])) || (empty($_POST['token']))) {
        $error = true;
        $message = 'C002 - No se encontro sesion activa';
        goto End;
    }

    if ((!isset($_POST['data'])) || (empty($_POST['data']))) {
        $error = true;
        $message = 'C004 - Ocurrió un error al intentar realizar una solicitud. </br> Descripción de el error: </br>';
        goto End;
    }

    $data = json_decode(trim($_POST['data']), true);

    // Genera la estructura base del xml
	$estructura = '<?xml version="1.0" encoding="utf-8"?>';
	$estructura.= '<retenciones:Retenciones xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
    $estructura.= 'xmlns:retenciones="http://www.sat.gob.mx/esquemas/retencionpago/2" ';
    $estructura.= 'xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" ';
    $estructura.= 'xsi:schemaLocation="http://www.sat.gob.mx/esquemas/retencionpago/2 http://www.sat.gob.mx/esquemas/retencionpago/2/retencionpagov2.xsd';
    $estructura.= 'http://www.sat.gob.mx/TimbreFiscalDigital ';
	$estructura.= 'http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd ';
    $estructura.= '"/>';

    // Creación del xml
	$xml = new SimpleXMLElement($estructura, 0, false, 'retenciones', true);

    if (isset($data['Retenciones'])) {
        foreach ($data['Retenciones'] as $attribute => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $xml->addAttribute($attribute, $value);
            }
        }
    }

    if (isset($data['Emisor'])) {
        $xmlEmisor = $xml->addChild('retenciones:Emisor');
        foreach ($data['Emisor'] as $attribute => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $xmlEmisor->addAttribute($attribute, $value);
            }
        }
    }

    if (isset($data['Receptor'])) {
        $xmlReceptor = $xml->addChild('retenciones:Receptor');

        $NacionalidadR = trim($data['Receptor']['NacionalidadR']);

        $xmlReceptor->addAttribute('NacionalidadR', $NacionalidadR);

        if ($NacionalidadR == 'Nacional') {
            $xmlNacExt = $xmlReceptor->addChild('retenciones:Nacional');
        }

        if ($NacionalidadR == 'Extranjero') {
            $xmlNacExt = $xmlReceptor->addChild('retenciones:Extranjero');
        }

        foreach ($data['Receptor'] as $attribute => $value) {
            if ($attribute != 'NacionalidadR') {
                $value = trim($value);
                if (!empty($value)) {
                    $xmlNacExt->addAttribute($attribute, $value);
                }
            }
        }
    }

    if (isset($data['Periodo'])) {
        $xmlPeriodo = $xml->addChild('retenciones:Periodo');
        foreach ($data['Periodo'] as $attribute => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $xmlPeriodo->addAttribute($attribute, $value);
            }
        }
    }

    if (isset($data['Totales'])) {
        $xmlTotales = $xml->addChild('retenciones:Totales');
        
        foreach ($data['Totales'] as $attribute => $value) {
            if ($attribute != 'ImpRetenidos') {
                $value = trim($value);
                if (!empty($value)) {
                    $xmlTotales->addAttribute($attribute, $value);
                }
            }
        }

        if (isset($data['Totales']['ImpRetenidos'])) {
            $ImpRetenidos = $data['Totales']['ImpRetenidos'];
            if (count($ImpRetenidos) > 0) {
                foreach ($ImpRetenidos as $ImpRent) {
                    $xmlImpRetenidos = $xmlTotales->addChild('retenciones:ImpRetenidos');
                    foreach ($ImpRent as $attribute => $value) {
                        $value = trim($value);
                        if (!empty($value)) {
                            $xmlImpRetenidos->addAttribute($attribute, $value);
                        }
                    }
                }
            }
        }
    }

    
    $xml = $xml->asXML();
    $xml = base64_encode($xml);
}

End:

$response = array(
    'error' => $error,
    'message' => $message,
    'xml' => $xml
);

echo json_encode($response);