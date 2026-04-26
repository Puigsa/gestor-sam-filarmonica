<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("Europe/Madrid");


function conectar() {
    $conexion = new mysqli("localhost", "root", "", "gestor_sam_filarmonica");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $conexion->set_charset("utf8");

    return $conexion;
}

function desconectar($conexion) {
    $conexion->close();
}