<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: matriculas.php");
    exit;
}

$matricula = $conexion->query("SELECT * FROM matriculas WHERE id_matricula=$id");
if ($matricula->num_rows == 0) {
    header("Location: matriculas.php");
    exit;
}

$datos = $matricula->fetch_assoc();

// Cargar datos para los selects
$instrumentos = $conexion->query("SELECT id_instrumento, nombre FROM instrumentos");
$cursos = $conexion->query("SELECT id_curso, nombre FROM cursos");

if (isset($_POST['editar'])) {
    $id_curso = (int)($_POST['id_curso']);
    $id_instrumento = (int)($_POST['id_instrumento']);
    $estado = $_POST['estado'];
    $observaciones = trim($_POST['observaciones']);

    $sql = "UPDATE matriculas SET id_curso=$id_curso, id_instrumento=$id_instrumento, estado='$estado', observaciones='$observaciones' WHERE id_matricula=$id";

    if ($conexion->query($sql)) {
    $curso_cambio = ($id_curso != $datos['id_curso']);
    $estado_cambio = ($estado != $datos['estado']);

    // Si cambió de curso
    if ($curso_cambio) {
        // Sumar plaza al curso anterior
        $conexion->query("UPDATE cursos SET plazas = plazas + 1 WHERE id_curso = {$datos['id_curso']}");
        
        // Comprobar plazas en nuevo curso
        $plazas = $conexion->query("SELECT plazas FROM cursos WHERE id_curso = $id_curso")->fetch_assoc()['plazas'];
        if ($plazas <= 0) {
            // Revertir cambio
            $conexion->query("UPDATE matriculas SET id_curso = {$datos['id_curso']} WHERE id_matricula = $id");
            $_SESSION['mensaje_error'] = "No hay plazas disponibles en el nuevo curso";
            desconectar($conexion);
            header("Location: matriculas.php");
            exit;
        }
        // Restar plaza del nuevo curso
        $conexion->query("UPDATE cursos SET plazas = plazas - 1 WHERE id_curso = $id_curso");
    }

    // Si cambió a cancelada, sumar plaza
    if ($estado == 'cancelada' && $datos['estado'] != 'cancelada') {
        $conexion->query("UPDATE cursos SET plazas = plazas + 1 WHERE id_curso = $id_curso");
    }

    // Si cambió a activa desde cancelada, restar plaza
    if ($estado == 'activa' && $datos['estado'] == 'cancelada') {
        $plazas = $conexion->query("SELECT plazas FROM cursos WHERE id_curso = $id_curso")->fetch_assoc()['plazas'];
        if ($plazas > 0) {
            $conexion->query("UPDATE cursos SET plazas = plazas - 1 WHERE id_curso = $id_curso");
        } else {
            $_SESSION['mensaje_error'] = "No hay plazas disponibles";
            desconectar($conexion);
            header("Location: matriculas.php");
            exit;
        }
    }

    $_SESSION['mensaje_exito'] = "Matrícula actualizada correctamente";
    desconectar($conexion);
    header("Location: matriculas.php");
    exit;
}

}

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <h1>Editar matrícula</h1>

    <form method="POST">
        <select name="id_curso" required>
            <option value="">Selecciona curso</option>
            <?php 
            $cursos->data_seek(0);
            while ($curso = $cursos->fetch_assoc()) { ?>
                <option value="<?= $curso['id_curso'] ?>" <?= $curso['id_curso'] == $datos['id_curso'] ? 'selected' : '' ?>>
                    <?= $curso['nombre'] ?>
                </option>
            <?php } ?>
        </select>

        <select name="id_instrumento" required>
            <option value="">Selecciona instrumento</option>
            <?php 
            $instrumentos->data_seek(0);
            while ($inst = $instrumentos->fetch_assoc()) { ?>
                <option value="<?= $inst['id_instrumento'] ?>" <?= $inst['id_instrumento'] == $datos['id_instrumento'] ? 'selected' : '' ?>>
                    <?= $inst['nombre'] ?>
                </option>
            <?php } ?>
        </select>

        <select name="estado" required>
            <option value="activa" <?= $datos['estado'] == 'activa' ? 'selected' : '' ?>>Activa</option>
            <option value="finalizada" <?= $datos['estado'] == 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
            <option value="cancelada" <?= $datos['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select>

        <textarea name="observaciones" rows="4" placeholder="Observaciones"><?= $datos['observaciones'] ?></textarea>

        <button type="submit" name="editar">Actualizar matrícula</button>
    </form>
</main>

<?php include "../plantillas/footer_privado.php"; ?>
