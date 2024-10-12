// auth.js

// Función para validar si el usuario está logueado
function checkLogin() {
    if (localStorage.getItem('loggedIn') !== 'true') {
        // Limpiar el historial de la página anterior y redirigir a login.html
        history.replaceState(null, null, 'login.html'); 
        window.location.href = 'login.html';
    }
}

// Función para cerrar sesión y redirigir a login.html
function logout() {
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('username');
    history.replaceState(null, null, 'login.html'); 
    window.location.href = 'login.html';
}
