<?php
require_once "../includes/funciones.php";

$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];

?>

<!-- Franja superior -->
<div class="navbar-privado-top">
    <!-- Toggle sidebar móvil -->
    <button class="menu-toggle" id="menuToggle" aria-label="Menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="breadcrumb">
        <?php
        $pagina_actual = basename($_SERVER['PHP_SELF']);
        $breadcrumbs = [
            'index.php' => 'Dashboard',
            'usuarios.php' => 'Usuarios',
            'cursos.php' => 'Cursos',
            'prematriculas.php' => 'Prematrículas',
            'matriculas.php' => 'Matrículas',
            'asignaturas.php' => 'Asignaturas',
            'aprobar_prematricula.php' => 'Aprobar prematrícula',
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
        <a href="../logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</div>

<!-- Overlay sidebar -->
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="../estaticos/img/cecilio.PNG " alt="Logo">
    </div>

    <nav class="sidebar-menu">
        <?php if ($rol === 'admin') { ?>
            <a href="index.php" class="menu-item">Dashboard</a>
            <a href="usuarios.php" class="menu-item">Usuarios</a>
            <a href="cursos.php" class="menu-item">Cursos</a>
            <a href="matriculas.php" class="menu-item">Matrículas</a>
            <a href="asignaturas.php" class="menu-item">Asignaturas</a>
            <a href="pagos.php" class="menu-item">Pagos</a>
            <a href="anuncios.php" class="menu-item">Anuncios</a>

            <a href="eventos.php" class="menu-item">Eventos</a>

        <?php } else if ($rol === 'profesor') { ?>
            <a href="index.php" class="menu-item">Dashboard</a>
            <a href="asignaturas.php" class="menu-item">Asignaturas</a>
            <a href="anuncios.php" class="menu-item">Anuncios</a>
            <a href="recursos.php" class="menu-item">Recursos</a>

        <?php } else if ($rol === 'alumno') { ?>
            <a href="index.php" class="menu-item">Dashboard</a>
            <a href="asignaturas.php" class="menu-item">Asignaturas</a>
            <a href="anuncios.php" class="menu-item">Anuncios</a>
            <a href="recursos.php" class="menu-item">Recursos</a>
        <?php } ?>
    </nav>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const menuToggle = document.getElementById("menuToggle");
        const sidebar = document.getElementById("sidebar");

        const overlay = document.getElementById("overlay");

        const links = document.querySelectorAll(".sidebar-menu a");

        if (!menuToggle || !sidebar || !overlay) return;

        function openMenu() {
            sidebar.classList.add("active");
            menuToggle.classList.add("active");
            overlay.classList.add("active");
        }

        function closeMenu() {
            sidebar.classList.remove("active");
            menuToggle.classList.remove("active");
            overlay.classList.remove("active");
        }

        menuToggle.addEventListener("click", () => {
            sidebar.classList.contains("active") ?
                closeMenu() :
                openMenu();
        });

        overlay.addEventListener("click", closeMenu);
        links.forEach(link => {
            link.addEventListener("click", closeMenu);
        });
    });
</script>