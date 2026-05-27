<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// Estadísticas
$total_usuarios = $conexion->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_cursos = $conexion->query("SELECT COUNT(*) as total FROM cursos")->fetch_assoc()['total'];
$total_matriculas = $conexion->query("SELECT COUNT(*) as total FROM matriculas WHERE estado='activa'")->fetch_assoc()['total'];
$pagos_pendientes = $conexion->query("SELECT COUNT(*) as total FROM pagos WHERE estado='pendiente'")->fetch_assoc()['total'];
$proximos_eventos = $conexion->query("SELECT COUNT(*) as total FROM eventos WHERE fecha >= CURDATE() AND publicado=1")->fetch_assoc()['total'];
$total_anuncios = $conexion->query("SELECT COUNT(*) as total FROM anuncios")->fetch_assoc()['total'];
desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    
    <h1>Dashboard</h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Usuarios</h3>
            <p class="numero"><?= $total_usuarios ?></p>
            <a href="usuarios.php">Ver más</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Cursos</h3>
            <p class="numero"><?= $total_cursos ?></p>
            <a href="cursos.php">Ver más</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Matrículas activas</h3>
            <p class="numero"><?= $total_matriculas ?></p>
            <a href="matriculas.php">Ver más</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Pagos pendientes</h3>
            <p class="numero"><?= $pagos_pendientes ?></p>
            <a href="pagos.php">Ver más</a>
        </div>
        <div class="dashboard-card">
            <h3>Anuncios</h3>
            <p class="numero"><?= $total_anuncios ?></p>
            <a href="anuncios.php">Ver más</a>
        </div>

        <div class="dashboard-card">
            <h3>Próximos eventos</h3>
            <p class="numero"><?= $proximos_eventos ?></p>
            <a href="eventos.php">Ver más</a>
        </div>
    </div>
</main>

<?php include "../plantillas/footer_privado.php"; ?>