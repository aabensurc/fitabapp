<?php
// Ruta del archivo JSON
$jsonFilePath = '../json/ejercicios.json';
$uploadDir = '../videos/ejercicios/'; // Ruta a la carpeta de videos

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
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$musculos_involucrados = json_decode($_POST['musculos_involucrados'], true);
$imagen_url = $_POST['imagen_url'];
$nuevo_video_url = '';
$video_anterior = '';

// Procesar la subida del nuevo video si se proporcionó
if (isset($_FILES['update_video_file']) && $_FILES['update_video_file']['error'] === UPLOAD_ERR_OK) {
    $videoFile = $_FILES['update_video_file'];
    $fileExt = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
    $fileName = pathinfo($videoFile['name'], PATHINFO_FILENAME) . '.' . $fileExt;
    $destinationPath = $uploadDir . $fileName;

    if (move_uploaded_file($videoFile['tmp_name'], $destinationPath)) {
        $nuevo_video_url = 'videos/ejercicios/' . $fileName;
    } else {
        echo "Error al guardar el nuevo archivo de video.";
        exit;
    }
}

// Buscar el ejercicio por ID, obtener el nombre del video anterior y actualizar la información
foreach ($ejercicios['ejercicios'] as &$ejercicio) {
    if ($ejercicio['id'] == $id) {
        // Obtener el nombre del video anterior
        $video_anterior = $ejercicio['video_url'];

        $ejercicio['nombre'] = $nombre;
        $ejercicio['descripcion'] = $descripcion;
        $ejercicio['musculos_involucrados'] = $musculos_involucrados;
        $ejercicio['imagen_url'] = $imagen_url;

        // Actualizar la URL del video solo si se subió un nuevo archivo
        if (!empty($nuevo_video_url)) {
            $ejercicio['video_url'] = $nuevo_video_url;
        }
        break;
    }
}

// Guardar los cambios en el archivo JSON
escribirJson($jsonFilePath, $ejercicios);

// Eliminar el video anterior si se subió uno nuevo y existía un archivo
if (!empty($nuevo_video_url) && !empty($video_anterior)) {
    $ruta_archivo_anterior = '../' . $video_anterior; // Construir la ruta completa al archivo anterior
    if (file_exists($ruta_archivo_anterior)) {
        if (unlink($ruta_archivo_anterior)) {
            // Archivo anterior eliminado con éxito (opcionalmente puedes loggear esto)
        } else {
            echo "Advertencia: No se pudo eliminar el archivo de video anterior: " . $ruta_archivo_anterior;
        }
    }
}

// Devolver un mensaje de éxito
echo "Ejercicio actualizado con éxito.";
?>