<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php
    $file = 'global.txt';
    $current = file_get_contents($file);
    if (strpos($current, "LOGSEV IS ON") === false) {
        $current .= "LOGSEV IS ON\n";
        file_put_contents($file, $current);
    }
    ?>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4">Monitor de Archivo</h1>
        <div class="mb-4 flex">
            <label for="intervalo" class="font-bold mr-2">Intervalo de verificación (segundos):</label>
            <input type="number" id="intervalo" class="px-2 py-1 border rounded-md mr-2" value="10">
            <button id="btnConfirmarIntervalo" class="px-4 py-2 bg-blue-500 text-white rounded-md">Confirmar</button>
        </div>
        <p class="mb-4">Solo se mostrará la última línea en el archivo <strong>global.txt</strong>:</p>
        <div id="logContainer" class="border border-gray-300 p-4 bg-white">
            <h5 class="font-bold">global.txt</h5>
        </div>
    </div>

    <!-- Barra de notificaciones -->
    <div class="fixed bottom-5 left-5">
        <h2 class="font-bold mb-2">Registro de Notificaciones</h2>
        <ul id="notificationLog" class="list-disc pl-5">
            <!-- Las notificaciones nuevas se mostrarán aquí -->
        </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        var ultimaLinea = "";
        var intervaloVerificacion = 10; // Intervalo predeterminado de 10 segundos

        function mostrarNotificacion(mensaje) {
            toastr.info(mensaje, "", {
                closeButton: true, // Mostrar botón de cierre
                progressBar: false, // Sin barra de progreso
                timeOut: 0, // No se cierra automáticamente
            });

            // Agregar la notificación al registro
            $("#notificationLog").append("<li>" + mensaje + "</li>");
        }

        function actualizarContenido() {
            // Obtener el contenido de global.txt
            var xhttp_global = new XMLHttpRequest();
            xhttp_global.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var nuevoContenido = this.responseText.trim();
                    
                    // Obtener la última línea del nuevo contenido
                    var lineas = nuevoContenido.split("\n");
                    var nuevaUltimaLinea = lineas[lineas.length - 1];

                    // Verificar si la última línea ha cambiado
                    if (nuevaUltimaLinea !== ultimaLinea && nuevaUltimaLinea.trim() !== "") {
                        ultimaLinea = nuevaUltimaLinea;
                        mostrarNotificacion("Nueva línea en global.txt: " + ultimaLinea.trim());
                    }
                }
            };
            xhttp_global.open("GET", "global.txt", true);
            xhttp_global.send();
        }

        function confirmarIntervalo() {
            intervaloVerificacion = parseInt($("#intervalo").val());
            toastr.success("Intervalo de verificación actualizado a " + intervaloVerificacion + " segundos.");
        }

        window.onload = function() {
            // Verificar cada 'intervaloVerificacion' segundos
            setInterval(actualizarContenido, intervaloVerificacion * 1000);
        }

        // Actualizar el intervalo de verificación cuando se confirme el botón
        $("#btnConfirmarIntervalo").click(confirmarIntervalo);

        // Actualizar el intervalo de verificación cuando cambie el valor en el campo de entrada
        $("#intervalo").on("input", function() {
            intervaloVerificacion = parseInt($(this).val());
        });
    </script>
</body>
</html>
