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

// Leer los ejercicios existentes
$ejercicios = leerJson($jsonFilePath);

// Devolver la lista de ejercicios
header('Content-Type: application/json');
echo json_encode($ejercicios);
?>
