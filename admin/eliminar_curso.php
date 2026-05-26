<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: cursos.php");
    exit;
}

$conexion = conectar();

$existe = $conexion->query("SELECT id_curso FROM cursos WHERE id_curso=$id");
if ($existe->num_rows == 0) {
    header("Location: cursos.php");
    exit;
}

$conexion->query("DELETE FROM cursos WHERE id_curso=$id");
desconectar($conexion);

header("Location: cursos.php");
exit;
?>