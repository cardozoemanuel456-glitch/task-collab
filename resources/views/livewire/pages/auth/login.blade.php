<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - TaskCollab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
        
        <!-- PANEL IZQUIERDO: Branding e Inspiración -->
        <div class="hidden md:flex flex-col justify-between bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 p-12 text-white">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-black tracking-wider text-indigo-400">TaskCollab</span>
            </div>
            
            <div class="space-y-4">
                <h1 class="text-4xl font-extrabold leading-tight">
                    La forma más eficiente de <br>
                    <span class="text-indigo-400">programar en equipo.</span>
                </h1>
                <p class="text-slate-400 max-w-md text-lg">
                    Organizá tus tareas, gestioná tus tableros Kanban y centralizá el flujo de trabajo de tu grupo de estudio en un solo lugar.
                </p>
            </div>

            <div class="text-xs text-slate-500">
                &copy; {{ date('Y') }} TaskCollab - Tecnicatura en Programación.
            </div>
        </div>

        <!-- PANEL DERECHO: Formulario Limpio -->
        <div class="flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">¡Hola de nuevo!</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        ¿No tenés cuenta? 
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Registrate acá</a>
                    </p>
                </div>

                <!-- Formulario Nativo de Laravel -->
                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="space-y-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Correo Electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Contraseña</label>
                            <input id="password" type="password" name="password" required
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="remember_me" class="ml-2 block text-sm text-slate-600">Recordar mi sesión</label>
                        </div>
                    </div>

                    <!-- Botón de Acción -->
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                            Ingresar al Panel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
</html>