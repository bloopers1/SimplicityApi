<?php
function obtenerArchivosJSON() {
    return glob('json/*.json');
}

function cargarDatos($archivo) {
    return json_decode(file_get_contents($archivo), true);
}

function guardarDatos($archivo, $nuevosDatos) {
    file_put_contents($archivo, json_encode($nuevosDatos, JSON_PRETTY_PRINT));
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
    if(isset($_POST['borrar_jsons'])) {
        borrarArchivosJSON();
    } else {
        $archivoSeleccionado = $_POST['archivo'];
        $datos = cargarDatos($archivoSeleccionado);

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar datos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="text-3xl font-bold mb-4 text-center">Editar datos</h1>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div>
                <label for="archivo" class="form-label">Seleccionar archivo:</label>
                <div class="relative">
                    <select id="archivo" name="archivo" class="form-input pl-3 pr-8" onchange="actualizarTextBoxes()">
                        <?php foreach ($archivosJSON as $archivo) : 
                            $archivoDatos = cargarDatos($archivo);
                        ?>
                            <option value="<?php echo $archivo; ?>">
                                <?php echo basename($archivo); ?>
                                <?php if(!empty($archivoDatos)) : ?>
                                    - SSID: <?php echo $archivoDatos[0]['ssid']; ?>, Updated: <?php echo $archivoDatos[0]['Updated']; ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label for="ssid" class="form-label">SSID:</label>
                <input type="text" id="ssid" name="ssid" class="form-input" value="<?php echo isset($datos[0]['ssid']) ? $datos[0]['ssid'] : ''; ?>">
            </div>
            <div>
                <label for="Updated" class="form-label">Updated:</label>
                <input type="text" id="Updated" name="Updated" class="form-input" value="<?php echo isset($datos[0]['Updated']) ? $datos[0]['Updated'] : ''; ?>">
            </div>
            <div>
                <label for="par1" class="form-label">Parámetro 1:</label>
                <input type="text" id="par1" name="par1" class="form-input" value="<?php echo isset($datos[0]['par1']) ? $datos[0]['par1'] : ''; ?>">
            </div>
            <div>
                <label for="par2" class="form-label">Parámetro 2:</label>
                <input type="text" id="par2" name="par2" class="form-input" value="<?php echo isset($datos[0]['par2']) ? $datos[0]['par2'] : ''; ?>">
            </div>
            <div>
                <label for="par3" class="form-label">Parámetro 3:</label>
                <input type="text" id="par3" name="par3" class="form-input" value="<?php echo isset($datos[0]['par3']) ? $datos[0]['par3'] : ''; ?>">
            </div>
            <div class="text-center mt-6">
                <button type="submit" class="form-button">Actualizar</button>
                <button type="submit" name="borrar_jsons" class="form-button">Borrar JSONs</button>
                <a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="reset-button">Resetear</a>
            </div>
        </form>
    </div>
    <script>
        function actualizarTextBoxes() {
            var selectBox = document.getElementById("archivo");
            var selectedFile = selectBox.value;
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
            xmlhttp.open("GET", "get_data.php?archivo=" + encodeURIComponent(selectedFile), true);
            xmlhttp.send();
        }

        // Initialize with the first file's data
        window.onload = function() {
            actualizarTextBoxes();
        };
    </script>
</body>
</html>

<?php
if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    echo json_encode(cargarDatos($archivo));
}
?>
