
<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: cursos.php");
    exit;
}

$curso = $conexion->query("SELECT * FROM cursos WHERE id_curso=$id");
if ($curso->num_rows == 0) {
    header("Location: cursos.php");
    exit;
}

$datos = $curso->fetch_assoc();

if (isset($_POST['editar'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $curso_academico = trim($_POST['curso_academico']);
    $plazas = $_POST['plazas'];
    $precio = $_POST['precio'];

    if (empty($nombre) || empty($curso_academico) || empty($plazas) || empty($precio)) {
        $mensaje = "Rellena los campos obligatorios";
    } else {
        $sql = "UPDATE cursos SET nombre='$nombre', descripcion='$descripcion', 
                curso_academico='$curso_academico', plazas=$plazas, precio=$precio WHERE id_curso=$id";
        
        if ($conexion->query($sql)) {
            $mensaje = "Curso actualizado correctamente";
            header("Location: cursos.php");
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
    <h1>Editar curso</h1>
    
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre del curso" value="<?= $datos['nombre'] ?>" required>
        <textarea name="descripcion" placeholder="Descripción del curso" rows="4"><?= $datos['descripcion'] ?></textarea>
        <input type="text" name="curso_academico" placeholder="Curso académico (ej: 2024-2025)" value="<?= $datos['curso_academico'] ?>" required>
        <input type="number" name="plazas" placeholder="Número de plazas" value="<?= $datos['plazas'] ?>" required>
        <input type="number" name="precio" placeholder="Precio (ej: 50.00)" value="<?= $datos['precio'] ?>" step="0.01" required>
        
        <button type="submit" name="editar">Actualizar curso</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>