<?php
session_start();

require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

$conexion = conectar();

// ELIMINAR RECURSO 
if (isset($_GET['eliminar'])) {

    $id = (int)$_GET['eliminar'];
    $id_profesor = $_SESSION['id_usuario'];

    $conexion->query("DELETE FROM recursos
                      WHERE id_recurso = $id
                      AND publicado_por = $id_profesor");

    $_SESSION['mensaje'] = "
        <p class='mensaje_exito'>
            Recurso eliminado correctamente
        </p>
    ";

    header("Location: recursos.php");
    exit;
}

$id_profesor = $_SESSION['id_usuario'];
$mensaje = "";
$id_asignatura_seleccionada = 0;
if (isset($_GET['id_asignatura']) && !empty($_GET['id_asignatura'])) {
    $id_asignatura_seleccionada = (int)$_GET['id_asignatura'];
}

// OBTENER ASIGNATURAS DEL PROFESOR
$consulta_asignaturas = $conexion->query("SELECT id_asignatura, nombre
                                            FROM asignaturas
                                            WHERE id_profesor = $id_profesor
                                            ORDER BY nombre ASC");
$asignaturas = [];

while ($fila = $consulta_asignaturas->fetch_assoc()) {
    $asignaturas[] = $fila;
}


// SUBIR RECURSO
if (isset($_POST['subir'])) {

    $titulo = trim($_POST['titulo']);
    $id_asignatura = (int)$_POST['id_asignatura'];
    $id_asignatura_seleccionada = $id_asignatura;

    if (empty($titulo) || empty($id_asignatura)) {
        $mensaje = "<p class='mensaje_error'> Rellena todos los campos </p>";
    } elseif (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != 0) {

        $mensaje = "
            <p class='mensaje_error'>
                Debes seleccionar un archivo
            </p>
        ";
    } else {

        $nombre_archivo = time() . "_" . basename($_FILES['archivo']['name']);
        $ruta_destino = "../subidas/recursos/" . $nombre_archivo;

        if (!is_dir("../subidas/recursos")) {
            mkdir("../subidas/recursos", 0777, true);
        }

        $extensiones_permitidas = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip'];

        $extension = explode('.', $_FILES['archivo']['name']);
        $extension = strtolower(end($extension));

        if (!in_array($extension, $extensiones_permitidas)) {

            $mensaje = "<p class='mensaje_error'>Tipo de archivo no permitido</p>";
        } else {

            if ($_FILES['archivo']['size'] > 10 * 1024 * 1024) {
                $mensaje = "<p class='mensaje_error'>El archivo es demasiado grande (máximo 10MB)</p>";
            } else {

                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
                    $sql = "INSERT INTO recursos (titulo, archivo, id_asignatura, publicado_por, fecha_subida)
                    VALUES ('$titulo', '$nombre_archivo', $id_asignatura, $id_profesor, NOW())";

                    if ($conexion->query($sql)) {
                        header("Location: recursos.php");
                        exit;
                    } else {

                        $mensaje = "<p class='mensaje_error'>Error al guardar el recurso</p>";
                    }
                } else {

                    $mensaje = "<p class='mensaje_error'>Error al subir el archivo</p>";
                }
            }
        }
    }
}


// LISTAR RECURSOS
$filtro_recursos = "";
if ($id_asignatura_seleccionada > 0) {
    $filtro_recursos = " AND r.id_asignatura = $id_asignatura_seleccionada";
}
$recursos = $conexion->query(" SELECT r.*, a.nombre AS asignatura_nombre
                                FROM recursos r
                                JOIN asignaturas a ON r.id_asignatura = a.id_asignatura
                                WHERE a.id_profesor = $id_profesor $filtro_recursos
                                ORDER BY r.fecha_subida DESC ");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">

    <?php botonVolver(); ?>

    <?php if (!empty($mensaje)) {
        echo $mensaje;
    } ?>

    <div id="form-recurso" style="display:none;">
        <form method="POST" enctype="multipart/form-data">

            <h2>Subir recurso</h2>

            Título del recurso: <input type="text" name="titulo" required>

            Asignatura: <select name="id_asignatura" required>

                <option value=""> -- Selecciona asignatura -- </option>

                <?php foreach ($asignaturas as $asig) {
                    $selected = ($id_asignatura_seleccionada == $asig['id_asignatura']) ? 'selected' : '';
                ?>
                    <option value="<?= $asig['id_asignatura'] ?>" <?= $selected ?>> <?= $asig['nombre'] ?> </option>

                <?php } ?>
            </select>

            <input type="file" name="archivo" required>
            <button type="submit" name="subir"> Subir recurso</button>
        </form>

    </div>

<div>
    <button type="button" id="toggle-form" class="btn-crear">
        + Subir recurso
    </button>
</div>

    <h1>Recursos</h1>

    <?php if ($recursos->num_rows > 0) { ?>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Asignatura</th>
                    <th>Fecha</th>
                    <th>Archivo</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($recurso = $recursos->fetch_assoc()) { ?>
                    <tr>
                        <td> <?= $recurso['titulo'] ?> </td>
                        <td> <?= $recurso['asignatura_nombre'] ?> </td>
                        <td> <?= date("d/m/Y", strtotime($recurso['fecha_subida'])) ?> </td>
                        <td>

                            <?php

                            $archivo = $recurso['archivo'];

                            // COMPROBAR SI ES UNA URL EXTERNA
                            if (filter_var($archivo, FILTER_VALIDATE_URL)) {

                            ?>

                                <!-- ENLACE EXTERNO -->
                                <a href="<?= $archivo ?>" target="_blank" class="btn-editar">
                                    Abrir enlace
                                </a>

                            <?php } else { ?>

                                <!-- ARCHIVO SUBIDO -->
                                <a href="../subidas/recursos/<?= urlencode($archivo) ?>" target="_blank" class="btn-editar">
                                    Abrir recurso
                                </a>

                            <?php } ?>

                            <a href="?eliminar=<?= $recurso['id_recurso'] ?>"
                                class="btn-eliminar"
                                onclick="return confirm('¿Eliminar recurso?')">
                                Eliminar
                            </a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <p>No hay recursos disponibles.</p>

    <?php } ?>

</main>

<script>
    const boton = document.getElementById("toggle-form");
    const form = document.getElementById("form-recurso");

    boton.addEventListener("click", function() {

        if (form.style.display === "none") {

            form.style.display = "block";
            boton.textContent = "Cancelar";

        } else {

            form.style.display = "none";
            boton.textContent = "+ Subir recurso";
        }
    });
</script>

<?php
desconectar($conexion);
include "../plantillas/footer_privado.php";
?>