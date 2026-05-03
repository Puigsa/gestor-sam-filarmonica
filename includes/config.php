<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("Europe/Madrid");



function conectar()
{
    $host    = "localhost";
    $usuario = "root";
    $pass    = "";
    $db      = "escuela_filarmonica";
    $conexion = new mysqli($host, $usuario, "", $db);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $conexion->set_charset("utf8");

    return $conexion;
}

function desconectar($conexion)
{
    $conexion->close();
}
