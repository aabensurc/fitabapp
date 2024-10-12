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

// Obtener el ID del ejercicio a eliminar
$datosRecibidos = json_decode(file_get_contents('php://input'), true);
$id = $datosRecibidos['id'];

// Buscar el índice del ejercicio por ID y eliminarlo
foreach ($ejercicios['ejercicios'] as $key => $ejercicio) {
    if ($ejercicio['id'] == $id) {
        unset($ejercicios['ejercicios'][$key]);
        // Reindexar el array
        $ejercicios['ejercicios'] = array_values($ejercicios['ejercicios']);
        break;
    }
}

// Guardar los cambios en el archivo JSON
escribirJson($jsonFilePath, $ejercicios);

// Devolver un mensaje de éxito
echo "Ejercicio eliminado con éxito.";
?>
