<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("alumno");
$id_alumno = $_SESSION['id_usuario'];
$filtro_asignatura = "";

if (isset($_GET['id_asignatura']) && !empty($_GET['id_asignatura'])) {

    $id_asignatura_seleccionada = (int)$_GET['id_asignatura'];
    $filtro_asignatura = " AND a.id_asignatura = $id_asignatura_seleccionada";
}

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>



<main class="main">

<?php botonVolver(); ?>

    <h1>Anuncios</h1>

    <div id="accordion">

        <?php
        $conexion = conectar();

        $result = $conexion->query("SELECT DISTINCT a.*, asi.nombre AS asignatura_nombre, u.nombre AS profesor_nombre
                                    FROM anuncios a
                                    JOIN asignaturas asi ON a.id_asignatura = asi.id_asignatura
                                    JOIN matriculas m ON a.id_curso = m.id_curso
                                    JOIN usuarios u ON a.id_profesor = u.id_usuario
                                    WHERE m.id_alumno = $id_alumno AND m.estado = 'activa' $filtro_asignatura
                                    ORDER BY a.fecha_publicacion DESC");

        if (!$result) {
            die("Error en la consulta SQL: " . $conexion->error);
        }
        if ($result->num_rows == 0) {

            echo "<p>No hay anuncios disponibles.</p>";
        } else {

            while ($anuncio = $result->fetch_assoc()) { ?>

                <h3><?= $anuncio['titulo'] ?> </h3>

                <div class="acordeon-body">
                    <p><strong>Contenido:</strong> <?= $anuncio['contenido'] ?></p>

                    <p><strong>Asignatura:</strong> <?= $anuncio['asignatura_nombre'] ?></p>
                    <p><strong>Publicado por:</strong> <?= $anuncio['profesor_nombre'] ?></p>
                    <p><strong>Fecha publicación:</strong> <?= date("d/m/Y", strtotime($anuncio['fecha_publicacion'])) ?></p>
                </div>

        <?php }
        }

        desconectar($conexion);
        ?>

    </div>
</main>

<script>
    $(function() {

        $("#accordion").accordion({
            collapsible: true,
            heightStyle: "content",
            active: false
        });

    });
</script>

<?php include "../plantillas/footer_privado.php";


?>