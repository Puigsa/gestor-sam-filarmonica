<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("admin");

$conexion = conectar();
$mensaje = "";


$anuncios = $conexion->query("SELECT a.*, u.nombre AS profesor_nombre, c.nombre AS asignatura_nombre 
                                FROM anuncios a 
                                JOIN usuarios u ON a.id_profesor=u.id_usuario 
                                JOIN asignaturas c ON a.id_asignatura=c.id_asignatura 
                                ORDER BY a.fecha_publicacion DESC");

if(isset($_GET['eliminar'])) {
    $id_anuncio = (int)$_GET['eliminar'];
    if($conexion->query("DELETE FROM anuncios WHERE id_anuncio=$id_anuncio")) {
        header("Location: anuncios.php");
        exit;
    } else {
        $mensaje = "<p class='mensaje-error'>Error al eliminar el anuncio</p>" . $conexion->error;
    }
}
desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>


<main class="main">
    <h1>Gestión de anuncios</h1>
    <a href="crear_anuncio.php" class="btn-crear">+ Crear anuncio</a>

    <?php if (!empty($mensaje)){
        echo $mensaje;
    }?>

    <div class="anuncios">

    <table class="tabla-anuncios">
        <thead>
            <tr>
                <th>Título</th>
                <th>Contenido</th>
                <th>Asignatura</th>
                <th>Puplicado por</th>
                <th>Fecha publicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($anuncio = $anuncios->fetch_assoc()) { ?>
                <tr>
                    <td><?= $anuncio['titulo'] ?></td>
                    <td><?= $anuncio['contenido'] ?></td>
                    <td><?= $anuncio['asignatura_nombre'] ?></td>
                    <td><?= $anuncio['profesor_nombre'] ?></td>
                    <td><?= date("d/m/Y", strtotime($anuncio['fecha_publicacion'])) ?></td>
                    <td>
                        <a href="anuncios.php?eliminar=<?= $anuncio['id_anuncio'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<?php include "../plantillas/footer_privado.php"; ?>