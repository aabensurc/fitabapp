<?php
// Configurar cabeceras CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Ruta del archivo JSON de rutinas
$jsonFilePath = '../json/rutinas.json'; // Asegúrate que esta ruta sea correcta

// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
        // Si el archivo no existe, crea una estructura base y retorna
        file_put_contents($filePath, json_encode(['rutinas' => []], JSON_PRETTY_PRINT));
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
    // JSON_UNESCAPED_UNICODE para caracteres UTF-8 sin escapar (acentos, ñ)
    // JSON_UNESCAPED_SLASHES para que las barras en URLs no se escapen
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents($filePath, $jsonData); // Devuelve el número de bytes escritos o false
}

// Si es una solicitud OPTIONS (pre-vuelo CORS), simplemente termina
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// Obtener los datos enviados por POST
// Usamos $_POST directamente ya que el frontend envía FormData
$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$url_imagen = $_POST['url_imagen'] ?? '';
$ejerciciosJson = $_POST['ejercicios'] ?? '[]'; // Ejercicios como JSON string

// Decodificar el string JSON de ejercicios a un array de PHP
$ejercicios = json_decode($ejerciciosJson, true);

// Validar datos esenciales
if ($id === null || empty($nombre) || empty($descripcion) || empty($tipo) || !is_array($ejercicios)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios o los ejercicios no son válidos.']);
    exit;
}

// Leer las rutinas existentes
$rutinasData = leerJson($jsonFilePath);

$foundAndUpdated = false;
$updatedRutinas = [];

// Buscar la rutina por ID y actualizar sus datos
foreach ($rutinasData['rutinas'] as $index => $rutina) {
    if (isset($rutina['id']) && $rutina['id'] === $id) {
        // Actualizar todos los campos de la rutina
        $rutinasData['rutinas'][$index]['nombre'] = $nombre;
        $rutinasData['rutinas'][$index]['descripcion'] = $descripcion;
        $rutinasData['rutinas'][$index]['tipo'] = $tipo;
        $rutinasData['rutinas'][$index]['url_imagen'] = $url_imagen;
        $rutinasData['rutinas'][$index]['ejercicios'] = $ejercicios;
        $foundAndUpdated = true;
        break; // Rutina encontrada y actualizada, salimos del bucle
    }
}

if (!$foundAndUpdated) {
    echo json_encode(['success' => false, 'message' => 'Rutina no encontrada con el ID especificado.']);
    exit;
}

// Escribir las rutinas actualizadas de nuevo en el archivo JSON
if (escribirJson($jsonFilePath, $rutinasData)) {
    echo json_encode(['success' => true, 'message' => 'Rutina actualizada exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar los cambios en el archivo JSON. Verifique los permisos de escritura.']);
}
?>