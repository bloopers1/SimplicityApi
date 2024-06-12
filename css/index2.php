<?php

// Función para verificar el formato del token
function validarToken($token) {
    return preg_match('/^[A-Z]\d{2}[A-Z]{2}$/', $token);
}

// Verificar si se proporcionó un token válido
if (isset($_GET['token']) && validarToken($_GET['token'])) {
    // Obtener los parámetros
    $token = $_GET['token'];
    $par1 = isset($_GET[0]) ? $_GET[0] : '';
    $par2 = isset($_GET[1]) ? $_GET[1] : '';
    $par3 = isset($_GET[2]) ? $_GET[2] : '';

    // Crear el array para el JSON
    $data = [
        [
            "Receiver" => $token,
            "Updated" => "received!",
            "par1" => $par1,
            "par2" => $par2,
            "par3" => $par3
        ]
    ];

    // Convertir el array a formato JSON
    $json = json_encode($data, JSON_PRETTY_PRINT);

    // Crear el nombre del archivo
    $nombreArchivo = 'json/' . $token . '.json';

    // Escribir el JSON en un archivo con el nombre del token
    $archivo = fopen($nombreArchivo, 'w');
    fwrite($archivo, $json);
    fclose($archivo);

    echo "Archivo JSON creado correctamente con el nombre: $token.json";
} else {
    echo "Token inválido.";
}
?>
