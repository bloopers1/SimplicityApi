<?php
function obtenerArchivosJSON() {
    return glob('json/*.json');
}

function cargarDatos($archivo) {
    if (!empty($archivo)) {
        return json_decode(file_get_contents($archivo), true);
    } else {
        return [];
    }
}

function guardarDatos($archivo, $nuevosDatos) {
    if (!empty($archivo)) {
        file_put_contents($archivo, json_encode($nuevosDatos, JSON_PRETTY_PRINT));
    } else {
        echo "<script>alert('No se ha seleccionado ningún archivo.');</script>";
    }
}

function validarDatos($datos) {
    return !empty($datos['ssid']) && !empty($datos['Updated']) && !empty($datos['par1']) && !empty($datos['par2']) && !empty($datos['par3']);
}

function borrarArchivosJSON() {
    $archivos = glob('json/*.json');
    foreach ($archivos as $archivo) {
        unlink($archivo);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['borrar_jsons'])) {
        borrarArchivosJSON();
    } else {
        if (isset($_POST['archivo'])) {
            $archivoSeleccionado = $_POST['archivo'];
            $datos = cargarDatos($archivoSeleccionado);
        } else {
            $datos = [];
        }

        if (validarDatos($_POST)) {
            $datos[0]['ssid'] = $_POST['ssid'];
            $datos[0]['Updated'] = $_POST['Updated'];
            $datos[0]['par1'] = $_POST['par1'];
            $datos[0]['par2'] = $_POST['par2'];
            $datos[0]['par3'] = $_POST['par3'];
            guardarDatos($archivoSeleccionado, $datos);
        } else {
            echo "<script>alert('Por favor, complete todos los campos.');</script>";
        }
    }

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

$archivosJSON = obtenerArchivosJSON();
$datos = !empty($archivosJSON) ? cargarDatos($archivosJSON[0]) : [];

if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    if (file_exists($archivo)) {
        echo json_encode(cargarDatos($archivo));
    } else {
        echo json_encode([]);
    }
    exit();
}

if (isset($_GET['obtener_archivos'])) {
    echo json_encode(array_map(function($archivo) {
        return basename($archivo, '.json');
    }, obtenerArchivosJSON()));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .container {
            max-width: 1000px;
            margin-top: 20px;
        }
        .file-container, .form-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            height: 100%;
        }
        .list-group-item {
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .online-icon {
            width: 10px;
            height: 10px;
            background-color: green;
            border-radius: 50%;
            margin-right: 10px;
        }
        .row {
            height: 100vh;
        }
        .col-md-6 {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Editar datos</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="file-container">
                    <h4 class="mb-4 text-center">Archivos disponibles</h4>
                    <div class="list-group" id="fileList">
                        <?php foreach ($archivosJSON as $archivo) : 
                            $archivoDatos = cargarDatos($archivo);
                        ?>
                            <a href="#" class="list-group-item list-group-item-action" onclick="actualizarTextBoxes('<?php echo $archivo; ?>')">
                                <span class="online-icon"></span>
                                <span><?php echo basename($archivo, '.json'); ?>
                                <?php if(!empty($archivoDatos)) : ?>
                                    - SSID: <?php echo $archivoDatos[0]['ssid']; ?>, Updated: <?php echo $archivoDatos[0]['Updated']; ?>
                                <?php endif; ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-container">
                    <h4 class="mb-4 text-center">Editor de datos</h4>
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <input type="hidden" name="archivo" id="archivo">
                        <div class="mb-3">
                            <label for="ssid" class="form-label">SSID:</label>
                            <input type="text" id="ssid" name="ssid" class="form-control" value="<?php echo isset($datos[0]['ssid']) ? $datos[0]['ssid'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="Updated" class="form-label">Updated:</label>
                            <input type="text" id="Updated" name="Updated" class="form-control" value="<?php echo isset($datos[0]['Updated']) ? $datos[0]['Updated'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="par1" class="form-label">Parámetro 1:</label>
                            <input type="text" id="par1" name="par1" class="form-control" value="<?php echo isset($datos[0]['par1']) ? $datos[0]['par1'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="par2" class="form-label">Parámetro 2:</label>
                            <input type="text" id="par2" name="par2" class="form-control" value="<?php echo isset($datos[0]['par2']) ? $datos[0]['par2'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="par3" class="form-label">Parámetro 3:</label>
                            <input type="text" id="par3" name="par3" class="form-control" value="<?php echo isset($datos[0]['par3']) ? $datos[0]['par3'] : ''; ?>">
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <button type="submit" name="borrar_jsons" class="btn btn-danger">Borrar JSONs</button>
                            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="btn btn-secondary">Resetear</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        function actualizarTextBoxes(archivo) {
            document.getElementById("archivo").value = archivo;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var datos = JSON.parse(this.responseText);
                    document.getElementById("ssid").value = datos[0]['ssid'];
                    document.getElementById("Updated").value = datos[0]['Updated'];
                    document.getElementById("par1").value = datos[0]['par1'];
                    document.getElementById("par2").value = datos[0]['par2'];
                    document.getElementById("par3").value = datos[0]['par3'];
                }
            };
            xmlhttp.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?archivo=" + encodeURIComponent(archivo), true);
            xmlhttp.send();
        }

        function obtenerArchivosNuevos() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var archivosExistentes = Array.from(document.querySelectorAll('.list-group-item span')).map(span => span.innerText.trim().split(' ')[0]);
                    var nuevosArchivos = JSON.parse(this.responseText);

                    var nuevosDetectados = false;

                    nuevosArchivos.forEach(archivo => {
                        if (!archivosExistentes.includes(archivo)) {
                            nuevosDetectados = true;
                        }
                    });

                    if (nuevosDetectados) {
                        location.reload();
                    }
                }
            };
            xmlhttp.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?obtener_archivos", true);
            xmlhttp.send();
        }

        setInterval(obtenerArchivosNuevos, 5000);
    </script>
</body>
</html>