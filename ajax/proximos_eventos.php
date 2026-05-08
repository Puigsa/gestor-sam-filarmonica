<?php
include "../includes/config.php";

$conexion = conectar();
$proximos = $conexion->query("SELECT titulo, fecha, hora, lugar, cartel FROM eventos WHERE fecha >= CURDATE() AND publicado = 1 ORDER BY fecha ASC LIMIT 3");

$resultado = [];
while ($evento = $proximos->fetch_assoc()) {
    $resultado[] = $evento;
}

desconectar($conexion);

header('Content-Type: application/json');
echo json_encode($resultado);
?>
