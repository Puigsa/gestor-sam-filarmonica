<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: usuarios.php");
    exit;
}

$conexion = conectar();

$existe = $conexion->query("SELECT id_usuario FROM usuarios WHERE id_usuario=$id");
if ($existe->num_rows == 0) {
    header("Location: usuarios.php");
    exit;
}

$conexion->query("DELETE FROM usuarios WHERE id_usuario=$id");
desconectar($conexion);

header("Location: usuarios.php");
exit;
?>