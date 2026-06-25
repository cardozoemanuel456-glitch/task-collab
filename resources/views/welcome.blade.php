<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskCollab - Gestión de Proyectos Académicos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f1115] text-zinc-100 selection:bg-indigo-500/30 selection:text-indigo-200 overflow-x-hidden">

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none opacity-20 hidden md:block">
        <div class="absolute top-[-10%] left-[20%] w-[500px] h-[500px] bg-indigo-600 rounded-full blur-[140px]"></div>
        <div class="absolute top-[10%] right-[20%] w-[400px] h-[400px] bg-violet-500 rounded-full blur-[140px]"></div>
    </div>

    <header class="border-b border-zinc-800/80 backdrop-blur-md sticky top-0 z-50 bg-[#0f1115]/80">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="bg-indigo-600 p-2 rounded-xl shadow-md shadow-indigo-600/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-white">TaskCollab</span>
            </div>

            <nav class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-lg shadow-indigo-600/10">
                            Ir al Panel de Control
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-zinc-400 hover:text-white transition px-2 py-2">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-zinc-800 hover:bg-zinc-700 text-white border border-zinc-700/60 text-sm font-semibold px-4 py-2 rounded-xl transition">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 pt-20 pb-16 text-center md:pt-32">
        <div class="max-w-3xl mx-auto space-y-6">
            <span class="inline-flex items-center space-x-1.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full px-3.5 py-1 text-xs font-medium">
                <span>🚀 Diseñado para estudiantes y desarrolladores</span>
            </span>

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white leading-tight md:leading-none">
                La forma más limpia de organizar tus <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">proyectos grupales</span>
            </h1>

            <p class="text-zinc-400 text-base md:text-lg max-w-xl mx-auto font-medium leading-relaxed">
                Estructurá tus tareas de programación, dividí el trabajo con tus compañeros de equipo y mantené el control del avance de tu software en tiempo real.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3 rounded-xl transition shadow-xl shadow-indigo-600/20 text-sm">
                        Volver a mis tableros
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3 rounded-xl transition shadow-xl shadow-indigo-600/20 text-sm">
                        Comenzar Gratis
                    </a>
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 font-semibold px-6 py-3 rounded-xl transition text-sm">
                        Acceder a mi cuenta
                    </a>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-24 md:mt-32 text-left">
            
            <div class="bg-[#121418] border border-zinc-800/60 p-6 rounded-2xl shadow-xl">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Tableros Interactivos</h3>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Gestioná el flujo de trabajo mediante columnas de estados dedicadas para evitar solapamientos con tus compañeros de código.
                </p>
            </div>

            <div class="bg-[#121418] border border-zinc-800/60 p-6 rounded-2xl shadow-xl">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Espacios Compartidos</h3>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Invitá a los integrantes de tu grupo de estudio para que colaboren asignando responsables específicos a cada requerimiento técnico.
                </p>
            </div>

            <div class="bg-[#121418] border border-zinc-800/60 p-6 rounded-2xl shadow-xl">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Diseño Adaptativo Completo</h3>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Revisá el estado del proyecto desde la computadora de la universidad o directo en tu celular con un rendimiento pulido y modo oscuro integrado.
                </p>
            </div>

        </div>
    </main>

    <footer class="border-t border-zinc-900 mt-20 bg-[#0b0c0f]">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500 gap-4">
            <p>&copy; {{ date('Y') }} TaskCollab. Desarrollado para la Tecnicatura Universitaria en Programación.</p>
            <div class="flex space-x-4">
                <span class="hover:text-zinc-400 transition cursor-default">Laravel 11</span>
                <span class="hover:text-zinc-400 transition cursor-default">Tailwind CSS</span>
            </div>
        </div>
    </footer>

</body>
</html>