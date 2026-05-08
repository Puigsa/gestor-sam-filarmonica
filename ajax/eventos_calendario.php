<?php
include "../includes/config.php";

$conexion = conectar();

$eventos = $conexion->query("SELECT titulo, fecha, lugar, hora, cartel FROM eventos WHERE publicado = 1");

$resultado = [];

while ($fila = $eventos->fetch_assoc()) {
    $resultado[] = [
        "title" => $fila['titulo'],
        "start" => $fila['fecha'],
        "color" => "#800020",
        "extendedProps" => [
            "descripcion" => $fila['titulo'],
            "lugar" => $fila['lugar'],
            "hora" => $fila['hora'],
            "cartel" => $fila['cartel']
        ]
    ];
}

desconectar($conexion);

header('Content-Type: application/json');
echo json_encode($resultado);
?>