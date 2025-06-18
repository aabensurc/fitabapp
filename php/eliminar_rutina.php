<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Si el Content-Type no fue application/json, intenta usar $_POST (aunque para delete es común JSON)
if (empty($input)) {
    $input = $_POST;
}

// Validar que el ID de la rutina está presente
$routineId = isset($input['id']) ? $input['id'] : null;

if (empty($routineId)) {
    $response['message'] = 'ID de rutina no proporcionado para la eliminación.';
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

$found = false;
$updatedRoutines = [];

// Iterar sobre las rutinas para encontrar y excluir la que se va a eliminar
foreach ($currentData['rutinas'] as $routine) {
    // Compara solo el ID para la eliminación
    if (isset($routine['id']) && $routine['id'] == $routineId) {
        $found = true;
        // Esta rutina se omite, es decir, se "elimina"
    } else {
        $updatedRoutines[] = $routine; // Las rutinas no coincidentes se mantienen
    }
}

if (!$found) {
    $response['message'] = 'Rutina con el ID especificado no encontrada para eliminar.';
    echo json_encode($response);
    exit;
}

// Asignar el array de rutinas actualizado de nuevo a la clave 'rutinas'
$currentData['rutinas'] = $updatedRoutines;

// Guardar los datos actualizados en el archivo JSON
if (file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))) {
    $response['success'] = true;
    $response['message'] = 'Rutina eliminada exitosamente.';
} else {
    $response['message'] = 'Error al guardar los cambios después de eliminar la rutina. Verifique permisos.';
}

echo json_encode($response);
?>