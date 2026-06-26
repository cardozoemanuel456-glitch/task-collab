<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba TaskCollab</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="shortcut icon" href="/logo.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-indigo-600 mb-2">🧪 Panel de Prueba</h1>
        <p class="text-sm text-gray-500 mb-6">Usuario activo: <strong>{{ auth()->user()->name }}</strong></p>

        @livewire('workspace-manager')
    </div>

    @livewireScripts
</body>
</html>