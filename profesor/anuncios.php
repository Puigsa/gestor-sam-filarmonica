<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

$conexion = conectar();
$mensaje = "";
$id_profesor = $_SESSION['id_usuario'];

// Filtrar por asignatura si viene en la URL o formulario
$filtro_asignatura = "";
$id_asignatura_seleccionada = "";

if (isset($_GET['id_asignatura']) && !empty($_GET['id_asignatura'])) {

    $id_asignatura_seleccionada = (int)$_GET['id_asignatura'];
    $filtro_asignatura = " AND a.id_asignatura = $id_asignatura_seleccionada";
}

// Obtener asignaturas del profesor
$consulta_asignaturas = $conexion->query("SELECT id_asignatura, nombre 
                                            FROM asignaturas 
                                            WHERE id_profesor = $id_profesor 
                                            ORDER BY nombre ASC");

$asignaturas = [];

while ($fila = $consulta_asignaturas->fetch_assoc()) {
    $asignaturas[] = $fila;
}

$anuncios = $conexion->query("SELECT a.*, asig.nombre AS asignatura_nombre 
                                FROM anuncios a 
                                JOIN asignaturas asig ON a.id_asignatura=asig.id_asignatura 
                                WHERE a.id_profesor= $id_profesor $filtro_asignatura
                                ORDER BY a.fecha_publicacion DESC");

if (isset($_GET['eliminar'])) {
    $id_anuncio = (int)$_GET['eliminar'];
    $verificar = $conexion->query("SELECT id_anuncio FROM anuncios WHERE id_anuncio=$id_anuncio AND id_profesor=$id_profesor");
    if ($verificar->num_rows > 0) {

        if ($conexion->query("DELETE FROM anuncios WHERE id_anuncio=$id_anuncio")) {
            header("Location: anuncios.php");
            exit;
        } else {
            $mensaje = "<p class='mensaje-error'>Error al eliminar el anuncio</p>" . $conexion->error;
        }
    } else {
        $mensaje = "<p class='mensaje-error'>No tienes permiso para eliminar este anuncio</p>";
    }
}

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>


<main class="main">
    <h1>Gestión de anuncios</h1>

    <a href="crear_anuncio.php" class="btn-crear">+ Crear anuncio</a>
    <div class="filtro">
        <form method="GET">
            <label for="filtro-asignatura">Filtrar por asignatura:</label>
            <select name="id_asignatura" id="filtro-asignatura" onchange="this.form.submit()">
                <option value="">-- Todas las asignaturas --</option>

                <?php
                foreach ($asignaturas as $asig) {
                    $selected = ($id_asignatura_seleccionada == $asig['id_asignatura']) ? "selected" : "";
                ?>
                    <option value="<?= $asig['id_asignatura'] ?>" <?= $selected ?>>
                        <?= $asig['nombre'] ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <?php if (!empty($mensaje)) {
        echo $mensaje;
    }

    if ($anuncios->num_rows == 0) {
        echo "<p>No hay anuncios disponibles.</p>";
    } else {
    ?>

        <div class="anuncios">

            <table class="tabla-anuncios">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Contenido</th>
                        <th>Asignatura</th>
                        <th>Fecha publicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($anuncio = $anuncios->fetch_assoc()) { ?>
                        <tr>
                            <td class="titulo"><?= $anuncio['titulo'] ?></td>
                            <td class="contenido"><?= $anuncio['contenido'] ?></td>
                            <td><?= $anuncio['asignatura_nombre'] ?></td>
                            <td><?= date("d/m/Y", strtotime($anuncio['fecha_publicacion'])) ?></td>
                            <td>
                                <a href="anuncios.php?eliminar=<?= $anuncio['id_anuncio'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</main>


<?php
desconectar($conexion);
include "../plantillas/footer_privado.php";
?>