<?php
// Ruta del archivo JSON
$jsonFilePath = '../json/ejercicios.json';


// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
        // Si el archivo no existe, retornar un array vacío
        return ['ejercicios' => []];
    }
    $jsonData = file_get_contents($filePath);
    return json_decode($jsonData, true);
}

// Función para escribir en el archivo JSON
function escribirJson($filePath, $data) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $jsonData);
}

// Leer los ejercicios existentes
$ejercicios = leerJson($jsonFilePath);

// Obtener el nuevo ejercicio desde el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos necesarios están presentes
if (!empty($data['nombre']) && 
    !empty($data['descripcion']) && 
    !empty($data['tipo']) && 
    !empty($data['musculos_involucrados']) && 
    !empty($data['imagen_url']) && 
    !empty($data['video_url'])) {
    
    // Crear un nuevo ejercicio a agregar
    $nuevoEjercicio = [
        "id" => count($ejercicios['ejercicios']) + 1, // Asignar un nuevo ID
        "nombre" => $data['nombre'],
        "descripcion" => $data['descripcion'],
        "tipo" => $data['tipo'],
        "musculos_involucrados" => $data['musculos_involucrados'],
        "imagen_url" => $data['imagen_url'],
        "video_url" => $data['video_url']
    ];

    // Agregar el nuevo ejercicio al array de ejercicios
    $ejercicios['ejercicios'][] = $nuevoEjercicio;

    // Escribir los ejercicios actualizados de nuevo en el archivo JSON
    escribirJson($jsonFilePath, $ejercicios);

    echo "Ejercicio agregado exitosamente.";
} else {
    echo "Faltan datos para agregar el ejercicio.";
}
?>
