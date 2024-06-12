<?php
// Nombre del archivo a monitorear
$archivo_log = 'log.txt';

// Obtener el contenido del archivo log.txt
$contenido = file_get_contents($archivo_log);

// Devolver el contenido como respuesta
echo $contenido;
?>
