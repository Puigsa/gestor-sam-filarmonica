<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: matriculas.php");
    exit;
}

$conexion = conectar();

$existe = $conexion->query("SELECT id_prematricula FROM prematriculas WHERE id_prematricula=$id");
if ($existe->num_rows == 0) {
    header("Location: matriculas.php");
    exit;
}

$conexion->query("UPDATE prematriculas SET estado='rechazada' WHERE id_prematricula=$id");
desconectar($conexion);

header("Location: matriculas.php");
exit;
?>