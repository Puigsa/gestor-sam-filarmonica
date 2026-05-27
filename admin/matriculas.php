<?php
session_start();

require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// Prematrículas pendientes
$prematriculas = $conexion->query("SELECT * FROM prematriculas WHERE estado='pendiente' ORDER BY fecha_solicitud DESC");

// Matrículas formalizadas
$estado = $_GET['estado'] ?? 'activa';
$matriculas = $conexion->query("SELECT m.*, u.nombre, u.apellidos, c.nombre as curso_nombre, i.nombre as instrumento_nombre 
                                FROM matriculas m 
                                JOIN usuarios u ON m.id_alumno = u.id_usuario 
                                JOIN cursos c ON m.id_curso = c.id_curso 
                                JOIN instrumentos i ON m.id_instrumento = i.id_instrumento 
                                WHERE m.estado='$estado' 
                                ORDER BY m.fecha_matricula DESC");

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";

if (isset($_SESSION['mensaje_exito'])) {
    echo "<p class='mensaje_exito'>" . $_SESSION['mensaje_exito'] . "</p>";
    unset($_SESSION['mensaje_exito']);
}
?>



<main class="main">
    <?php botonVolver(); ?>
    <h1>Gestión de Matrículas</h1>

    <!-- PREMATRÍCULAS -->
    <section class="seccion-prematriculas">
        <h2>Prematrículas pendientes</h2>

        <?php if ($prematriculas->num_rows > 0) { ?>
            <table class="tabla-matriculas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Curso</th>
                        <th>Instrumento</th>
                        <th>Fecha solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pre = $prematriculas->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $pre['nombre'] . ' ' . $pre['apellidos'] ?></td>
                            <td><?= $pre['email'] ?></td>
                            <td><?= $pre['telefono'] ?></td>
                            <td><?= $pre['id_curso'] ?></td>
                            <td><?= $pre['id_instrumento'] ?></td>
                            <td><?= date('d/m/Y', strtotime($pre['fecha_solicitud'])) ?></td>
                            <td>
                                <a href="aprobar_prematricula.php?id=<?= $pre['id_prematricula'] ?>" class="btn-aprobar">Aprobar</a>
                                <a href="rechazar_prematricula.php?id=<?= $pre['id_prematricula'] ?>" class="btn-rechazar">Rechazar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No hay prematrículas pendientes</p>
        <?php } ?>
    </section>

    <!-- MATRÍCULAS -->

    <section class="seccion-matriculas">

        <h2>Matrículas formalizadas</h2>

        <form method="GET">
            <label for="estado">Filtrar por estado:</label>
            <select name="estado" id="estado" onchange="this.form.submit()">
                <option value="activa" <?= $estado == 'activa' ? 'selected' : '' ?>>Activas</option>
                <option value="finalizada" <?= $estado == 'finalizada' ? 'selected' : '' ?>>Finalizadas</option>
                <option value="cancelada" <?= $estado == 'cancelada' ? 'selected' : '' ?>>Canceladas</option>
            </select>
        </form>

        <?php if ($matriculas->num_rows > 0) { ?>
            <table class="tabla-matriculas">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Instrumento</th>
                        <th>Fecha matrícula</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($mat = $matriculas->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $mat['nombre'] . ' ' . $mat['apellidos'] ?></td>
                            <td><?= $mat['curso_nombre'] ?></td>
                            <td><?= $mat['instrumento_nombre'] ?></td>
                            <td><?= date('d/m/Y', strtotime($mat['fecha_matricula'])) ?></td>
                            <td><?= ucfirst($mat['estado']) ?></td>
                            <td>
                                <a href="editar_matricula.php?id=<?= $mat['id_matricula'] ?>" class="btn-editar">Editar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No hay matrículas formalizadas</p>
        <?php } ?>
    </section>
</main>

<?php include "../plantillas/footer_privado.php"; ?>