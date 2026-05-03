<?php include "plantillas/header.php"; ?>
<?php include "plantillas/navbar.php"; ?>

<?php
require_once "includes/config.php";

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
$instrumento = "";
$observaciones = "";

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
    $instrumento = trim($_POST['id_instrumento']);
    $observaciones = trim($_POST['observaciones']);

    if (
        empty($nombre) || empty($apellidos) || empty($email) ||
        empty($telefono) || empty($dni) || empty($fecha_nacimiento) ||
        empty($id_curso)
    ) {
        $error = "Debe rellenar todos los campos obligatorios.";
    } else {
        $comprobar = $conexion->query("SELECT id_prematricula FROM prematriculas WHERE dni='$dni' AND id_curso='$id_curso'");

        if ($comprobar->num_rows > 0) {
            $error = "Ya existe una solicitud de prematrícula para este curso con ese DNI.";
        } else {
            $insert = "INSERT INTO prematriculas
                    (nombre, apellidos, email, telefono, dni, fecha_nacimiento, id_curso, instrumento, observaciones, estado, fecha_solicitud)
                    VALUES
                    ('$nombre', '$apellidos', '$email', '$telefono', '$dni', '$fecha_nacimiento', '$id_curso', '$instrumento', '$observaciones', 'pendiente', CURDATE())";

            if ($conexion->query($insert)) {
                $exito = "Solicitud enviada correctamente. Nos pondremos en contacto con usted.";

                $nombre = "";
                $apellidos = "";
                $email = "";
                $telefono = "";
                $dni = "";
                $fecha_nacimiento = "";
                $id_curso = "";
                $instrumento = "";
                $observaciones = "";
            } else {
                $error = "Error al enviar la solicitud.";
            }
        }
    }
}
?>

<main class="main">
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
                    <legend>Formulario Prematrícula </legend>

                    <fieldset>
                        <legend>Datos personales</legend>

                        <input type="text" name="nombre" placeholder="Nombre" required value="<?= $nombre ?>">
                        <input type="text" name="apellidos" placeholder="Apellidos" required value="<?= $apellidos ?>">
                        <input type="email" name="email" placeholder="Email" required value="<?= $email ?>">
                        <input type="text" name="telefono" placeholder="Teléfono" required value="<?= $telefono ?>">
                        <input type="text" name="dni" placeholder="DNI" required value="<?= $dni ?>">
                        <input type="date" name="fecha_nacimiento" required value="<?= $fecha_nacimiento ?>">
                    </fieldset>

                    <fieldset>
                        <legend>Información académica</legend>

                        <select name="id_curso" id="id_curso" required>
                            <option value="">Seleccione curso</option>

                            <?php while ($curso = $cursos->fetch_assoc()) { ?>
                                <option value="<?= $curso['id_curso'] ?>" <?php if ($id_curso == $curso['id_curso']) echo "selected" ?>>
                                    <?= $curso['nombre'] ?>
                                </option>
                            <?php } ?>

                        </select>


                        <div id="asignaturas">

                        </div>

                        <select name="instrumento">
                            <option value="">Seleccione instrumento</option>

                            <?php while ($i = $instrumentos->fetch_assoc()) { ?>
                                <option value="<?= $i['nombre'] ?>" <?php if ($instrumento == $i['nombre']) echo "selected" ?>>
                                    <?= $i['nombre'] ?>
                                </option>
                            <?php } ?>

                        </select>
                    </fieldset>

                    <h2>Observaciones</h2>

                    <textarea name="observaciones"><?= $observaciones ?></textarea><br>

                    <button type="submit" name="prematricular"> Enviar solicitud </button>
                </fieldset>
            </form>

        <?php } else { ?>
            <p><strong>El plazo de prematrícula no está disponible en este momento.</strong></p>
        <?php } ?>
    </section>
</main>

<script>
    document.getElementById('id_curso').addEventListener('change', function() {

        let idCurso = this.value;

        if (!idCurso) {
            document.getElementById('asignaturas').innerHTML = "";
            return;
        }

        fetch('./ajax/asignaturas_curso.php?id_curso=' + idCurso)
            .then(res => res.text())
            .then(data => {
                document.getElementById('asignaturas').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('asignaturas').innerHTML = "Error cargando asignaturas";
            });

    });
</script>
<?php
include "plantillas/footer.php";
desconectar($conexion);
?>