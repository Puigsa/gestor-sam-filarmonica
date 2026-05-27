<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: usuarios.php");
    exit;
}

$usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario=$id");
if ($usuario->num_rows == 0) {
    header("Location: usuarios.php");
    exit;
}

$datos = $usuario->fetch_assoc();

if (isset($_POST['editar'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = trim($_POST['direccion']);
    $rol = $_POST['rol'];

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($rol)) {
        $mensaje_error = "<p class='mensaje_error'>Rellena los campos obligatorios</p>";
    } else {
        $sql = "UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', email='$email', 
                telefono='$telefono', dni='$dni', fecha_nacimiento='$fecha_nacimiento', 
                direccion='$direccion', rol='$rol' WHERE id_usuario=$id";

        if ($conexion->query($sql)) {
            $_SESSION['mensaje'] = "<p class='mensaje_exito'>Usuario actualizado correctamente</p>";
            header("Location: usuarios.php");
            exit;
        } else {
            $mensaje_error = "<p class='mensaje_error'>Error al actualizar</p>" . $conexion->error;
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Editar usuario</h1>

    <form method="POST" class="formulario-usuario" id="formEditar">

        Nombre: <input type="text" name="nombre" id="nombre" value="<?= $datos['nombre'] ?>" required>
        <span class="error-campo" id="error-nombre"></span>

        Apellidos: <input type="text" name="apellidos" id="apellidos" value="<?= $datos['apellidos'] ?>" required>
        <span class="error-campo" id="error-apellidos"></span>

        Email: <input type="email" name="email" id="email" value="<?= $datos['email'] ?>" required>
        <span class="error-campo" id="error-email"></span>

        Teléfono: <input type="tel" name="telefono" id="telefono" value="<?= $datos['telefono'] ?>">
        <span class="error-campo" id="error-telefono"></span>

        DNI: <input type="text" name="dni" id="dni" value="<?= $datos['dni'] ?>">
        <span class="error-campo" id="error-dni"></span>

        Fecha de nacimiento: <input type="date" name="fecha_nacimiento" value="<?= $datos['fecha_nacimiento'] ?>">

        Dirección: <input type="text" name="direccion" value="<?= $datos['direccion'] ?>">

        Rol: <select name="rol" required>
            <option value="admin" <?= $datos['rol'] == 'admin'    ? 'selected' : '' ?>>Admin</option>
            <option value="profesor" <?= $datos['rol'] == 'profesor' ? 'selected' : '' ?>>Profesor</option>
            <option value="alumno" <?= $datos['rol'] == 'alumno'   ? 'selected' : '' ?>>Alumno</option>
        </select>

        <button type="submit" name="editar">Actualizar usuario</button>

    </form>

    <?php if (!empty($mensaje_error)) { ?>
        <?= $mensaje_error ?>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>