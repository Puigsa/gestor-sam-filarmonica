<?php
session_start();

require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// Prematrículas pendientes (paginar separadamente)
$total_pre_result = $conexion->query("SELECT COUNT(*) AS total FROM prematriculas WHERE estado='pendiente'");
$total_pre = $total_pre_result->fetch_assoc()['total'];
$paginacion_pre = paginar($total_pre, 5, 'pagina_prematriculas');
$prematriculas = $conexion->query("SELECT * FROM prematriculas WHERE estado='pendiente' ORDER BY fecha_solicitud DESC LIMIT {$paginacion_pre['offset']}, {$paginacion_pre['limite']}");

// Matrículas formalizadas
$estado = $_GET['estado'] ?? 'activa';
// TOTAL
$total_result = $conexion->query("SELECT COUNT(*) AS total FROM matriculas WHERE estado='$estado'");
$total = $total_result->fetch_assoc()['total'];

// PAGINACIÓN
$paginacion = paginar($total, 3);

$matriculas = $conexion->query("SELECT m.*, u.nombre, u.apellidos, c.nombre as curso_nombre, i.nombre as instrumento_nombre 
                                FROM matriculas m 
                                JOIN usuarios u ON m.id_alumno = u.id_usuario 
                                JOIN cursos c ON m.id_curso = c.id_curso 
                                JOIN instrumentos i ON m.id_instrumento = i.id_instrumento 
                                WHERE m.estado='$estado' 
                                ORDER BY m.fecha_matricula DESC
                                LIMIT {$paginacion['offset']}, {$paginacion['limite']}");

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
    <section>
        <h2>Prematrículas pendientes</h2>

        <?php if ($prematriculas->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="ocultar-mobile">Email</th>
                        <th class="ocultar-mobile">Teléfono</th>
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
                            <td class="ocultar-mobile"><?= $pre['email'] ?></td>
                            <td class="ocultar-mobile"><?= $pre['telefono'] ?></td>
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
            <?php mostrarPaginacion($paginacion_pre['pagina'], $paginacion_pre['total_paginas'], [], 'pagina_prematriculas'); ?>
        <?php } else { ?>
            <p>No hay prematrículas pendientes</p>
        <?php } ?>
    </section>

    <!-- MATRÍCULAS -->

    <section>

        <h2>Matrículas formalizadas</h2>

        
        <form method="GET">
            <label for="estado">Filtrar por estado:</label>
            <select name="estado" id="estado" onchange="this.form.submit()">
                <option value="activa" <?= $estado == 'activa' ? 'selected' : '' ?>>Activas</option>
                <option value="finalizada" <?= $estado == 'finalizada' ? 'selected' : '' ?>>Finalizadas</option>
                <option value="cancelada" <?= $estado == 'cancelada' ? 'selected' : '' ?>>Canceladas</option>
            </select>
        </form>
        <a href="exportar_matriculas_pdf.php?estado=<?= $estado ?>" class="btn-crear"> Exportar PDF </a>


        <?php if ($matriculas->num_rows > 0) { ?>
            <table class="tabla-matriculas">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Instrumento</th>
                        <th>Fecha matrícula</th>
                        <th class="ocultar-mobile">Estado</th>
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
                            <td class="ocultar-mobile"><?= ucfirst($mat['estado']) ?></td>
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
    <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas'], [
        'estado' => $estado
    ]); ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>