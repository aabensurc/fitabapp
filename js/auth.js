// auth.js

// Función para validar si el usuario está logueado y habilitado
function checkLogin() {
    if (localStorage.getItem('loggedIn') !== 'true') {
        // Si no está logueado, redirigir a login.html
        location.replace('login.html');
    } else {
        // Obtener el nombre de usuario desde el localStorage
        const username = localStorage.getItem('username');

        // Hacer una petición para obtener el archivo usuarios.json
        fetch('json/usuarios.json')
            .then(response => response.json())
            .then(data => {
                // Buscar al usuario en la lista de usuarios
                const usuario = data.find(u => u.nombre_usuario === username);

                // Si no se encuentra el usuario o el estado es "Deshabilitado", redirigir a login.html
                if (!usuario || usuario.estado === 'Deshabilitado') {
                    alert('Tu cuenta está deshabilitada. Contacta al administrador.');
                    logout(); // Cerrar sesión y redirigir a login.html
                }
            })
            .catch(error => {
                console.error('Error al cargar el archivo JSON:', error);
                // Si ocurre un error en la consulta, redirigir a login.html como medida de seguridad
                location.replace('login.html');
            });
    }
}

// Función para cerrar sesión y redirigir a login.html
function logout() {
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('username');
    localStorage.removeItem('nombre');
    localStorage.removeItem('apellido');
    localStorage.removeItem('sexo');
    localStorage.removeItem('edad');
    localStorage.removeItem('peso');
    localStorage.removeItem('estatura');
    localStorage.removeItem('estado');
    location.replace('login.html');
}
