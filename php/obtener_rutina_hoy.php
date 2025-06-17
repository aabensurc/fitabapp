<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'routine' => null];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = 'ID de rutina no proporcionado.';
    echo json_encode($response);
    exit;
}

$routineId = $_GET['id'];
$jsonFile = '../json/rutinas_custom.json';

if (!file_exists($jsonFile)) {
    $response['message'] = 'El archivo de rutinas no existe.';
    echo json_encode($response);
    exit;
}

$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'Error al decodificar el JSON de rutinas.';
    echo json_encode($response);
    exit;
}

$foundRoutine = null;
foreach ($data['rutinas'] as $routine) {
    if (isset($routine['id']) && $routine['id'] == $routineId) {
        $foundRoutine = $routine;
        break;
    }
}

if ($foundRoutine) {
    $response['success'] = true;
    $response['message'] = 'Rutina encontrada exitosamente.';
    $response['routine'] = $foundRoutine;
} else {
    $response['message'] = 'Rutina no encontrada.';
}

echo json_encode($response);
?>