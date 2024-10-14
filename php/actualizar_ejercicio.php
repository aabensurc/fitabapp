<?php
// Ruta del archivo JSON
$jsonFilePath = '../json/ejercicios.json';

// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
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

// Obtener los datos del ejercicio actualizado
$datosRecibidos = json_decode(file_get_contents('php://input'), true);
$id = $datosRecibidos['id'];
$nombre = $datosRecibidos['nombre'];
$descripcion = $datosRecibidos['descripcion'];
$musculos_involucrados = $datosRecibidos['musculos_involucrados'];
$imagen_url = $datosRecibidos['imagen_url'];
$video_url = $datosRecibidos['video_url'];

// Buscar el ejercicio por ID y actualizarlo
foreach ($ejercicios['ejercicios'] as &$ejercicio) {
    if ($ejercicio['id'] == $id) {
        $ejercicio['nombre'] = $nombre;
        $ejercicio['descripcion'] = $descripcion;
        $ejercicio['musculos_involucrados'] = $musculos_involucrados;
        $ejercicio['imagen_url'] = $imagen_url;
        $ejercicio['video_url'] = $video_url;
        break;
    }
}

// Guardar los cambios en el archivo JSON
escribirJson($jsonFilePath, $ejercicios);

// Devolver un mensaje de éxito
echo "Ejercicio actualizado con éxito.";
?>
