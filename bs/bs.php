<?php
// Verificamos si la contraseña se ha enviado y es correcta
if (!isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== 'sex123') {
    // Si no es correcta, solicitamos la autenticación
    header('WWW-Authenticate: Basic realm="Acceso restringido"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso no autorizado';
    exit;
}

// Si la autenticación es exitosa, mostramos el contenido de la página
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar IPs Bloqueadas</title>
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="container max-w-lg mx-auto mt-8 p-6">
    <h1 class="text-3xl mb-5">Administrar IPs Bloqueadas</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="mb-4">
        <label for="ip" class="block text-gray-700">Agregar IP:</label>
        <input type="text" class="form-input mt-1 block w-full" id="ip" name="ip">
      </div>
      <button type="submit" class="btn btn-primary mb-3" name="add">Agregar IP</button>
    </form>

    <?php
    $filename = 'banserv.txt';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
      $ip = $_POST['ip'];
      if (!empty($ip)) {
        file_put_contents($filename, $ip . "\n", FILE_APPEND);
        echo '<div class="alert alert-success" role="alert">IP agregada con éxito.</div>';
      }
    }

    if (file_exists($filename)) {
      echo '<h2 class="mt-5 text-2xl">IPs Bloqueadas</h2>';
      $banned_ips = file($filename, FILE_IGNORE_NEW_LINES);
      if (!empty($banned_ips)) {
        echo '<div class="scrollable">';
        echo '<table class="table-auto w-full">';
        echo '<thead><tr><th class="px-4 py-2">IP</th><th class="px-4 py-2">Acción</th></tr></thead>';
        echo '<tbody>';
        foreach ($banned_ips as $banned_ip) {
          echo '<tr><td class="border px-4 py-2">' . $banned_ip . '</td><td class="border px-4 py-2"><a href="?remove=' . urlencode($banned_ip) . '" class="btn btn-danger btn-sm">Quitar</a></td></tr>';
        }
        echo '</tbody></table></div>';
      } else {
        echo '<div class="alert alert-info mt-3" role="alert">No hay IPs bloqueadas actualmente.</div>';
      }
    } else {
      echo '<div class="alert alert-info mt-3" role="alert">No hay IPs bloqueadas actualmente.</div>';
    }

    if (isset($_GET['remove'])) {
      $ip_to_remove = $_GET['remove'];
      $banned_ips = file($filename, FILE_IGNORE_NEW_LINES);
      $banned_ips = array_diff($banned_ips, array($ip_to_remove));
      file_put_contents($filename, implode("\n", $banned_ips) . "\n");
      echo '<div class="alert alert-warning mt-3" role="alert">IP eliminada con éxito.</div>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_file'])) {
      if (file_exists($filename)) {
        unlink($filename);
        echo '<div class="alert alert-danger mt-3" role="alert">Archivo ' . $filename . ' borrado con éxito.</div>';
        // Redirigir para evitar reenvío de formulario
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
      } else {
        echo '<div class="alert alert-warning mt-3" role="alert">El archivo ' . $filename . ' no existe.</div>';
      }
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <button type="submit" class="btn btn-danger mt-3" name="delete_file" onclick="return confirm('¿Estás seguro de que quieres borrar el archivo banserv.txt? Esta acción no se puede deshacer.');">Borrar Archivo banserv.txt</button>
    </form>
  </div>

  <!-- Tailwind CSS (replaced Bootstrap bundle with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
</body>
</html>