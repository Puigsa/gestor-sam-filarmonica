<?php
// Iniciar sesión y cargar configuración
session_start();
require_once "../includes/config.php";
require_once "../includes/funciones.php";

// Verificar que el usuario está autenticado y es admin
comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$id = (int) ($_GET['id'] ?? 0);

// Validar que existe el ID de la prematrícula en la URL
if (!$id) {
    header("Location: matriculas.php");
    exit;
}

// Obtener datos de la prematrícula a aprobar
$prematricula = $conexion->query("SELECT * FROM prematriculas WHERE id_prematricula=$id");
if ($prematricula->num_rows == 0) {
    header("Location: matriculas.php");
    exit;
}

$datos = $prematricula->fetch_assoc();
$mensaje_error = "";
$mensaje_exito = "";
$usuario_creado = false;

// Cargar datos para los selects del formulario
$cursos = $conexion->query("SELECT id_curso, nombre FROM cursos");
$instrumentos = $conexion->query("SELECT id_instrumento, nombre FROM instrumentos");

// Procesar el formulario cuando se envía
if (isset($_POST['aprobar'])) {
    // Recoger y limpiar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $id_curso = (int) ($_POST['id_curso'] ?? 0);
    $id_instrumento = (int) ($_POST['id_instrumento'] ?? 0);
    $consentimiento = isset($_POST['tutor_consentimiento']) && $_POST['tutor_consentimiento'] === '1' ? 1 : 0;
    $tutor_nombre = trim($_POST['tutor_nombre'] ?? '');
    $tutor_dni = trim($_POST['tutor_dni'] ?? '');
    $tutor_email = trim($_POST['tutor_email'] ?? '');
    $tutor_telefono = trim($_POST['tutor_telefono'] ?? '');

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($dni) || empty($direccion) || empty($fecha_nacimiento) || $id_curso <= 0 || $id_instrumento <= 0) {
        $mensaje_error = "Rellena todos los campos obligatorios";
    } elseif (!validarEmail($email)) {
        $mensaje_error = "Email inválido";
    } elseif (!validarDNI($dni)) {
        $mensaje_error = "DNI/NIE inválido";
    } elseif (!empty($telefono) && !validarTelefono($telefono)) {
        $mensaje_error = "Teléfono inválido (9 dígitos)";
    } else {
        // Validar fecha de nacimiento y calcular edad
        $fecha_valida = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        if (!$fecha_valida) {
            $mensaje_error = "Fecha de nacimiento inválida";
        } else {
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_valida)->y;

            if ($edad < 0) {
                $mensaje_error = "Fecha de nacimiento inválida";
            } elseif ($edad < 18) {
                // Si es menor, validar datos del tutor
                if (empty($tutor_nombre) || empty($tutor_dni) || empty($tutor_email) || empty($tutor_telefono) || !$consentimiento) {
                    $mensaje_error = "Para menores de edad, debe rellenar los datos del tutor legal y dar consentimiento.";
                } elseif (!validarDNI($tutor_dni)) {
                    $mensaje_error = "DNI/NIE del tutor inválido";
                } elseif (!validarEmail($tutor_email)) {
                    $mensaje_error = "Email del tutor inválido";
                } elseif (!validarTelefono($tutor_telefono)) {
                    $mensaje_error = "Teléfono del tutor inválido (9 dígitos)";
                }
            }
        }
    }

    // Si no hay errores, crear o actualizar usuario
    if (empty($mensaje_error)) {
        $existe = $conexion->query("SELECT id_usuario FROM usuarios WHERE email='$email' OR dni='$dni'");
        if ($existe === false) {
            $mensaje_error = "Error al comprobar usuario: " . $conexion->error;
        } elseif ($existe->num_rows > 0) {
            // Usuario existe: actualizar datos
            $id_alumno = $existe->fetch_assoc()['id_usuario'];
            $sql_update = "UPDATE usuarios
                   SET nombre='$nombre',
                       apellidos='$apellidos',
                       email='$email',
                       telefono='$telefono',
                       fecha_nacimiento='$fecha_nacimiento',
                       direccion='$direccion'
                   WHERE id_usuario=$id_alumno";
            if (!$conexion->query($sql_update)) {
                $mensaje_error = "Error al actualizar usuario: " . $conexion->error;
            }
        } else {
            // Usuario no existe: crear nuevo
            $password = password_hash($dni, PASSWORD_DEFAULT);
            $sql_usuario = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, fecha_nacimiento, direccion, rol)
                            VALUES ('$nombre', '$apellidos', '$email', '$password', '$telefono', '$dni', '$fecha_nacimiento', '$direccion', 'alumno')";
            if ($conexion->query($sql_usuario)) {
                $usuario_creado = true;

                $id_alumno = $conexion->insert_id;
            } else {
                $mensaje_error = "Error al crear usuario: " . $conexion->error;
            }
        }
    }

    // Verificar plazas disponibles en el curso
    if (empty($mensaje_error)) {
        $curso_plazas = $conexion->query("SELECT plazas FROM cursos WHERE id_curso=$id_curso");
        if ($curso_plazas && $curso_plazas->num_rows > 0) {
            $plazas = $curso_plazas->fetch_assoc()['plazas'];
            if ($plazas <= 0) {
                $mensaje_error = "No hay plazas disponibles en este curso.";
            }
        }
    }

    // Verificar que el alumno no tiene otra matrícula activa
    if (empty($mensaje_error) && isset($id_alumno)) {
        $alumno_activa = $conexion->query("SELECT id_matricula FROM matriculas WHERE id_alumno = $id_alumno AND estado = 'activa'");
        if ($alumno_activa && $alumno_activa->num_rows > 0) {
            $mensaje_error = "El alumno ya tiene una matrícula activa";
        }
    }


    // Si todo es correcto, crear matrícula, pago y prematrícula 'aprobada'
    if (empty($mensaje_error)) {
        $sql_matricula = "INSERT INTO matriculas (id_alumno, id_curso, id_instrumento, fecha_matricula, estado, 
                        tutor_nombre, tutor_dni, tutor_email, tutor_telefono, tutor_consentimiento) 
                      VALUES ($id_alumno, $id_curso, $id_instrumento, NOW(), 'activa', 
                            '$tutor_nombre', '$tutor_dni', '$tutor_email', '$tutor_telefono', $consentimiento)";

        // Obtener precio del curso
        $precio_result = $conexion->query("SELECT precio FROM cursos WHERE id_curso = $id_curso");
        $precio = $precio_result->fetch_assoc()['precio'];

        if ($conexion->query($sql_matricula)) {
            $id_matricula = $conexion->insert_id;

            // Descontar una plaza del curso
            $conexion->query("UPDATE cursos SET plazas = plazas - 1 WHERE id_curso = $id_curso");

            // Crear pago pendiente para la matrícula
            $sql_pago = "INSERT INTO pagos (id_alumno, id_matricula, importe, estado) 
                     VALUES ($id_alumno, $id_matricula, $precio, 'pendiente')";

            $conexion->query($sql_pago);

            // Marcar prematrícula como aprobada
            $conexion->query("UPDATE prematriculas SET estado='aprobada' WHERE id_prematricula=$id");
            desconectar($conexion);

            // Guardar mensaje de éxito en sesión
            if ($usuario_creado) {
                $_SESSION['mensaje_exito'] = "Usuario creado y matrícula creada correctamente.";
            } else {
                $_SESSION['mensaje_exito'] = "Usuario actualizado y matrícula creada correctamente.";
            }

            header("Location: matriculas.php");
            exit;
        } else {
            $mensaje_error = "Error al crear matrícula: " . $conexion->error;
        }
    }
}

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Aprobar prematrícula</h1>

    <?php if (!empty($mensaje_error)) { ?>
        <p class="mensaje_error"><?= $mensaje_error ?></p>
    <?php } ?>

    <form method="POST" id="formAprobar">
        <fieldset style="border:none">
            <h3>Datos personales</h3>

            Nombre: <input type="text" name="nombre" id="nombre"
                value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : $datos['nombre'] ?>" required>
            <span class="error-campo" id="error-nombre"></span>

            Apellidos: <input type="text" name="apellidos" id="apellidos"
                value="<?= isset($_POST['apellidos']) ? $_POST['apellidos'] : $datos['apellidos'] ?>" required>
            <span class="error-campo" id="error-apellidos"></span>

            Email: <input type="email" name="email" id="email"
                value="<?= isset($_POST['email']) ? $_POST['email'] : $datos['email'] ?>" required>
            <span class="error-campo" id="error-email"></span>

            Teléfono: <input type="tel" name="telefono" id="telefono"
                value="<?= isset($_POST['telefono']) ? $_POST['telefono'] : $datos['telefono'] ?>">
            <span class="error-campo" id="error-telefono"></span>

            DNI: <input type="text" name="dni" id="dni"
                value="<?= isset($_POST['dni']) ? $_POST['dni'] : $datos['dni'] ?>" required>
            <span class="error-campo" id="error-dni"></span>

            Fecha de nacimiento: <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                 value="<?= isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : $datos['fecha_nacimiento'] ?>" onchange="mostrarTutor()" required>
            Dirección: <input type="text" name="direccion" id="direccion"
                value="<?= isset($_POST['direccion']) ? $_POST['direccion'] : $datos['direccion'] ?>" required>
            <span class="error-campo" id="error-direccion"></span>

        </fieldset>

        <fieldset id="campos-tutor"style="display:none; border:none;">
            <h3>Datos del tutor/a legal</h3>

            Nombre tutor/a: <input type="text" name="tutor_nombre" id="tutor_nombre"
                value="<?= isset($_POST['tutor_nombre']) ? $_POST['tutor_nombre'] : $datos['tutor_nombre'] ?>">
            <span class="error-campo" id="error-tutor_nombre"></span>

            DNI tutor/a: <input type="text" name="tutor_dni" id="tutor_dni"
                value="<?= isset($_POST['tutor_dni']) ? $_POST['tutor_dni'] : $datos['tutor_dni'] ?>">
            <span class="error-campo" id="error-tutor_dni"></span>

            Email tutor/a: <input type="email" name="tutor_email" id="tutor_email"
                value="<?= isset($_POST['tutor_email']) ? $_POST['tutor_email'] : $datos['tutor_email'] ?>">
            <span class="error-campo" id="error-tutor_email"></span>

            Teléfono tutor/a: <input type="text" name="tutor_telefono" id="tutor_telefono"
                value="<?= isset($_POST['tutor_telefono']) ? $_POST['tutor_telefono'] : $datos['tutor_telefono'] ?>">
            <span class="error-campo" id="error-tutor_telefono"></span>

            Consentimiento:
            <select name="tutor_consentimiento">
                <option value="1" <?= isset($_POST['tutor_consentimiento']) ? ($_POST['tutor_consentimiento'] == 1 ? 'selected' : '') : ($datos['tutor_consentimiento'] == 1 ? 'selected' : '') ?>>SÍ</option>
                <option value="0" <?= isset($_POST['tutor_consentimiento']) ? ($_POST['tutor_consentimiento'] == 0 ? 'selected' : '') : ($datos['tutor_consentimiento'] == 0 ? 'selected' : '') ?>>NO</option>
            </select>

        </fieldset>

        <fieldset style="border:none">
            <h3>Datos académicos</h3>

            <select name="id_curso" required>
                <option value="">Selecciona curso</option>
                <?php while ($curso = $cursos->fetch_assoc()) { ?>
                    <option value="<?= $curso['id_curso'] ?>" <?= $curso['id_curso'] == $datos['id_curso'] ? 'selected' : '' ?>>
                        <?= $curso['nombre'] ?>
                    </option>
                <?php } ?>
            </select>

            <select name="id_instrumento" required>
                <option value="">Selecciona instrumento</option>
                <?php while ($inst = $instrumentos->fetch_assoc()) { ?>
                    <option value="<?= $inst['id_instrumento'] ?>" <?= $inst['id_instrumento'] == $datos['id_instrumento'] ? 'selected' : '' ?>>
                        <?= $inst['nombre'] ?>
                    </option>
                <?php } ?>
            </select>

        </fieldset>

        <?php if ($datos['estado'] == 'aprobada') { ?>
            <button type="submit" name="aprobar" disabled>Ya aprobada</button>
        <?php } else { ?>
            <button type="submit" name="aprobar"
                onclick="return confirm('¿Estás seguro de que deseas aprobar esta prematrícula?')">Aprobar y formalizar matrícula</button>
        <?php } ?>

    </form>

</main>

<script>
function mostrarTutor() {

    const fecha = document.getElementById('fecha_nacimiento');
    const tutor = document.getElementById('campos-tutor');

    if (!fecha || !tutor) return;

    // SI NO HAY FECHA
    if (!fecha.value) {

        tutor.style.display = 'none';
        return;
    }

    const hoy = new Date();
    const nacimiento = new Date(fecha.value);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    // MENOR DE EDAD
    if (edad < 18) {
        tutor.style.display = 'block';
    } else {
        tutor.style.display = 'none';
    }
}

// EJECUTAR AL CARGAR
mostrarTutor();

</script>

<?php include "../plantillas/footer_privado.php"; ?>