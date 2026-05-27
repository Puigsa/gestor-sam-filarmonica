/*   CALENDARIO   */

document.addEventListener("DOMContentLoaded", function () {
  const calendario = document.getElementById("calendario");

  if (calendario) {
    const calendar = new FullCalendar.Calendar(calendario, {
      initialView: "dayGridMonth",
      // Cambiar a vista de lista o día en móvil
      windowResize: function (view) {
        if (window.innerWidth < 768) {
          calendar.changeView("listMonth");
        } else {
          calendar.changeView("dayGridMonth");
        }
      },
      locale: "es",
      firstDay: 1,
      events: "ajax/eventos_calendario.php",
      eventClick: function (info) {
        mostrarDetalleEvento(info);
      },
    });

    calendar.render();
  }
});

/*  DETALLE DE EVENTOS */

function mostrarDetalleEvento(info) {
  var titulo = info.event.title;

  var hora = info.event.extendedProps.hora;

  var lugar = info.event.extendedProps.lugar;

  var cartel = info.event.extendedProps.cartel;

  var contenedor = document.getElementById("evento-detalle");

  contenedor.innerHTML = "";

  var detalle = document.createElement("div");

  detalle.classList.add("detalle-evento");

  // Imagen
  if (cartel !== "") {
    var imagen = document.createElement("img");

    imagen.src = cartel;

    imagen.alt = titulo;

    imagen.classList.add("detalle-imagen");

    detalle.appendChild(imagen);
  }

  const contenido = document.createElement("div");
  contenido.classList.add("detalle-contenido");

  contenido.innerHTML = `
        <h3>${titulo}</h3>
        <p>${hora}</p>
        <p>${lugar}</p>
    `;

  detalle.appendChild(contenido);

  contenedor.appendChild(detalle);
}

// MENÚ RESPONSIVO

document.addEventListener("DOMContentLoaded", () => {
  const menu = document.getElementById("menu");
  const toggle = document.getElementById("menuToggle");
  const overlay = document.getElementById("overlay");
  const links = document.querySelectorAll(".menu a");

  if (!menu || !toggle || !overlay) return;

  function openMenu() {
    menu.classList.add("active");
    toggle.classList.add("active");
    overlay.classList.add("active");
  }

  function closeMenu() {
    menu.classList.remove("active");
    toggle.classList.remove("active");
    overlay.classList.remove("active");
  }

  toggle.addEventListener("click", () => {
    menu.classList.contains("active") ? closeMenu() : openMenu();
  });

  overlay.addEventListener("click", closeMenu);

  links.forEach((link) => link.addEventListener("click", closeMenu));
});

// CARGA DE PRÓXIMOS EVENTOS
document.addEventListener('DOMContentLoaded', function() {
    const proximos = document.getElementById('proximos-eventos');
    
    if (proximos) {
        fetch('ajax/proximos_eventos.php')
            .then(response => response.json())
            .then(eventos => {
                let html = '<div class="grid-proximos">';
                eventos.forEach(evento => {
                    html += `<div class="detalle-evento">
                        ${evento.cartel ? `<img src="${evento.cartel}" alt="${evento.titulo}">` : ''}
                        <h3>${evento.titulo}</h3>
                        <p>${new Date(evento.fecha).toLocaleDateString('es-ES')} ${evento.hora}</p>
                        <p>${evento.lugar}</p>
                    </div>`;
                });
                html += '</div>';
                proximos.innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
    }
});
// ================================
// VALIDACIÓN FORMULARIOS USUARIOS
// ================================
document.addEventListener('DOMContentLoaded', function () {

    const reglas = {
        nombre:         [/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]{2,50}$/, 'Solo letras, mínimo 2 caracteres'],
        apellidos:      [/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]{2,100}$/, 'Solo letras, mínimo 2 caracteres'],
        email:          [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'Email no válido'],
        password:       [/^.{6,}$/, 'Mínimo 6 caracteres'],
        telefono:       [/^[0-9]{9}$/, 'El teléfono debe tener 9 dígitos'],
        dni:            [/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$|^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/, 'DNI/NIE no válido'],
        direccion:      [/^.{3,}$/, 'Dirección demasiado corta'],
        tutor_nombre:   [/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]{2,50}$/, 'Solo letras, mínimo 2 caracteres'],
        tutor_dni:      [/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$|^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/, 'DNI/NIE no válido'],
        tutor_email:    [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'Email no válido'],
        tutor_telefono: [/^[0-9]{9}$/, 'El teléfono debe tener 9 dígitos'],
    };

    function validarCampo(id) {
        const input = document.getElementById(id);
        const error = document.getElementById('error-' + id);
        if (!input || !error) return true;

        // Si el campo es del tutor y el fieldset está oculto, no validar
        const camposTutor = ['tutor_nombre', 'tutor_dni', 'tutor_email', 'tutor_telefono'];
        if (camposTutor.includes(id)) {
            const fieldset = document.getElementById('campos-tutor');
            if (fieldset && fieldset.style.display === 'none') return true;
        }

        const valor = input.value.trim();
        let valido = true;

        if (input.required && valor === '') {
            valido = false;
        } else if (valor !== '') {
            valido = reglas[id][0].test(valor);
        }

        error.textContent = valido ? '' : reglas[id][1];
        return valido;
    }

    // Validar al perder el foco
    Object.keys(reglas).forEach(function(id) {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('blur', function() {
                validarCampo(id);
            });
        }
    });

    // Validar al enviar el formulario
    ['formCrear', 'formEditar', 'formAprobar'].forEach(function(idForm) {
        const form = document.getElementById(idForm);
        if (form) {
            form.addEventListener('submit', function(e) {
                let formularioValido = true;
                Object.keys(reglas).forEach(function(id) {
                    if (!validarCampo(id)) formularioValido = false;
                });
                if (!formularioValido) e.preventDefault();
            });
        }
    });
});
