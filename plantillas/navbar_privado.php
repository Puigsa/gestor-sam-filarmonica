<?php
require "../includes/funciones.php";

comprobarAcceso() ;
$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];

?>

<!-- Franja superior -->
<div class="navbar-privado-top">
    <div class="breadcrumb">
        <?php
        $pagina_actual = basename($_SERVER['PHP_SELF']);
        $breadcrumbs = [
            'index.php' => 'Dashboard',
            'usuarios.php' => 'Usuarios',
            'cursos.php' => 'Cursos',
            'prematriculas.php' => 'Prematrículas',
            'matriculas.php' => 'Matrículas',
            'pagos.php' => 'Pagos',
            'eventos.php' => 'Eventos',
            'anuncios.php' => 'Anuncios',
            'recursos.php' => 'Recursos'
        ];
        ?>
        <a href="index.php">Inicio</a>
        <?php if (isset($breadcrumbs[$pagina_actual])) { ?>
            <span> / <?= $breadcrumbs[$pagina_actual] ?></span>
        <?php } ?>
    </div>
    <div class="usuario-info">
        <span>Hola, <?= $nombre ?></span>
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</div>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="estaticos/img/logo.png" alt="Logo">
    </div>
    
    <nav class="sidebar-menu">
        <?php if ($rol === 'admin') { ?>
            <a href="admin/index.php" class="menu-item">Dashboard</a>
            <a href="admin/usuarios.php" class="menu-item">Usuarios</a>
            <a href="admin/cursos.php" class="menu-item">Cursos</a>
            <a href="admin/matriculas.php" class="menu-item">Matrículas</a>
            <a href="admin/pagos.php" class="menu-item">Pagos</a>
            <a href="admin/eventos.php" class="menu-item">Eventos</a>
        
        <?php } else if ($rol === 'profesor') { ?>
            <a href="profesor/index.php" class="menu-item">Dashboard</a>
            <a href="profesor/cursos.php" class="menu-item">Mis cursos</a>
            <a href="profesor/anuncios.php" class="menu-item">Anuncios</a>
            <a href="profesor/recursos.php" class="menu-item">Recursos</a>
        
       <?php } else if ($rol === 'alumno') { ?>
            <a href="alumno/index.php" class="menu-item">Dashboard</a>
            <a href="alumno/cursos.php" class="menu-item">Mis cursos</a>
            <a href="alumno/anuncios.php" class="menu-item">Anuncios</a>
            <a href="alumno/recursos.php" class="menu-item">Recursos</a>
        <?php } ?>
    </nav>
</aside>
