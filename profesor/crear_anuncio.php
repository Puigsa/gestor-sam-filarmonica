<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

$conexion = conectar();
$id_profesor = $_SESSION['id_usuario'];
$mensaje = "";

?>

<?php

if (isset($_POST['crear'])) {
    $titulo = trim($_POST['titulo']);
    $contenido = trim($_POST['contenido']);
    $id_asignatura = (int)$_POST['asignatura'];

    $consulta = $conexion->query("SELECT id_curso FROM asignaturas WHERE id_asignatura = $id_asignatura");
    $datos = $consulta->fetch_assoc();
    $id_curso = (int)$datos['id_curso'];

    if (empty($titulo) || empty($contenido) || empty($id_asignatura)) {
        $mensaje = "Rellena todos los campos";
    } else {

        $sql = "INSERT INTO anuncios (id_curso, id_asignatura, id_profesor, titulo, contenido, fecha_publicacion) VALUES ($id_curso, $id_asignatura, $id_profesor, '$titulo', '$contenido', NOW())";
        if ($conexion->query($sql)) {
            header("Location: anuncios.php");
            exit;
        } else {
            $mensaje = "<p class='mensaje_error'>Error al crear el anuncio: " . $conexion->error . "</p>";
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <h1>Crear anuncio</h1>

    <form action="crear_anuncio.php" method="POST">

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="contenido">Contenido:</label>
        <textarea id="contenido" name="contenido" rows="5" required></textarea>

        <label for="asignatura">Asignatura:</label>
        <select id="asignatura" name="asignatura" required>
            <option value="">Selecciona una asignatura</option>
            <?php
            $conexion = conectar();


            $asignaturas = $conexion->query("SELECT id_asignatura, nombre FROM asignaturas where id_profesor = $id_profesor ORDER BY nombre ASC");
            while ($asignatura = $asignaturas->fetch_assoc()) {
                echo "<option value='" . $asignatura['id_asignatura'] . "'>" . $asignatura['nombre'] . "</option>";
            }
            ?>
        </select>

            <button type="submit" name="crear">Crear anuncio</button>

    </form>
    <?php if (!empty($mensaje)) {
        echo $mensaje;
    } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>