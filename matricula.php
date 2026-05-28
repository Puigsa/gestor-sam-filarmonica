<?php include "plantillas/header.php"; ?>
<?php include "plantillas/navbar.php"; ?>

<?php
require_once "includes/config.php";
require_once "includes/funciones.php";

$inicio_plazo = "2026-06-01";
$fin_plazo = "2026-07-15";
$hoy = date("Y-m-d");
//$plazo_abierto = ($hoy >= $inicio_plazo && $hoy <= $fin_plazo);
$plazo_abierto = true; // Para pruebas, siempre abierto
$conexion = conectar();

if (!$conexion) {
    die("Error de conexión a la base de datos");
}

$error = "";
$exito = "";

$nombre = "";
$apellidos = "";
$email = "";
$telefono = "";
$dni = "";
$fecha_nacimiento = "";
$id_curso = "";
$id_instrumento = "";
$observaciones = "";
$direccion = "";
$tutor_nombre = "";
$tutor_dni = "";
$tutor_email = "";
$tutor_telefono = "";
$consentimiento = "";

// Cargar cursos
$cursos = $conexion->query("SELECT id_curso, nombre FROM cursos ORDER BY id_curso ASC");
// Cargar instrumentos
$instrumentos = $conexion->query("SELECT id_instrumento, nombre FROM instrumentos ORDER BY id_instrumento ASC");

if (!$cursos) {
    die("Error al cargar cursos: " . $conexion->error);
}

if ($plazo_abierto && isset($_POST['prematricular'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_curso = $_POST['id_curso'];
    $id_instrumento = $_POST['id_instrumento'];
    $observaciones = trim($_POST['observaciones']);
    $direccion = trim($_POST['direccion']);
    $tutor_nombre = trim($_POST['tutor_nombre'] ?? '');
    $tutor_dni = trim($_POST['tutor_dni'] ?? '');
    $tutor_email = trim($_POST['tutor_email'] ?? '');
    $tutor_telefono = trim($_POST['tutor_telefono'] ?? '');
    $consentimiento = isset($_POST['consentimiento']) ? 1 : 0;

    // Calcular edad
    $hoy = new DateTime();
    $nacimiento = new DateTime($fecha_nacimiento);
    $edad = $hoy->diff($nacimiento)->y;

    if (
        empty($nombre) || empty($apellidos) || empty($email) ||
        empty($telefono) || empty($dni) || empty($fecha_nacimiento) ||
        empty($id_curso) || empty($direccion)
    ) {
        $error = "<p class='mensaje_error'>Debe rellenar todos los campos obligatorios.</p>";
    } else if (!validarEmail($email)) {
        $error = "<p class='mensaje_error'>Email inválido</p>";
    } else if (!validarDNI($dni)) {
        $error = "<p class='mensaje_error'>DNI/NIE inválido</p>";
    } else if (!validarTelefono($telefono)) {
        $error = "<p class='mensaje_error'>Teléfono inválido (9 dígitos)</p>";
    } else if ($edad < 0) {
        $error = "<p class='mensaje_error'>Fecha de nacimiento inválida</p>";
    } else if ($edad < 18 && !$consentimiento) {
        $error = "<p class='mensaje_error'>Para menores de edad, debe dar consentimiento.</p>";
    } else if ($edad < 18 && !validarDNI($tutor_dni)) {
        $error = "<p class='mensaje_error'>DNI/NIE del tutor inválido</p>";
    } else if ($edad < 18 && !validarEmail($tutor_email)) {
        $error = "<p class='mensaje_error'>Email del tutor inválido</p>";
    } else if ($edad < 18 && !validarTelefono($tutor_telefono)) {
        $error = "<p class='mensaje_error'>Teléfono del tutor inválido (9 dígitos)</p>";
    } else {

        // Si es menor, validar datos de tutor
        if ($edad < 18) {
            if (empty($tutor_nombre) || empty($tutor_dni) || empty($tutor_email) || empty($tutor_telefono) || !$consentimiento) {
                $error = "<p class='mensaje_error'>Para menores de edad, debe rellenar los datos del tutor legal y dar consentimiento.</p>";
            }
        }

        if (empty($error)) {
            $comprobar = $conexion->query("SELECT id_prematricula FROM prematriculas WHERE dni='$dni' AND id_curso='$id_curso'");

            if ($comprobar->num_rows > 0) {
                $error = "<p class='mensaje_error'>Ya existe una solicitud de prematrícula para este curso con ese DNI.</p>";
            } else {
                $insert = "INSERT INTO prematriculas
                        (nombre, apellidos, email, telefono, dni, fecha_nacimiento, id_curso, id_instrumento, 
                        observaciones, estado, direccion, tutor_nombre, tutor_dni, tutor_email, tutor_telefono, tutor_consentimiento, fecha_solicitud)
                        VALUES
                        ('$nombre', '$apellidos', '$email', '$telefono', '$dni', '$fecha_nacimiento', '$id_curso', '$id_instrumento',
                          '$observaciones', 'pendiente', '$direccion', '$tutor_nombre', '$tutor_dni', '$tutor_email', '$tutor_telefono', $consentimiento, CURDATE())";

                if ($conexion->query($insert)) {
                    $exito = "<p class='mensaje_exito'>Solicitud enviada correctamente. Nos pondremos en contacto con usted.</p>";

                    $nombre = "";
                    $apellidos = "";
                    $email = "";
                    $telefono = "";
                    $dni = "";
                    $fecha_nacimiento = "";
                    $id_curso = "";
                    $id_instrumento = "";
                    $observaciones = "";
                    $direccion = "";
                    $tutor_nombre = "";
                    $tutor_dni = "";
                    $tutor_email = "";
                    $tutor_telefono = "";
                    $consentimiento = "";
                } else {
                    $error = "<p class='mensaje_error'>Error al enviar la solicitud." . $conexion->error . "</p>";
                }
            }
        }
    }
}
?>

<main>
    <section class="bloque">

        <article>
            <h1>Prematrícula</h1>

            <p>
                A través de este formulario puede solicitar plaza en la Escuela de Música
                de SAM La Filarmónica de Callosa de Segura para el próximo curso académico.
            </p>

            <p>
                Una vez enviada la solicitud, esta será revisada por el centro y se contactará
                con la persona interesada para confirmar la disponibilidad de plaza y completar
                el proceso de matrícula.
            </p>
        </article>

        <article>

            <h2>Proceso de solicitud</h2>
            <ul>
                <li>Rellenar el formulario de prematrícula.</li>
                <li>Seleccionar el curso o modalidad deseada.</li>
                <li>Esperar la revisión de la solicitud por parte del centro.</li>
                <li>Recibir confirmación y pasos para formalizar la matrícula.</li>
            </ul>

            <h2>Información importante</h2>
            <ul>
                <li>La prematrícula no garantiza plaza automática.</li>
                <li>La admisión estará sujeta a disponibilidad de plazas.</li>
                <li>El centro podrá solicitar documentación adicional si fuera necesario.</li>
            </ul>
        </article>

        <article>

            <h2>Plazo de inscripción</h2>
            <p>
                El plazo de prematrícula estará disponible del
                <strong><?php echo date("d/m/Y", strtotime($inicio_plazo)); ?></strong>
                al
                <strong><?php echo date("d/m/Y", strtotime($fin_plazo)); ?></strong>.
            </p>

        </article>

        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <?php if (!empty($exito)) { ?>
            <p class="exito"><?php echo $exito; ?></p>
        <?php } ?>

        <?php if ($plazo_abierto) { ?>
            <h3><strong>El plazo de prematrícula está abierto.</strong></h3>

            <form action="matricula.php" method="POST" class="form-prematricula">
                <fieldset>
                    <legend>Formulario Prematrícula</legend>

                    <fieldset>
                        <legend>Datos personales</legend>

                        Nombre: <input type="text" name="nombre" required value="<?= $nombre ?>">
                        Apellidos: <input type="text" name="apellidos" required
                            value="<?= $apellidos ?>">
                        Email: <input type="email" name="email" required value="<?= $email ?>">
                        Teléfono: <input type="text" name="telefono" required value="<?= $telefono ?>">
                        DNI: <input type="text" name="dni" required value="<?= $dni ?>">
                        Fecha de nacimiento: <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                            value="<?= $fecha_nacimiento ?>" onchange="mostrarTutor()">
                        Dirección: <input type="text" name="direccion" placeholder="Dirección" required
                            value="<?= $direccion ?>">
                    </fieldset>

                    <!-- Campos de tutor legal (se muestran si es menor) -->
                    <fieldset id="campos-tutor" style="display:none;">
                        <legend>Datos del tutor/a legal</legend>

                        Nombre tutor/a: <input type="text" name="tutor_nombre" value="<?= $tutor_nombre ?>">
                        DNI tutor/a: <input type="text" name="tutor_dni" value="<?= $tutor_dni ?>">
                        Email tutor/a: <input type="email" name="tutor_email" value="<?= $tutor_email ?>">
                        Teléfono tutor/a: <input type="text" name="tutor_telefono" value="<?= $tutor_telefono ?>">

                        <label>
                            <input type="checkbox" name="consentimiento" <?= $consentimiento ? 'checked' : '' ?>>
                            Autorizo como padre/madre/tutor legal el acceso a esta escuela de música
                        </label>
                    </fieldset>

                    <fieldset>
                        <legend>Información académica</legend>

                        <select name="id_curso" id="id_curso" required>
                            <option value="">Seleccione curso</option>

                            <?php while ($curso = $cursos->fetch_assoc()) { ?>
                                <option value="<?= $curso['id_curso'] ?>" <?php if ($id_curso == $curso['id_curso'])
                                                                                echo "selected" ?>>
                                    <?= $curso['nombre'] ?>
                                </option>
                            <?php } ?>

                        </select>

                        <div id="asignaturas">

                        </div>

                        <select name="id_instrumento">
                            <option value="">Seleccione instrumento</option>

                            <?php while ($i = $instrumentos->fetch_assoc()) { ?>
                                <option value="<?= $i['id_instrumento'] ?>" <?php if ($id_instrumento == $i['id_instrumento'])
                                                                                echo "selected" ?>>
                                    <?= $i['nombre'] ?>
                                </option>
                            <?php } ?>

                        </select>
                    </fieldset>

                    <h2>Observaciones</h2>

                    <textarea name="observaciones"><?= $observaciones ?></textarea><br>

                    <button type="submit" name="prematricular">Enviar solicitud</button>
                </fieldset>
            </form>

        <?php } else { ?>
            <p class="mensaje_error"><strong>El plazo de prematrícula no está disponible en este momento.</strong></p>
        <?php } ?>
    </section>
</main>

<script>

    /*
        FUNCIÓN:
        Mostrar u ocultar los campos del tutor legal
        dependiendo de si el alumno es menor de edad
    */
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


    /*
        CUANDO CARGA LA PÁGINA:
        - comprobar edad actual
        - añadir evento change al input fecha
    */
    document.addEventListener('DOMContentLoaded', function() {

        // Ejecutar validación al cargar
        mostrarTutor();

        // Escuchar cambios en fecha nacimiento
        document.getElementById('fecha_nacimiento')
            .addEventListener('change', mostrarTutor);

    });


    /*
        CARGAR ASIGNATURAS DINÁMICAMENTE
        cuando cambia el curso seleccionado
    */
    document.getElementById('id_curso').addEventListener('change', function() {

        // Obtener id curso
        let idCurso = this.value;

        // Si no hay curso seleccionado
        if (!idCurso) {

            // Vaciar asignaturas
            document.getElementById('asignaturas').innerHTML = "";

            return;
        }

        /*
            Llamada AJAX al archivo PHP
            que devuelve las asignaturas
        */
        fetch('./ajax/asignaturas_curso.php?id_curso=' + idCurso)

            .then(res => res.text())
            .then(data => {

                // Insertar HTML recibido
                document.getElementById('asignaturas').innerHTML = data;

            })

            .catch(error => {

                // Mostrar error si falla
                document.getElementById('asignaturas').innerHTML =
                    "Error cargando asignaturas";

            });
    });

</script>

<?php
include "plantillas/footer.php";
desconectar($conexion);
?>