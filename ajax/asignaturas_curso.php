<?php 
require_once "../includes/config.php";

$conexion = conectar();
$id_curso = (int)$_GET['id_curso'];

$asignaturas = $conexion->query("SELECT id_asignatura, nombre FROM asignaturas WHERE id_curso=$id_curso ");

if($asignaturas->num_rows == 0) {
    echo "<option value=''>No hay asignaturas</option>";
} else {
    echo "<h4>Asignaturas del curso</h4>";
    echo "<ul>";
    while ($asignatura = $asignaturas->fetch_assoc()) {
        echo "<li>" . $asignatura['nombre'] . "</li>";
    }
    echo "</ul>";
}

desconectar($conexion);
?> 