
<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";
$id = $_GET['id'] ?? null;

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

// Obtener instrumentos para el select
$instrumentos = $conexion->query("SELECT id_instrumento, nombre FROM instrumentos");

if (isset($_POST['editar'])) {
    $id_curso = $_POST['id_curso'];
    $id_instrumento = $_POST['id_instrumento'];
    $estado = $_POST['estado'];
    $observaciones = trim($_POST['observaciones']);

    $sql = "UPDATE matriculas SET id_curso=$id_curso, id_instrumento=$id_instrumento, estado='$estado', observaciones='$observaciones' WHERE id_matricula=$id";
    
    if ($conexion->query($sql)) {
        $mensaje = "Matrícula actualizada correctamente";
        header("Location: matriculas.php");
        exit;
    } else {
        $mensaje = "Error al actualizar";
    }
}

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <h1>Editar matrícula</h1>
    
    <form method="POST">
        <select name="id_instrumento" required>
            <option value="">Selecciona instrumento</option>
            <?php 
            $conexion = conectar();
            $instrumentos = $conexion->query("SELECT id_instrumento, nombre FROM instrumentos");
            while ($inst = $instrumentos->fetch_assoc()) { 
            ?>
                <option value="<?= $inst['id_instrumento'] ?>" <?= $inst['id_instrumento'] == $datos['id_instrumento'] ? 'selected' : '' ?>>
                    <?= $inst['nombre'] ?>
                </option>
            <?php } 
            ?>
        </select>

        <select name="id_curso" required>
            <option value="">Selecciona curso: </option>
            <?php
            $cursos = $conexion->query("SELECT id_curso, nombre FROM cursos");
            while ($curso = $cursos->fetch_assoc()) {
            ?>
                <option value="<?= $curso['id_curso'] ?>" <?= $curso['id_curso'] == $datos['id_curso'] ? 'selected' : '' ?>>
                    <?= $curso['nombre'] ?>
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
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>
