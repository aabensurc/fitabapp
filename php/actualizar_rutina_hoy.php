<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// If Content-Type is not application/json, try $_POST
if (empty($input)) {
    $input = $_POST;
}

$routineId = isset($input['id']) ? $input['id'] : null;
$username = isset($input['username']) ? trim($input['username']) : '';
$fecha = isset($input['fecha']) ? trim($input['fecha']) : '';
$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
$descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';
$tipo = isset($input['tipo']) ? trim($input['tipo']) : '';
$urlImagen = isset($input['url_imagen']) ? trim($input['url_imagen']) : '';
$ejerciciosJson = isset($input['ejercicios']) ? $input['ejercicios'] : '[]';

if (empty($routineId)) {
    $response['message'] = 'ID de rutina no proporcionado para la actualización.';
    echo json_encode($response);
    exit;
}

if (empty($username) || empty($fecha) || empty($nombre) || empty($descripcion) || empty($tipo) || empty($ejerciciosJson)) {
    $response['message'] = 'Todos los campos obligatorios (usuario, fecha, nombre, descripción, tipo y ejercicios) son requeridos.';
    echo json_encode($response);
    exit;
}

$ejercicios = json_decode($ejerciciosJson, true);

if ($ejercicios === null && json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'Formato de ejercicios inválido.';
    echo json_encode($response);
    exit;
}

if (empty($ejercicios)) {
    $response['message'] = 'La rutina debe contener al menos un ejercicio.';
    echo json_encode($response);
    exit;
}

// Validate each exercise
foreach ($ejercicios as $index => $ejercicio) {
    if (!isset($ejercicio['id_ejercicio']) || !isset($ejercicio['series']) || !isset($ejercicio['medicion']) || !isset($ejercicio['cantidad']) || !isset($ejercicio['tiempo_descanso']['minutos']) || !isset($ejercicio['tiempo_descanso']['segundos'])) {
        $response['message'] = "Ejercicio " . ($index + 1) . " tiene campos incompletos.";
        echo json_encode($response);
        exit;
    }
    if (!is_numeric($ejercicio['series']) || $ejercicio['series'] <= 0) {
        $response['message'] = "Series del ejercicio " . ($index + 1) . " deben ser un número positivo.";
        echo json_encode($response);
        exit;
    }
    if (!is_numeric($ejercicio['cantidad']) || $ejercicio['cantidad'] <= 0) {
        $response['message'] = "Cantidad del ejercicio " . ($index + 1) . " debe ser un número positivo.";
        echo json_encode($response);
        exit;
    }
    if (!is_numeric($ejercicio['tiempo_descanso']['minutos']) || $ejercicio['tiempo_descanso']['minutos'] < 0 || !is_numeric($ejercicio['tiempo_descanso']['segundos']) || $ejercicio['tiempo_descanso']['segundos'] < 0 || $ejercicio['tiempo_descanso']['segundos'] > 59) {
        $response['message'] = "Tiempo de descanso del ejercicio " . ($index + 1) . " es inválido.";
        echo json_encode($response);
        exit;
    }
}


$jsonFile = '../json/rutinas_custom.json';

// Ensure the directory exists
if (!is_dir(dirname($jsonFile))) {
    mkdir(dirname($jsonFile), 0777, true);
}

// Read existing data
$currentData = ['rutinas' => []];
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $decodedData = json_decode($jsonData, true);
    if ($decodedData !== null && json_last_error() === JSON_ERROR_NONE) {
        $currentData = $decodedData;
    }
}

$updated = false;
foreach ($currentData['rutinas'] as $key => $routine) { // Itera sobre la clave 'rutinas'
    if (isset($routine['id']) && $routine['id'] == $routineId) {
        $currentData['rutinas'][$key]['username'] = $username;
        $currentData['rutinas'][$key]['fecha'] = $fecha;
        $currentData['rutinas'][$key]['nombre'] = $nombre; // CAMBIADO a 'nombre'
        $currentData['rutinas'][$key]['descripcion'] = $descripcion;
        $currentData['rutinas'][$key]['tipo'] = $tipo; // CAMBIADO a 'tipo'
        $currentData['rutinas'][$key]['url_imagen'] = $urlImagen;
        $currentData['rutinas'][$key]['ejercicios'] = $ejercicios;
        $updated = true;
        break;
    }
}

if (!$updated) {
    $response['message'] = 'Rutina con el ID especificado no encontrada para actualizar.';
    echo json_encode($response);
    exit;
}

// Save updated data
if (file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT))) {
    $response['success'] = true;
    $response['message'] = 'Rutina actualizada exitosamente.';
} else {
    $response['message'] = 'Error al guardar la rutina actualizada.';
}

echo json_encode($response);
?>