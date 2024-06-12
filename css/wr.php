<?php

// Permitir solicitudes desde cualquier origen (no-cors)
header("Access-Control-Allow-Origin: *");
// Indicar que no se pueden incluir credenciales en las solicitudes (no-cors)
header("Access-Control-Allow-Credentials: true");

// Función para verificar el formato del token
function validarToken($token) {
    return preg_match('/^[A-Z]\d{2}[A-Z]{2}$/', $token);
}

// Verificar si se proporcionó un token válido
if (isset($_GET['r']) && validarToken($_GET['r'])) {
    // Obtener los parámetros
    $token = $_GET['r'];
    $par1 = isset($_GET['1']) ? $_GET['1'] : '';
    $par2 = isset($_GET['2']) ? $_GET['2'] : '';
    $par3 = isset($_GET['3']) ? $_GET['3'] : '';

    // Crear el array para el JSON
    $data = [
        [
            "ssid" => $token,
            "Updated" => "received!",
            "par1" => $par1,
            "par2" => $par2,
            "par3" => $par3
        ]
    ];

    // Convertir el array a formato JSON
    $json = json_encode($data, JSON_PRETTY_PRINT);

    // Crear el nombre del archivo de forma segura
    $nombreArchivo = 'json/' . preg_replace('/[^A-Za-z0-9\-]/', '', $token) . '-RC.json';

    // Escribir el JSON en un archivo con el nombre del token
    $archivo = fopen($nombreArchivo, 'w');
    fwrite($archivo, $json);
    fclose($archivo);

    // Responder con un código de estado 200 OK
    http_response_code(200);
} else {
    // Responder con un código de estado 400 Bad Request
    http_response_code(400);
    echo "Solicitud inválida.";
}
?>
