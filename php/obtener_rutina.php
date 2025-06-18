<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'routine' => null];

// Permitir solicitudes GET (o OPTIONS para pre-vuelo CORS)
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// Si es una solicitud OPTIONS, solo envía las cabeceras CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

// Obtener el ID de la rutina desde los parámetros de la URL (GET)
$routineId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (empty($routineId)) {
    $response['message'] = 'ID de rutina no proporcionado.';
    echo json_encode($response);
    exit;
}

$jsonFile = '../json/rutinas.json'; // Asegúrate de que esta ruta sea correcta

// Verificar si el archivo JSON existe
if (!file_exists($jsonFile)) {
    $response['message'] = 'Archivo de rutinas no encontrado.';
    echo json_encode($response);
    exit;
}

// Leer y decodificar el JSON
$jsonData = file_get_contents($jsonFile);
$currentData = json_decode($jsonData, true);

if ($currentData === null && json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'Error al decodificar el archivo JSON de rutinas.';
    echo json_encode($response);
    exit;
}

// Asegurarse de que la clave 'rutinas' existe y es un array
if (!isset($currentData['rutinas']) || !is_array($currentData['rutinas'])) {
    $response['message'] = 'Formato de archivo JSON inválido: la clave "rutinas" no existe o no es un array.';
    echo json_encode($response);
    exit;
}

$foundRoutine = null;

// Buscar la rutina por su ID
foreach ($currentData['rutinas'] as $routine) {
    if (isset($routine['id']) && $routine['id'] === $routineId) {
        $foundRoutine = $routine;
        break; // Rutina encontrada, salimos del bucle
    }
}

if ($foundRoutine) {
    $response['success'] = true;
    $response['message'] = 'Rutina encontrada exitosamente.';
    $response['routine'] = $foundRoutine;
} else {
    $response['message'] = 'Rutina no encontrada con el ID proporcionado.';
}

echo json_encode($response);
?>