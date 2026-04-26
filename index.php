<?php include "plantillas/header.php"; ?>
<?php include "plantillas/navbar.php"; ?>

<main class="main">

    <?php include "plantillas/hero_inicio.php"; ?>

    <section class="programas">
        <div class="contenedor">
            <h2>Oferta Educativa</h2>
            <div class="tarjetas">

                <div class="card">
                    <img src="estaticos/img/slideshow/solfeo.jpg" alt="Clases de solfeo y teoría musical">
                    <div class="card-body">
                        <h3>Solfeo</h3>
                        <p>Aprende lectura musical y teoría desde cero.</p>
                        <button class="btn-card">Más info</button>
                    </div>
                </div>

                <div class="card">
                    <img src="estaticos/img/slideshow/trompa.jpg" alt="Lecciones de instrumentos de viento">
                    <div class="card-body">
                        <h3>Instrumento</h3>
                        <p>Aprende a tocar un instrumento.</p>
                        <button class="btn-card">Más info</button>
                    </div>
                </div>

                <div class="card">
                    <img src="estaticos/img/slideshow/uniformes.JPG" alt="Banda completa en uniforme oficial">
                    <div class="card-body">
                        <h3>Banda</h3>
                        <p>Forma parte de nuestra banda.</p>
                        <button class="btn-card">Más info</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="banda-contenedor">
        <h2>Nuestra banda</h2>
        <div class="banda">
            <p class="banda-texto">
                La Sociedad de Arte Musical La Filarmónica de Callosa de Segura es una de las
                formaciones bandísticas más representativas de la Vega Baja, con una trayectoria
                que combina tradición, formación musical y una presencia muy activa en la vida
                cultural de la localidad.
            </p>

            <iframe width="560" height="315"
                src="https://www.youtube.com/embed/1SAFrI1xIiY?si=9G8bnC0B0byBTwgS"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
            </iframe>
        </div>
    </section>

    <section class="eventos">
        <h2>Próximos Eventos</h2>
        <iframe src="https://calendar.google.com/calendar/embed?..." style="border: 0" height="300px"
            frameborder="0" scrolling="no">
        </iframe>
    </section>

</main>

<?php include "plantillas/footer.php"; ?>
