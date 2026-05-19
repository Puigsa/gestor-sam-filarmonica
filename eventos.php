<?php
include "includes/config.php";
require_once "includes/funciones.php";
include "plantillas/header.php";
include "plantillas/navbar.php";

$conexion = conectar();
$hoy = date('Y-m-d');

$proximos = $conexion->query("SELECT * FROM eventos WHERE fecha >= '$hoy' AND publicado = 1 ORDER BY fecha ASC");

desconectar($conexion);
?>

<main>

    <section class="bloque calendario">
        <h1>Calendario de eventos</h1>
        <div class="calendario-contenedor">

            <div id="calendario"></div>


            <div id="evento-detalle">
                <h2>Detalles del evento</h2>
                <p>Haz clic en un evento del calendario para ver los detalles aquí.</p>
            </div>
        </div>
    </section>

</main>

<!-- cdn Fancybox -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<!-- cdn FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<?php include "plantillas/footer.php"; ?>