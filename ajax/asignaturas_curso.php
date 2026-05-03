<?php 
require_once "../includes/config.php";

$conexion = conectar();

if (isset($_GET['id_curso']) && !empty($_GET['id_curso'])) {
    $id_curso = $_GET['id_curso'];
    $consulta = "SELECT asignaturas.nombre FROM curso_asignatura 
            INNER JOIN asignaturas ON curso_asignatura.id_asignatura = asignaturas.id_asignatura 
            WHERE curso_asignatura.id_curso = $id_curso
            ORDER BY asignaturas.id_asignatura ASC";

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0){
        echo "<h3>Asignaturas incluidas: </h3><ul>";
        while ($fila = $resultado->fetch_assoc()) {
            echo "<li>" . $fila['nombre'] . "</li>";
        }
        echo "</ul>";
    }
}

desconectar($conexion);
?> 