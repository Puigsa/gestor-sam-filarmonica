<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Dashboard</h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Asignaturas</h3>
            <a href="asignaturas.php">Ver más</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Anuncios</h3>
            <a href="anuncios.php">Ver más</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Recursos</h3>            
            <a href="recursos.php">Ver más</a>
        </div>
    </div>
</main>

<?php include "../plantillas/footer_privado.php";


?>