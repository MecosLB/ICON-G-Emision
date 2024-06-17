<?php
$servidor = 'localhost';
$usuario = 'u991697905_emision';
$contrasena = 'r#5M@Nsm!E';
$baseDeDatos = 'u991697905_emision';

$connection = new mysqli (
    $servidor,
    $usuario,
    $contrasena,
    $baseDeDatos
);

if ($connection->connect_errno) {
    echo 'Ocurrió un error al intentar conectar la base de datos: ' . $mysqli->connect_error;
    exit();
}