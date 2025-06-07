<?php
// Ruta del archivo JSON de rutinas personalizadas
$jsonFilePath = '../json/rutinas_custom.json'; // Ajusta esta ruta si es necesario

// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
        // Si el archivo no existe, retornar una estructura base
        return ['rutinas' => []];
    }
    $jsonData = file_get_contents($filePath);
    $data = json_decode($jsonData, true);
    // Asegurarse de que 'rutinas' sea un array
    if (!isset($data['rutinas']) || !is_array($data['rutinas'])) {
        $data['rutinas'] = [];
    }
    return $data;
}

// Función para escribir en el archivo JSON
function escribirJson($filePath, $data) {
    // JSON_PRETTY_PRINT para formato legible
    // JSON_UNESCAPED_UNICODE para no escapar caracteres como 'ñ'
    // JSON_UNESCAPED_SLASHES para no escapar las barras en las URLs
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    file_put_contents($filePath, $jsonData);
}

// Configurar cabeceras CORS (necesario si tu frontend y backend están en dominios/puertos diferentes)
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Leer las rutinas existentes
$rutinasCustomData = leerJson($jsonFilePath);

// Obtener los datos enviados por POST
$username = $_POST['username'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$url_imagen = $_POST['url_imagen'] ?? '';
$ejerciciosJson = $_POST['ejercicios'] ?? '[]'; // Los ejercicios se envían como JSON string

// Decodificar el string JSON de ejercicios a un array de PHP
$ejercicios = json_decode($ejerciciosJson, true);

// Verificar que los datos necesarios estén presentes
if (
    !empty($username) && 
    !empty($fecha) && 
    !empty($nombre) && 
    !empty($descripcion) && 
    !empty($tipo) && 
    is_array($ejercicios) && 
    !empty($ejercicios) // Asegurarse de que haya al menos un ejercicio
) {
    // Generar un nuevo ID para la rutina
    // Encuentra el ID más grande existente en rutinas_custom.json y le suma 1
    $newId = 1;
    if (!empty($rutinasCustomData['rutinas'])) {
        $maxId = 0;
        foreach ($rutinasCustomData['rutinas'] as $rutina) {
            if ($rutina['id'] > $maxId) {
                $maxId = $rutina['id'];
            }
        }
        $newId = $maxId + 1;
    }

    // Crear la nueva rutina personalizada
    $nuevaRutina = [
        "username" => $username,
        "id" => $newId,
        "fecha" => $fecha,
        "nombre" => $nombre,
        "descripcion" => $descripcion,
        "tipo" => $tipo,
        "url_imagen" => $url_imagen, // Puede ser una cadena vacía si es opcional
        "ejercicios" => $ejercicios
    ];

    // Agregar la nueva rutina al array de rutinas personalizadas
    $rutinasCustomData['rutinas'][] = $nuevaRutina;

    // Escribir las rutinas actualizadas de nuevo en el archivo JSON
    escribirJson($jsonFilePath, $rutinasCustomData);

    echo json_encode(['success' => true, 'message' => 'Rutina personalizada agregada exitosamente.', 'id' => $newId]);

} else {
    // Si faltan datos o los ejercicios no son válidos, enviar un mensaje de error JSON
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios o los ejercicios no son válidos para registrar la rutina.']);
}
?>