<?php include "plantillas/header.php"; ?>
<?php include "plantillas/navbar.php"; ?>

<main class="main">
    <section class="bloque">
        <h1>Próximos eventos</h1>
        <p>Aquí se mostrarán los próximos conciertos, audiciones y actuaciones organizadas por la sociedad musical.</p>
        <iframe src="https://calendar.google.com/calendar/embed?..." style="border: 0" height="300px"
            frameborder="0" scrolling="no">
        </iframe>
    </section>

    <section class="galeria-eventos">
        <h1>Galería de Eventos</h1>
        <div class="grid-galeria">
        <a
            href="https://lipsum.app/id/1/1600x1200"
            data-fancybox="eventos"
            data-caption="Optional caption,&lt;br /&gt;that can contain &lt;em&gt;HTML&lt;/em&gt; code">
            <img
                src="https://lipsum.app/id/1/200x150"
                width="200"
                height="150"
                alt="Sample image #1" />
        </a>
        <a href="https://lipsum.app/id/2/1600x1200" data-fancybox="eventos">
            <img
                src="https://lipsum.app/id/2/200x150"
                width="200"
                height="150"
                alt="Sample image #2" />
        </a>
        <a href="https://lipsum.app/id/3/1600x1200" data-fancybox="eventos">
            <img
                src="https://lipsum.app/id/3/200x150"
                width="200"
                height="150"
                alt="Sample image #3" />
        </a>
        <a href="https://lipsum.app/id/4/1600x1200" data-fancybox="eventos">
            <img
                src="https://lipsum.app/id/4/200x150"
                width="200"
                height="150"
                alt="Sample image #4" />
        </a>
        <a href="https://lipsum.app/id/5/1600x1200" data-fancybox="eventos">
            <img
                src="https://lipsum.app/id/5/200x150"
                width="200"
                height="150"
                alt="Sample image #5" />        
        </a>
        <a href="https://lipsum.app/id/6/1600x1200" data-fancybox="eventos">
            <img
                src="https://lipsum.app/id/6/200x150"
                width="200"
                height="150"
                alt="Sample image #6" />        
        </a>
        </div>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script> 

<?php include "plantillas/footer.php"; ?>