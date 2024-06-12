<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET
header("Access-Control-Allow-Methods: GET");
// Permitir encabezados personalizados
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Función para eliminar un token de un archivo y agregarlo a otro
function moverToken($token) {
    // Leer el archivo "ok.txt"
    $file = file_get_contents('ok.txt');
    $tokens = explode("\n", $file);
    
    // Verificar si el token existe en el archivo
    if (($key = array_search($token, $tokens)) !== false) {
        // Eliminar el token del array
        unset($tokens[$key]);
        
        // Escribir los tokens actualizados de nuevo en "ok.txt"
        file_put_contents('ok.txt', implode("\n", $tokens));
        
        // Añadir el token a "deleted.txt"
        file_put_contents('deleted.txt', $token . PHP_EOL, FILE_APPEND);
        
        // Agregar mensaje al log
        agregarLog($token, true, "eliminado");
        
        return true;
    }
    
    return false;
}

// Función para agregar un registro al archivo de log
function agregarLog($token, $valido, $accion) {
    $mensaje = date('Y-m-d H:i:s') . " - Token '" . $token . "' ";
    $mensaje .= $valido ? "válido." : "inválido.";
    $mensaje .= " Acción: " . $accion;
    if (!$valido) {
        $mensaje .= " IP: " . $_SERVER['REMOTE_ADDR'];
        // Si el token es inválido, agregar IP a "global.txt"
        file_put_contents('global.txt', $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);
    }
    file_put_contents('log.txt', $mensaje . PHP_EOL, FILE_APPEND);
}

// Verificar si se recibió una solicitud GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar si se recibió un token en el parámetro 'token' de la URL
    if (isset($_GET['token'])) {
        // Obtener el token de la URL
        $token = strtoupper($_GET['token']); // Convertir el token a mayúsculas
        
        // Verificar si el token existe y moverlo a "deleted.txt" si es válido
        $tokenValido = moverToken($token);
        
        if ($tokenValido) {
            // Si el token existe y fue movido, enviar código de éxito
            http_response_code(200);
            $response = array(
                "message" => "OK",
                "status" => "200 OK"
            );
        } else {
            // Si el token no existe, enviar código de no encontrado
            http_response_code(404);
            $response = array(
                "message" => "Token not found",
                "status" => "404 Not Found"
            );
            // Agregar mensaje al log para token inválido
            agregarLog($token, false, "add2ban");
        }
    } else {
        // Si no se proporcionó ningún token en la URL, enviar código de solicitud incorrecta
        http_response_code(400);
        $response = array(
            "message" => "Bad Request - Token missing",
            "status" => "400 Bad Request"
        );
        // Agregar mensaje al log para solicitud inválida
        agregarLog("", false, "add2ban");
    }
} else {
    // Si no es una solicitud GET, enviar una respuesta de método no permitido
    http_response_code(405);
    $response = array(
        "message" => "Method Not Allowed",
        "status" => "405 Method Not Allowed"
    );
    // Agregar mensaje al log para solicitud inválida
    agregarLog("", false, "add2ban");
}

// Especificar que la respuesta será en formato JSON
header("Content-Type: application/json; charset=UTF-8");

// Enviar la respuesta en formato JSON
echo json_encode($response);
?>
