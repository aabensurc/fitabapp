<?php
session_start();

// Leer archivo JSON con usuarios
$usuarios = json_decode(file_get_contents('../json/usuarios.json'), true);

// Obtener datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar si los datos ingresados son correctos
foreach ($usuarios as $usuario) {
    if ($usuario['nombre_usuario'] === $username && $usuario['password'] === $password) {
        // Guardar nombre en la sesión y redirigir a la página principal
        $_SESSION['nombre_usuario'] = $username;
        header("Location: ../index.html");
        exit();
    }
}

// Si las credenciales no coinciden, redirigir de vuelta al login con un error
header("Location: login.html?error=1");
exit();
?>
