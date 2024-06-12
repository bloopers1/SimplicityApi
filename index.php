<?php
$password = "lol123";

if (isset($_POST['password']) && $_POST['password'] === $password) {
    $showPage = true;
} else {
    $showPage = false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php if ($showPage): ?>
        <div class="container mx-auto py-8">
            <div class="bg-gray-800 text-white py-4 px-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl text-center font-bold">Simplicity v1.0</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Gestoken -->
                <div class="bg-yellow-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestoken</h2>
                        <p class="mb-4">Gestión de tokens.</p>
                        <a href="/q/a.php" class="bg-yellow-700 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Gestoken (Live) -->
                <div class="bg-yellow-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestoken (Live)</h2>
                        <p class="mb-4">Gestoken en vivo.</p>
                        <a href="/q/live.php" class="bg-yellow-700 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Gestoken (Token Creator) -->
                <div class="bg-yellow-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestoken (Token Creator)</h2>
                        <p class="mb-4">Creador de tokens.</p>
                        <a href="/q/tokencreator.php" class="bg-yellow-700 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Divisor -->
                <div class="col-span-3 border-t-2 border-gray-300"></div>

                <!-- Gestuser -->
                <div class="bg-green-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestuser</h2>
                        <p class="mb-4">Gestión de usuarios.</p>
                        <a href="/css/admain.php" class="bg-green-700 hover:bg-green-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Gestuser (Admovil) -->
                <div class="bg-green-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestuser (Admovil)</h2>
                        <p class="mb-4">Gestión de usuarios (versión móvil).</p>
                        <a href="/css/admovil.php" class="bg-green-700 hover:bg-green-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Divisor -->
                <div class="col-span-3 border-t-2 border-gray-300"></div>

                <!-- Banserv -->
                <div class="bg-blue-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Banserv</h2>
                        <p class="mb-4">Sistema de Banserv.</p>
                        <a href="/bs/bs.php" class="bg-blue-700 hover:bg-blue-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Banserv (Live) -->
                <div class="bg-blue-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Banserv (Live)</h2>
                        <p class="mb-4">Banserv en vivo.</p>
                        <a href="/bs/live.php" class="bg-blue-700 hover:bg-blue-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Divisor -->
                <div class="col-span-3 border-t-2 border-gray-300"></div>

                <!-- Gestend -->
                <div class="bg-red-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Gestend</h2>
                        <p class="mb-4">Gestión de solicitudes.</p>
                        <a href="/request" class="bg-red-700 hover:bg-red-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- ServerSalud -->
                <div class="bg-purple-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">ServerSalud</h2>
                        <p class="mb-4">Sistema de salud del servidor.</p>
                        <a href="/links.php" class="bg-purple-700 hover:bg-purple-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-indigo-500 text-white rounded-lg shadow-md">
                    <div class="py-4 px-6">
                        <h2 class="text-xl font-semibold mb-2">Help</h2>
                        <p class="mb-4">Centro de ayuda y soporte.</p>
                        <a href="/help.php" class="bg-indigo-700 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg block text-center">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container mx-auto py-8">
            <div class="bg-gray-800 text-white py-4 px-6 rounded-lg shadow-md mb-6">
                <h1 class="text-3xl text-center font-bold">Protegido con Contraseña</h1>
            </div>
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="py-4 px-6">
                    <h2 class="text-xl font-semibold mb-2">Ingresa la contraseña</h2>
                    <form action="" method="post">
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña:</label>
                            <input type="password" id="password" name="password" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
