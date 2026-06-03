<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";

if (isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = trim($_POST['direccion']);
    $rol = $_POST['rol'];

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($_POST['password']) || empty($rol)) {
        $mensaje_error = "<p class='mensaje_error'>Rellena los campos obligatorios</p>";
    } else {
        $existe = $conexion->query("SELECT id_usuario FROM usuarios WHERE email='$email'");
        if ($existe->num_rows > 0) {
            $mensaje_error = "<p class='mensaje_error'>El email ya existe</p>";
        } else {
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, fecha_nacimiento, direccion, rol) 
                    VALUES ('$nombre', '$apellidos', '$email', '$password', '$telefono', '$dni', '$fecha_nacimiento', '$direccion', '$rol')";

            if ($conexion->query($sql)) {
                $_SESSION['mensaje'] = "<p class='mensaje_exito'>Usuario creado correctamente</p>";
                header("Location: usuarios.php");
                exit;
            } else {
                $mensaje_error = "<p class='mensaje_error'>Error al crear el usuario</p>";
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
    <h1>Crear usuario</h1>

    <form method="POST" class="formulario-usuario" id="formCrear">

        Nombre: <input type="text" name="nombre" id="nombre" required>
        <span class="error-campo" id="error-nombre"></span>

        Apellidos: <input type="text" name="apellidos" id="apellidos" required>
        <span class="error-campo" id="error-apellidos"></span>

        Email: <input type="email" name="email" id="email" required>
        <span class="error-campo" id="error-email"></span>

        Contraseña: <input type="password" name="password" id="password" required>
        <span class="error-campo" id="error-password"></span>

        Teléfono: <input type="tel" name="telefono" id="telefono">
        <span class="error-campo" id="error-telefono"></span>

        DNI: <input type="text" name="dni" id="dni">
        <span class="error-campo" id="error-dni"></span>

        Fecha de nacimiento: <input type="date" name="fecha_nacimiento">

        Dirección: <input type="text" name="direccion" id="direccion">
        <span class="error-campo" id="error-direccion"></span>

        <select name="rol" required>
            <option value="">Selecciona un rol</option>
            <option value="admin">Admin</option>
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>
        </select>

        <button type="submit" name="crear">Crear usuario</button>
    </form>

    <?php if (!empty($mensaje_error)) { ?>
        <?= $mensaje_error ?>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>