
<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";

if (isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $curso_academico = trim($_POST['curso_academico']);
    $plazas = $_POST['plazas'];
    $precio = $_POST['precio'];

    if (empty($nombre) || empty($curso_academico) || empty($plazas) || empty($precio)) {
        $mensaje = "Rellena los campos obligatorios";
    } else {
        $existe = $conexion->query("SELECT id_curso FROM cursos WHERE nombre='$nombre'");
        if ($existe->num_rows > 0) {
            $mensaje = "El curso ya existe";
        } else {
            $sql = "INSERT INTO cursos (nombre, descripcion, curso_academico, plazas, precio) 
                    VALUES ('$nombre', '$descripcion', '$curso_academico', $plazas, $precio)";
            
            if ($conexion->query($sql)) {
                $mensaje = "Curso creado correctamente";
                header("Location: cursos.php");
                exit;
            } else {
                $mensaje = "Error al crear el curso";
            }
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Crear curso</h1>
    
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre del curso" required>
        <textarea name="descripcion" placeholder="Descripción" rows="4"></textarea>
        <input type="text" name="curso_academico" placeholder="Curso académico (ej: 2024-2025)" required>
        <input type="number" name="plazas" placeholder="Plazas disponibles" required>
        <input type="number" name="precio" placeholder="Precio" step="0.01" required>
        
        <button type="submit" name="crear">Crear curso</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>