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

// Obtener el ID del ejercicio
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Leer los ejercicios existentes
$ejercicios = leerJson($jsonFilePath);

// Buscar el ejercicio por ID
$ejercicioEncontrado = null;
foreach ($ejercicios['ejercicios'] as $ejercicio) {
    if ($ejercicio['id'] === $id) {
        $ejercicioEncontrado = $ejercicio;
        break;
    }
}

// Devolver el ejercicio encontrado
header('Content-Type: application/json');
echo json_encode($ejercicioEncontrado);
?>
