document.addEventListener("DOMContentLoaded", () => {
    verificarSesion();
    cargarServicios();
});

// Función para ver si hay un usuario logueado
async function verificarSesion() {
    const nav = document.getElementById('auth-nav');
    
    try {
        // Llamamos al controlador de PHP (tienes que crear esta ruta en tu index.php)
        const response = await fetch('index.php?ver=check_session');
        const data = await response.json();

        if (data.logueado) {
            nav.innerHTML = `
                <span>👤 Hola, ${data.nombre}</span>
                <a href="index.php?ver=logout" class="btn-salir">Cerrar Sesión</a>
            `;
        } else {
            nav.innerHTML = `
                <a href="index.php?ver=login">Iniciar Sesión</a>
            `;
        }
    } catch (error) {
        nav.innerHTML = '<a href="index.php?ver=login">Iniciar Sesión</a>';
    }
}

// Función para traer los servicios de la Base de Datos
async function cargarServicios() {
    const grid = document.getElementById('grid-servicios');

    try {
        const response = await fetch('index.php?ver=get_servicios_json');
        const servicios = await response.json();

        if (servicios.length === 0) {
            grid.innerHTML = "<p>No hay servicios disponibles.</p>";
            return;
        }

        // Dibujamos cada tarjeta
        grid.innerHTML = servicios.map(serv => `
            <div class="tarjeta">
                <h3>${serv.nombre}</h3>
                <p class="precio">$${parseFloat(serv.precio).toLocaleString()}</p>
                <p class="desc">${serv.descripcion}</p>
                <small>⏱ ${serv.duracion_minutos} mins</small>
                <br>
                <button onclick="irAReservar(${serv.id})">Reservar</button>
            </div>
        `).join('');

    } catch (error) {
        grid.innerHTML = "<p>Error al conectar con el servidor.</p>";
    }
}

function irAReservar(id) {
    window.location.href = `index.php?ver=reservar&servicio_id=${id}`;
}