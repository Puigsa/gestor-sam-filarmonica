<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">

<?php botonVolver(); ?>
    <h1>Mis asignaturas</h1>
    

    <div class="dashboard-grid">
        <?php
        $conexion = conectar();
        $id_profesor = $_SESSION['id_usuario'];
        $asignaturas = $conexion->query("SELECT a.*, c.nombre AS curso_nombre, c.curso_academico 
                                        FROM asignaturas a 
                                        JOIN cursos c ON a.id_curso=c.id_curso 
                                        WHERE a.id_profesor=$id_profesor 
                                        ORDER BY a.nombre ASC");

        if ($asignaturas->num_rows == 0) {
            echo "<p>No tienes asignaturas asignadas.</p>";
        } else {
            while ($asignatura = $asignaturas->fetch_assoc()) { ?>
            <div class="dashboard-card">

            <h3><?= $asignatura['nombre'] ?></h3>

            <div class="acciones-card">
                <a href="listado-alumnos.php?id_asignatura=<?= $asignatura['id_asignatura'] ?>"> Alumnos </a>
                <a href="anuncios.php?id_asignatura=<?= $asignatura['id_asignatura'] ?>"> Anuncios </a>
                <a href="recursos.php?id_asignatura=<?= $asignatura['id_asignatura'] ?>"> Recursos </a>
            </div>
        </div>
        <?php
            }
        }

        desconectar($conexion);
        ?>
    </div>
</main>

<?php include "../plantillas/footer_privado.php";


?>