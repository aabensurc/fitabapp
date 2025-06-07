<?php
// Ruta del archivo JSON de rutinas
$jsonFilePath = '../json/rutinas.json'; // Asegúrate que esta ruta sea correcta desde la ubicación de este PHP

// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
        return ['rutinas' => []]; // Si el archivo no existe, retornar una estructura base
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
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 
    file_put_contents($filePath, $jsonData);
}

// Configurar cabeceras CORS para permitir solicitudes desde el frontend (si son dominios diferentes)
header('Access-Control-Allow-Origin: *'); // Permite cualquier origen. En producción, especifica tu dominio.
header('Content-Type: application/json'); // Indica que la respuesta es JSON
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


// Leer las rutinas existentes
$rutinasData = leerJson($jsonFilePath);

// Obtener los datos enviados por POST
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$url_imagen = $_POST['url_imagen'] ?? '';
$ejerciciosJson = $_POST['ejercicios'] ?? '[]'; // Los ejercicios se envían como JSON string

// Decodificar el string JSON de ejercicios a un array de PHP
$ejercicios = json_decode($ejerciciosJson, true);

// Verificar que los datos necesarios estén presentes
if (!empty($nombre) && !empty($descripcion) && !empty($tipo) && is_array($ejercicios)) {

    // Generar un nuevo ID para la rutina
    $newId = 1;
    if (!empty($rutinasData['rutinas'])) {
        // Encontrar el ID más grande para asegurar que el nuevo ID sea único y secuencial
        $maxId = 0;
        foreach ($rutinasData['rutinas'] as $rutina) {
            if ($rutina['id'] > $maxId) {
                $maxId = $rutina['id'];
            }
        }
        $newId = $maxId + 1;
    }

    // Crear la nueva rutina
    $nuevaRutina = [
        "id" => $newId,
        "nombre" => $nombre,
        "descripcion" => $descripcion,
        "tipo" => $tipo,
        "url_imagen" => $url_imagen,
        "ejercicios" => $ejercicios
    ];

    // Agregar la nueva rutina al array de rutinas
    $rutinasData['rutinas'][] = $nuevaRutina;

    // Escribir las rutinas actualizadas de nuevo en el archivo JSON
    escribirJson($jsonFilePath, $rutinasData);

    echo json_encode(['success' => true, 'message' => 'Rutina agregada exitosamente.', 'id' => $newId]);

} else {
    // Si faltan datos, enviar un mensaje de error JSON
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios para registrar la rutina o los ejercicios no son válidos.']);
}
?>