
<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";

if (isset($_POST['crear'])) {
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
        $sql = "INSERT INTO eventos (titulo, descripcion, fecha, hora, lugar, publicado, cartel) 
                VALUES ('$titulo', '$descripcion', '$fecha', '$hora', '$lugar', $publicado, '$cartel')";
        
        if ($conexion->query($sql)) {
            $mensaje = "Evento creado correctamente";
            header("Location: eventos.php");
            exit;
        } else {
            $mensaje = "Error al crear el evento";
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Crear evento</h1>
    
    <form method="POST">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descripcion" rows="4" placeholder="Descripción"></textarea>
        <input type="date" name="fecha" required>
        <input type="time" name="hora" required>
        <input type="text" name="lugar" placeholder="Lugar" required>
        
        
        <input type="text" name="cartel" placeholder="URL del cartel">
        
        <label>
            <input type="checkbox" name="publicado"> Publicar
        </label>
        
        <button type="submit" name="crear">Crear evento</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>