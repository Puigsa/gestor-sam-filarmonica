<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: eventos.php");
    exit;
}

$conexion = conectar();

$existe = $conexion->query("SELECT id_evento FROM eventos WHERE id_evento=$id");
if ($existe->num_rows == 0) {
    header("Location: eventos.php");
    exit;
}

if ($conexion->query("DELETE FROM eventos WHERE id_evento=$id")) {
    $_SESSION['mensaje'] = "<p class='mensaje_exito'>Evento eliminado correctamente</p>";
} else {
   echo "<p class='mensaje_error'>Error al eliminar el evento</p>";
}
desconectar($conexion);

header("Location: eventos.php");
exit;
?>
