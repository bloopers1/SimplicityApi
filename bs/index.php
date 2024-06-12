<?php
// Función para verificar si una IP está en banserv.txt
function verificarIPBaneada($ip) {
    $banserv_content = file_get_contents('banserv.txt');
    $banserv_ips = explode("\n", $banserv_content);
    return in_array($ip, $banserv_ips);
}

// Función para verificar si una IP está en banserv.txt
function verificarIPDesdeTextoPlano($ip) {
    return verificarIPBaneada($ip);
}

// Verifica si se ha proporcionado el parámetro 'b' o 't' o 'v' en la URL
if(isset($_GET['b'])) {
    $ip = $_GET['b'];

    // Escribe la IP en banserv.txt
    file_put_contents('banserv.txt', $ip . "\n", FILE_APPEND);

    // Devuelve una respuesta
    echo json_encode(array("status" => "success", "message" => "IP añadida a banserv.txt"));
    exit;
} elseif(isset($_GET['t'])) {
    $comando = $_GET['t'];
    
    // Verifica el comando proporcionado
    if($comando == 'i') {
        // Obtiene la información necesaria
        $fecha_actual = date('d/m/Y');
        $hora_actual = date('H:i:s');
        $ip_usuario = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No referer';

        // Formatea la línea para escribir en el archivo global.txt
        $linea_global = "$fecha_actual $hora_actual >IP:$ip_usuario >REF:$referer >UserA:$user_agent\n";

        // Escribe la línea en el archivo global.txt
        file_put_contents('global.txt', $linea_global, FILE_APPEND);

        // Devuelve una respuesta
        echo json_encode(array("status" => "success", "message" => "Comando ejecutado correctamente"));
    } else {
        // Si se proporciona un comando no reconocido
        http_response_code(400);
        echo json_encode(array("status" => "error", "message" => "Comando no reconocido"));
    }
} elseif(isset($_GET['v'])) {
    $ip = $_GET['v'];
    
    // Verifica si la IP está en banserv.txt
    if(verificarIPDesdeTextoPlano($ip)) {
        // Si la IP está en la lista de IPs baneadas
        http_response_code(403);
        echo json_encode(array("status" => "error", "message" => "Denied"));
    } else {
        // Si la IP no está en la lista de IPs baneadas
        echo json_encode(array("status" => "success", "message" => "200 OK"));
    }
} else {
    // Si no se proporciona el parámetro 'b', 't' o 'v' en la URL
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "Parámetro 'b', 't' o 'v' no proporcionado"));
}
?>
