<?php
function cargarDatos($archivo) {
    return json_decode(file_get_contents($archivo), true);
}

if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    echo json_encode(cargarDatos($archivo));
}
?>
