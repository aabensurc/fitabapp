// auth.js

// Función para validar si el usuario está logueado
function checkLogin() {
    if (localStorage.getItem('loggedIn') !== 'true') {
        // Si no está logueado, redirigir a login.html
        window.location.href = 'login.html';
    }
}

// Función para cerrar sesión y redirigir a login.html
function logout() {
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}
