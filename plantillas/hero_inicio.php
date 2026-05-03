<div class="banner">
    <div class="banner-texto">
        <h1>SAM LA FILARMÓNICA</h1>
        <p>Aprende música con nosotros y forma parte de nuestra historia</p>
        <a href="matricula.php"><button class="btn-banner">Matricúlate ahora</button></a>
    </div>

    <div class="banner-carrusel">
        <div class="f-carousel" id="myCarousel">
            <div class="f-carousel__slide">
                <img src="estaticos/img/slideshow/uniformes.JPG" alt="Miembros de la banda en uniforme">
            </div>
            <div class="f-carousel__slide">
                <img src="estaticos/img/slideshow/trompa.jpg" alt="Instrumentos de viento - Trompa">
            </div>
            <div class="f-carousel__slide">
                <img src="estaticos/img/slideshow/solfeo.jpg" alt="Clases de solfeo y teoría musical">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.arrows.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.dots.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.autoplay.umd.js"></script>

    <script>
        Carousel(document.getElementById("myCarousel"), {
            Autoplay: {
                pauseOnHover: true,
                timeout: 6000
            },
            style: {
                "--f-progressbar-color": "#111",
                "--f-progressbar-height": "2px",
            },
        }, {
            Arrows,
            Dots,
            Autoplay
        }).init();
    </script>
</div>
