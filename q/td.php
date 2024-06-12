<?php

// Permitir el acceso desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir los métodos GET y POST desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST");
// Tipo de contenido de la respuesta en JSON
header("Content-Type: application/json");

// Ruta del archivo ok.txt y okload.txt
$okFile = 'ok.txt';
$okLoadFile = 'okload.txt';
$deliveredFile = 'delivered.txt';

// Verificar si se ha enviado el parámetro loadtokens por GET
if (isset($_GET['loadtokens']) && $_GET['loadtokens'] === '!') {
    // Duplicar el contenido de ok.txt en okload.txt
    copy($okFile, $okLoadFile);
}

// Verificar si se ha enviado el parámetro newtoken por GET
if (isset($_GET['newtoken']) && $_GET['newtoken'] === '!') {
    // Leer el contenido de okload.txt
    $tokens = file($okLoadFile, FILE_IGNORE_NEW_LINES);

    if (!empty($tokens)) {
        // Tomar el primer token
        $token = $tokens[0];

        // Eliminar el primer token del array
        array_shift($tokens);

        // Escribir el token en delivered.txt
        file_put_contents($deliveredFile, $token . PHP_EOL, FILE_APPEND);

        // Actualizar el archivo okload.txt con los tokens restantes
        file_put_contents($okLoadFile, implode(PHP_EOL, $tokens));

        // Responder en formato JSON con el token
        echo json_encode(['token' => $token]);
    } else {
        // Si no hay tokens disponibles, responder con un mensaje de error
        http_response_code(404);
        echo json_encode(['error' => 'No hay tokens disponibles']);
    }
}

// Verificar si se ha enviado el parámetro verify con un token por GET
if (isset($_GET['verify'])) {
    $tokenToVerify = $_GET['verify'];

    // Leer el contenido de ok.txt
    $tokens = file($okFile, FILE_IGNORE_NEW_LINES);

    // Verificar si el token existe en el archivo ok.txt
    if (in_array(trim($tokenToVerify), $tokens)) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>
