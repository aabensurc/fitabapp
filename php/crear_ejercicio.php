<?php
// Ruta del archivo JSON
$jsonFilePath = '../json/ejercicios.json';
$uploadDir = '../videos/ejercicios/'; // Ruta a la carpeta de videos

// Función para leer el archivo JSON
function leerJson($filePath) {
    if (!file_exists($filePath)) {
        // Si el archivo no existe, retornar un array vacío
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

// Verificar que los datos necesarios estén presentes
if (!empty($_POST['nombre']) &&
    !empty($_POST['descripcion']) &&
    !empty($_POST['musculos_involucrados']) &&
    !empty($_POST['imagen_url']) &&
    isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $imagen_url = $_POST['imagen_url'];
    $musculos_involucrados = json_decode($_POST['musculos_involucrados'], true);
    $videoFile = $_FILES['video_file'];

    // Obtener la extensión del archivo
    $fileExt = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
    // Generar un nombre de archivo único (puedes usar el nombre original si lo prefieres)
    $fileName = pathinfo($videoFile['name'], PATHINFO_FILENAME) . '.' . $fileExt;
    $destinationPath = $uploadDir . $fileName;

    // Mover el archivo subido a la carpeta de destino
    if (move_uploaded_file($videoFile['tmp_name'], $destinationPath)) {
        // Crear un nuevo ejercicio a agregar
        $nuevoEjercicio = [
            "id" => count($ejercicios['ejercicios']) + 1, // Asignar un nuevo ID
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "musculos_involucrados" => $musculos_involucrados,
            "imagen_url" => $imagen_url,
            "video_url" => 'videos/ejercicios/' . $fileName // Guardar la ruta relativa al archivo
        ];

        // Agregar el nuevo ejercicio al array de ejercicios
        $ejercicios['ejercicios'][] = $nuevoEjercicio;

        // Escribir los ejercicios actualizados de nuevo en el archivo JSON
        escribirJson($jsonFilePath, $ejercicios);

        echo "Ejercicio y video agregado exitosamente.";
    } else {
        echo "Error al guardar el archivo de video.";
    }

} else {
    echo "Faltan datos o no se subió el archivo de video.";
}
?>