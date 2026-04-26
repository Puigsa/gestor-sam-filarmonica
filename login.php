<?php
require_once "includes/config.php";
require_once "includes/funciones.php";

$mensaje = "";
$email = "";

// Si ya existe una sesión iniciada, redirige según el rol
redirigirSegunRol();

$conexion = conectar();

// Procesa el formulario cuando se envía
if (isset($_POST['login'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $mensaje = "Rellene todos los campos";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $resultado = $conexion->query("SELECT * FROM usuarios WHERE email='$email'");

        if ($resultado->num_rows != 1) {
            $mensaje = "El correo introducido es incorrecto";
        } else {
            $fila = $resultado->fetch_assoc();

            if (!password_verify($password, $fila['password'])) {
                $mensaje = "Contraseña incorrecta";
            } else {
                $_SESSION['id_usuario'] = $fila['id'];
                $_SESSION['user'] = $fila['nombre'];
                $_SESSION['rol'] = $fila['rol'];

                redirigirSegunRol();
            }
        }
    }
}

desconectar($conexion);

include "plantillas/header.php";
include "plantillas/navbar.php";
?>

<main class="main">
    <section class="login">
        <h2>Iniciar sesión</h2>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo $email; ?>"><br>
            <input type="password" name="password" placeholder="Contraseña"><br>
            <button type="submit" name="login">Iniciar sesión</button>
        </form>

        <?php
        if (!empty($mensaje)) {
            echo "<p class='error'>$mensaje</p>";
        }
        ?>
    </section>
</main>

<?php include "plantillas/footer.php"; ?>
