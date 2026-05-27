<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("alumno");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
 

    
        <?php
        $conexion = conectar();
        $id_alumno = $_SESSION['id_usuario'];
        $curso = $conexion->query("SELECT c.nombre 
                                    FROM cursos c JOIN matriculas m ON c.id_curso = m.id_curso
                                    WHERE m.id_alumno=$id_alumno AND m.estado = 'activa'");


        $asignaturas = $conexion->query("SELECT DISTINCT a.id_asignatura, a.nombre
                                            FROM asignaturas a 
                                            JOIN matriculas m ON a.id_curso = m.id_curso
                                            WHERE m.id_alumno = $id_alumno AND m.estado = 'activa'
                                            ORDER BY a.nombre ASC");


        if ($curso->num_rows == 0) {
            echo "<p>No estás matriculado/a en ningún curso.</p>";
        } else {
            $nombre_curso = $curso->fetch_assoc()['nombre'];

            echo "<h1>CURSO: $nombre_curso</h1>";    
            echo "<h1>Mis asignaturas</h1>";
            if ($asignaturas->num_rows == 0) {
                echo "<p>No tienes asignaturas asignadas.</p>";
            } else { ?>
                <div class="dashboard-grid">
                <?php while ($asignatura = $asignaturas->fetch_assoc()) { ?>
                    <div class="dashboard-card">

                        <h3><?= $asignatura['nombre'] ?></h3>

                        <div class="acciones-card">
                            <a href="anuncios.php?id_asignatura=<?= $asignatura['id_asignatura'] ?>"> Anuncios </a>
                            <a href="recursos.php?id_asignatura=<?= $asignatura['id_asignatura'] ?>"> Recursos </a>
                        </div>
                    </div>
        <?php
                }
            }
        }


        desconectar($conexion);
        ?>
    </div>
</main>

<?php include "../plantillas/footer_privado.php";


?>