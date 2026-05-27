<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$cursos = $conexion->query("SELECT id_curso, nombre, descripcion, curso_academico, plazas, precio FROM cursos ORDER BY nombre ASC");
if (!$cursos) {
    die("Error SQL: " . $conexion->error);
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Gestión de Cursos</h1>
    
    <a href="crear_curso.php" class="btn-crear">+ Crear curso</a>
    
    <table class="tabla-cursos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Curso</th>
                <th>Plazas</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($cursos->num_rows == 0) { ?>
                <tr>
                    <td colspan="6">No hay cursos disponibles</td>
                </tr>
            <?php } while ($curso = $cursos->fetch_assoc()) { ?>
                <tr>
                    <td><?= $curso['nombre'] ?></td>
                    <td><?= $curso['descripcion'] ?></td>
                    <td><?= $curso['curso_academico'] ?></td>
                    <td><?= $curso['plazas'] ?></td>
                    <td><?= $curso['precio'] ?>€</td>
                    <td>
                        <a href="editar_curso.php?id=<?= $curso['id_curso'] ?>" class="btn-editar">Editar</a>
                        <a href="eliminar_curso.php?id=<?= $curso['id_curso'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<?php include "../plantillas/footer_privado.php"; ?>

