<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("alumno");

$id_alumno = $_SESSION['id_usuario'];
$id_asignatura_seleccionada = 0;
if (isset($_GET['id_asignatura']) && !empty($_GET['id_asignatura'])) {
    $id_asignatura_seleccionada = (int)$_GET['id_asignatura'];
}

$conexion = conectar();

// Obtener asignaturas donde el alumno está matriculado activamente
$asignaturas = $conexion->query("SELECT DISTINCT asi.id_asignatura, asi.nombre
                                FROM asignaturas asi
                                JOIN matriculas m ON asi.id_curso = m.id_curso
                                WHERE m.id_alumno = $id_alumno AND m.estado = 'activa'
                                ORDER BY asi.nombre ASC");

$filtro_asignatura = "";
if ($id_asignatura_seleccionada > 0) {
    $filtro_asignatura = " AND r.id_asignatura = $id_asignatura_seleccionada";
}

$recursos = $conexion->query("SELECT r.*, asi.nombre AS asignatura_nombre, u.nombre AS profesor_nombre
                                FROM recursos r
                                JOIN asignaturas asi ON r.id_asignatura = asi.id_asignatura
                                JOIN matriculas m ON asi.id_curso = m.id_curso
                                JOIN usuarios u ON r.publicado_por = u.id_usuario
                                WHERE m.id_alumno = $id_alumno AND m.estado = 'activa' $filtro_asignatura
                                ORDER BY r.fecha_subida DESC");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Recursos</h1>

    <form method="GET" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <label for="filtro-asignatura">Filtrar por asignatura:</label>
        <select name="id_asignatura" id="filtro-asignatura" onchange="this.form.submit()">
            <option value="">-- Todas las asignaturas --</option>
            <?php while ($asig = $asignaturas->fetch_assoc()) {
                $selected = ($id_asignatura_seleccionada == $asig['id_asignatura']) ? 'selected' : '';
            ?>
                <option value="<?= $asig['id_asignatura'] ?>" <?= $selected ?>><?= $asig['nombre'] ?></option>
            <?php } ?>
        </select>
        <noscript><button type="submit">Filtrar</button></noscript>
    </form>

    <?php if ($recursos && $recursos->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Asignatura</th>
                    <th>Profesor</th>
                    <th>Fecha</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($recurso = $recursos->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($recurso['titulo']) ?></td>
                        <td><?= htmlspecialchars($recurso['asignatura_nombre']) ?></td>
                        <td><?= htmlspecialchars($recurso['profesor_nombre']) ?></td>
                        <td><?= date("d/m/Y", strtotime($recurso['fecha_subida'])) ?></td>
                        <td>
                            <?php
                            $archivo = $recurso['archivo'];
                            if (filter_var($archivo, FILTER_VALIDATE_URL)) {
                            ?>

                                <a href="<?= $archivo ?>" target="_blank" class="btn-editar">
                                    Abrir enlace
                                </a>

                            <?php } else { ?>

                                <a href="../subidas/recursos/<?= urlencode($archivo) ?>" target="_blank" class="btn-editar">
                                    Abrir recurso
                                </a>

                            <?php } ?>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay recursos disponibles.</p>
    <?php } ?>
</main>

<?php
desconectar($conexion);
include "../plantillas/footer_privado.php";
?>