<?php
// Función para escribir la IP en global.txt
function logIP($ip) {
    $log_message = "Dirección IP: $ip\n";
    file_put_contents("global.txt", $log_message, FILE_APPEND);
}

// Obtener la fecha y hora actual
$date_time = date("Y-m-d H:i:s");

// Obtener la dirección IP del cliente
$client_ip = $_SERVER['REMOTE_ADDR'];

// Obtener la solicitud actual
$request = $_SERVER['REQUEST_URI'];

// Construir el mensaje de registro
$log_message = "[$date_time] Solicitud recibida desde $client_ip: $request\n";

// Escribir el mensaje en el archivo de registro
file_put_contents("log.txt", $log_message, FILE_APPEND);

// Verifica si se ha enviado la solicitud GET con el parámetro "3d"
if(isset($_GET['3d'])) {
    // Lee el valor del parámetro "3d"
    $parametro_3d = $_GET['3d'];
    
    // Verifica que el token tenga el formato correcto (una letra seguida por dos números y dos letras)
    if(preg_match('/^[A-Za-z]\d{2}[A-Za-z]{2}$/', $parametro_3d)) {
        // Define la ruta completa del archivo JSON (carpeta actual + carpeta json + nombre de archivo)
        $file_path = './json/' . $parametro_3d . '.json';

        // Verifica si el archivo ya existe
        if(file_exists($file_path)) {
            // Si el archivo existe, lee su contenido y lo imprime
            $json_content = file_get_contents($file_path);
            echo $json_content;
        } else {
            // Si el archivo no existe, crea el contenido del archivo JSON con el formato especificado
            $json_content = [
                [
                    "ssid" => $parametro_3d,
                    "Updated" => "NO",
                    "par1" => "??",
                    "par2" => "??",
                    "par3" => "??"
                ]
            ];
            
            // Convierte el contenido a formato JSON
            $json_data = json_encode($json_content, JSON_PRETTY_PRINT);
            
            // Intenta escribir el contenido JSON en el archivo
            if(file_put_contents($file_path, $json_data) !== false) {
                // Agregar registro al archivo de registro
                $log_message = "[$date_time] Se creó un nuevo archivo JSON: " . $parametro_3d . ".json\n";
                file_put_contents("log.txt", $log_message, FILE_APPEND);
                
                // Devuelve el contenido recién creado en formato JSON
                echo $json_data;
            } else {
                // Devuelve una respuesta indicando que hubo un error al crear el archivo
                http_response_code(500);
                echo json_encode(array("mensaje" => "Error al crear el archivo JSON."));
            }
        }
    } else {
        // Si el formato del token no es válido, devuelve un error
        http_response_code(400);
        echo json_encode(array("mensaje" => "Formato de token inválido. Debe ser una letra seguida por dos números y dos letras."));
        
        // Registra la IP en global.txt
        logIP($client_ip);
    }
} else {
    // Si no se proporciona el parámetro "3d", devuelve un error
    http_response_code(400);
    echo json_encode(array("mensaje" => "Falta el parámetro '3d'."));
}
?>
