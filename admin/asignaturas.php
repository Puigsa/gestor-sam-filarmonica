<?php

require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("admin");

$conexion = conectar();

$mensaje = "";

$total_registros = $conexion->query("SELECT COUNT(*) AS total FROM asignaturas")->fetch_assoc()['total'];

$paginacion = paginar($total_registros, 5);

// ASIGNAR PROFESOR
if (isset($_POST['asignar'])) {

    $id_asignatura = (int)$_POST['id_asignatura'];

    $id_profesor = (int)$_POST['id_profesor'];

    $sql = "UPDATE asignaturas
            SET id_profesor = $id_profesor
            WHERE id_asignatura = $id_asignatura";

    if ($conexion->query($sql)) {

        $mensaje = "<p class='mensaje_exito'> Profesor asignado correctamente </p>";
    } else {

        $mensaje = "<p class='mensaje_error'> Error al asignar profesor </p>";
    }
}


// LISTAR ASIGNATURAS
$asignaturas = $conexion->query("SELECT a.id_asignatura, a.nombre, c.nombre AS curso, u.nombre AS profesor
                                    FROM asignaturas a
                                    JOIN cursos c ON a.id_curso = c.id_curso
                                    LEFT JOIN usuarios u ON a.id_profesor = u.id_usuario
                                    ORDER BY c.nombre, a.nombre
                                    LIMIT {$paginacion['limite']} OFFSET {$paginacion['offset']}");


// PROFESORES
$profesores = $conexion->query(" SELECT id_usuario, nombre, apellidos
                                    FROM usuarios
                                    WHERE rol = 'profesor'
                                    ORDER BY nombre ASC");


include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";

?>

<main class="main">

    <h1>Asignaturas</h1>

    <?= $mensaje ?>

    <table>
        <thead>
            <tr>
                <th>Asignatura</th>
                <th class="ocultar-mobile">Curso</th>
                <th>Profesor</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($asignatura = $asignaturas->fetch_assoc()) { ?>

                <tr>
                    <td> <?= $asignatura['nombre'] ?> </td>
                    <td class="ocultar-mobile"> <?= $asignatura['curso'] ?> </td>
                    <td>
                        <?= $asignatura['profesor'] ?: "Sin asignar" ?>
                    </td>
                    <td>

                        <form method="POST" class="filtro">
                            <input type="hidden" name="id_asignatura" value="<?= $asignatura['id_asignatura'] ?>">

                            <select name="id_profesor" required>

                                <option value="">-- Profesor --</option>

                                <?php

                                $profesores->data_seek(0);
                                while ($profesor = $profesores->fetch_assoc()) { ?>

                                    <option value="<?= $profesor['id_usuario'] ?>">
                                        <?= $profesor['nombre'] ?>
                                        <?= $profesor['apellidos'] ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <button type="submit" name="asignar" class="btn-editar"> Asignar profesor </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas']); ?>
</main>

<?php

desconectar($conexion);

include "../plantillas/footer_privado.php";

?>