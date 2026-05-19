<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: eventos.php");
    exit;
}

$evento = $conexion->query("SELECT * FROM eventos WHERE id_evento=$id");
if ($evento->num_rows == 0) {
    header("Location: eventos.php");
    exit;
}

$datos = $evento->fetch_assoc();

if (isset($_POST['editar'])) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $lugar = trim($_POST['lugar']);
    $publicado = isset($_POST['publicado']) ? 1 : 0;
    $cartel = trim($_POST['cartel']);

    if (empty($titulo) || empty($fecha) || empty($hora) || empty($lugar)) {
        $mensaje = "Rellena los campos obligatorios";
    } else {
        $sql = "UPDATE eventos SET titulo='$titulo', descripcion='$descripcion', fecha='$fecha', 
                hora='$hora', lugar='$lugar', publicado=$publicado, cartel='$cartel' WHERE id=$id";
        
        if ($conexion->query($sql)) {
            $mensaje = "Evento actualizado correctamente";
            header("Location: eventos.php");
            exit;
        } else {
            $mensaje = "Error al actualizar";
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <h1>Editar evento</h1>
    
    <form method="POST">
        <input type="text" name="titulo" value="<?= $datos['titulo'] ?>" required>
        <textarea name="descripcion" rows="4"><?= $datos['descripcion'] ?></textarea>
        <input type="date" name="fecha" value="<?= $datos['fecha'] ?>" required>
        <input type="time" name="hora" value="<?= $datos['hora'] ?>" required>
        <input type="text" name="lugar" value="<?= $datos['lugar'] ?>" required>
        
        <input type="text" name="cartel" value="<?= $datos['cartel'] ?>" placeholder="URL del cartel">
        
        <label>
            <input type="checkbox" name="publicado" <?= $datos['publicado'] ? 'checked' : '' ?>> Publicar
        </label>
        
        <button type="submit" name="editar">Actualizar evento</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>