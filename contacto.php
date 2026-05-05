<?php include "plantillas/header.php"; ?>
<?php include "plantillas/navbar.php"; ?>

<main class="main contacto">

    <div class="bloque contacto-grid">
        <section class="contacto-info">

            <h2>Datos de Contacto</h2>

            <ul>
                <li><strong>Redes Sociales:</strong>
                    <a href="https://www.facebook.com/filarmonicadecallosa" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/filarmonica_callosa/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@LaFilarmonicaCallosa" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://wa.me/1234567890" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                <li><strong>Dirección:</strong> Callosa de Segura (Alicante)</li>
                <li><strong>Teléfono:</strong> 000 000 000</li>
                <li><strong>Email:</strong> info@escuelademusica.com</li>
                <li><strong>Horario:</strong> Lunes a Viernes de 17:00 a 21:00</li>
                
            </ul><br>


            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d784.644217242272!2d-0.8754625303415052!3d38.12677729824369!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd63a2dcf71b9b07%3A0xd1f3025e846d0b84!2sC.%20La%20Filarmonica%2C%207%2C%2003360%20Callosa%20de%20Segura%2C%20Alicante!5e0!3m2!1ses!2ses!4v1777840315165!5m2!1ses!2ses"
                width="90%"
                height="200"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </section>

        <section class="contacto-form">

            <h2>Escríbenos</h2>
            

            <form class="form-contacto" method="POST" action="contacto.php">

                <input type="text" name="nombre" placeholder="Nombre" required>

                <input type="number" name="telefono" placeholder="Teléfono" required>

                <input type="email" name="email" placeholder="Email" required>

                <textarea name="mensaje" placeholder="Mensaje" rows="6" required></textarea>

                <button type="submit">Enviar mensaje</button>

            </form>

        </section>
    </div>
</main>


<?php include "plantillas/footer.php"; ?>