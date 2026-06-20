@php
    // 1. CONTROLADORES DE MODO CLARO / OSCURO DINÁMICOS
    $isDark = auth()->user()->dark_mode;
    
    $bgBody      = $isDark ? 'bg-[#121212] text-neutral-200' : 'bg-neutral-50 text-neutral-800';
    $bgSidebar   = $isDark ? 'bg-[#1e1e1e] border-neutral-800' : 'bg-white border-neutral-200';
    $bgHeader    = $isDark ? 'bg-[#121212] border-neutral-900' : 'bg-neutral-50 border-neutral-200';
    $bgCard      = $isDark ? 'bg-[#1e1e1e] border-neutral-800 shadow-2xl' : 'bg-white border-neutral-200 shadow-md';
    $bgInput     = $isDark ? 'bg-[#121212] border-neutral-800 text-neutral-200' : 'bg-neutral-100 border-neutral-300 text-neutral-800';
    $textTitle   = $isDark ? 'text-neutral-100' : 'text-neutral-900';
    $textMuted   = $isDark ? 'text-neutral-500' : 'text-neutral-400';
    
    $hoverSidebar  = $isDark ? 'hover:bg-neutral-800' : 'hover:bg-neutral-100';
    $activeSidebar = $isDark ? 'bg-neutral-800 text-white font-medium' : 'bg-neutral-200 text-neutral-900 font-semibold';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskCollab - Entorno de Pruebas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="{{ $bgBody }} font-sans min-h-screen flex transition-colors duration-200">

    <aside id="sidebar" class="{{ $bgSidebar }} w-64 border-r flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div class="p-4 border-b {{ $isDark ? 'border-neutral-800' : 'border-neutral-200' }} flex items-center justify-between">
            <span class="font-bold text-teal-600 tracking-wider text-lg">🚀 TaskCollab</span>
            <button onclick="toggleSidebar()" class="md:hidden text-2xl">✕</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            <div>
                <h3 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-2">Mis Páginas</h3>
                <ul class="space-y-1">
                    @forelse($paginasPrivadas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}" class="flex items-center space-x-2 p-2 rounded-xl {{ $hoverSidebar }} text-sm {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <span>{{ $p->icono ?? '📄' }}</span>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-neutral-400 px-2 py-1">No hay páginas creadas</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-2">Colaborativo</h3>
                <ul class="space-y-1">
                    @forelse($paginasColaborativas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}" class="flex items-center space-x-2 p-2 rounded-xl {{ $hoverSidebar }} text-sm {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <span>{{ $p->icono ?? '👥' }}</span>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-neutral-400 px-2 py-1">No te compartieron páginas</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="p-4 border-t {{ $isDark ? 'border-neutral-800 bg-[#1a1a1a]' : 'border-neutral-200 bg-neutral-100' }}">
            <form action="{{ route('paginas.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="titulo" placeholder="+ Nueva página..." class="w-full {{ $bgInput }} border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-teal-600">
                <button type="submit" class="bg-teal-600 hover:bg-teal-500 px-2.5 rounded-xl text-xs font-bold text-white">+</button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden"></div>

    <div class="flex-1 flex flex-col md:pl-64 min-w-0">
        
        <header class="h-14 {{ $bgHeader }} border-b flex items-center justify-between px-4 sticky top-0 z-20">
            <button onclick="toggleSidebar()" class="p-2 rounded-xl border {{ $isDark ? 'bg-[#1e1e1e] border-neutral-800' : 'bg-white border-neutral-300' }} focus:outline-none md:hidden">
                🍔
            </button>
            
            <div class="text-sm font-medium truncate px-2 hidden md:block {{ $textTitle }}">
                {{ isset($pagina) ? 'Espacio / ' . $pagina->titulo : 'Inicio' }}
            </div>

            <div class="flex items-center space-x-3">
                @if(isset($pagina))
                    <button onclick="alert('Módulo de invitación en desarrollo')" class="bg-teal-600 hover:bg-teal-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition">
                        👤 Invitar
                    </button>
                @endif

                <form action="{{ route('dark-mode.toggle') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-sm border transition {{ $isDark ? 'bg-neutral-800 border-neutral-700 hover:bg-neutral-700' : 'bg-white border-neutral-300 hover:bg-neutral-100' }}" title="Cambiar modo de pantalla">
                        {{ $isDark ? '☀️ Claro' : '🌙 Oscuro' }}
                    </button>
                </form>

                <div class="relative inline-block text-left">
                    <button onclick="toggleUserMenu()" class="flex items-center space-x-1 p-2 rounded-xl text-sm font-medium border transition {{ $isDark ? 'border-neutral-800 bg-neutral-900 hover:bg-neutral-800 text-neutral-200' : 'border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-800' }}">
                        <span>👤 {{ auth()->user()->name }}</span>
                        <span class="text-xs">▼</span>
                    </button>
                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 border z-50 {{ $isDark ? 'bg-[#1e1e1e] border-neutral-800 text-neutral-300' : 'bg-white border-neutral-200 text-neutral-700' }}">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm {{ $isDark ? 'hover:bg-neutral-800 text-neutral-200' : 'hover:bg-neutral-100 text-neutral-700' }}">
                            ⚙️ Editar Perfil
                        </a>
                        <hr class="{{ $isDark ? 'border-neutral-800' : 'border-neutral-200' }}">
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-500 font-semibold {{ $isDark ? 'hover:bg-neutral-800' : 'hover:bg-neutral-100' }}">
                                🚪 Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-12 max-w-4xl w-full mx-auto">
            @if(isset($pagina))
                
                <div class="flex items-center space-x-3 mb-8">
                    <span class="text-4xl">{{ $pagina->icono ?? '📝' }}</span>
                    <h1 class="text-3xl md:text-4xl font-bold {{ $textTitle }}">{{ $pagina->titulo }}</h1>
                    
                    <form action="{{ route('paginas.destroy', $pagina->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar esta lista completa?')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs p-1.5 rounded-lg border {{ $isDark ? 'text-neutral-500 border-neutral-800 hover:text-red-400 hover:border-red-900' : 'text-neutral-400 border-neutral-300 hover:text-red-600 hover:border-red-200' }} transition">🗑️ Borrar Lista</button>
                    </form>
                </div>

                <div class="{{ $bgCard }} rounded-2xl p-4 md:p-6 border">
                    
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
                        <div class="relative w-full md:w-64">
                            <input type="text" placeholder="Buscar tareas (Filtro)..." disabled class="w-full pl-3 pr-4 py-1.5 {{ $bgInput }} border rounded-xl text-xs {{ $textMuted }} cursor-not-allowed">
                        </div>

                        <form action="{{ route('tareas.store') }}" method="POST" class="w-full md:w-auto flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="pagina_id" value="{{ $pagina->id }}">
                            <input type="text" name="title" required placeholder="Escribir nueva tarea..." class="w-full md:w-64 {{ $bgInput }} border rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:border-teal-600">
                            <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-1.5 rounded-xl text-sm transition shrink-0">
                                + Añadir
                            </button>
                        </form>
                    </div>

                    <div class="space-y-2">
                        @forelse($pagina->tareas as $tarea)
                            <div class="flex items-center justify-between p-3 {{ $bgInput }} border rounded-xl hover:border-teal-600/40 transition">
                                <div class="flex items-center space-x-3 min-w-0 flex-1">
                                    <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox" onchange="this.form.submit()" {{ $tarea->status === 'done' ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-0 cursor-pointer">
                                    </form>
                                    
                                    <span class="text-sm truncate {{ $tarea->status === 'done' ? 'line-through opacity-40' : '' }}">
                                        {{ $tarea->title }}
                                    </span>
                                </div>

                                <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-neutral-400 hover:text-red-500 p-1 text-xs">✕</button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-8 {{ $textMuted }} text-sm">
                                🎉 ¡No hay tareas pendientes en esta lista!
                            </div>
                        @endforelse
                    </div>
                </div>

            @else
                <div class="text-center py-20">
                    <span class="text-6xl">👋</span>
                    <h2 class="text-2xl font-bold mt-4 {{ $textTitle }}">¡Bienvenido a TaskCollab!</h2>
                    <p class="{{ $textMuted }} text-sm mt-2 max-w-sm mx-auto">Seleccioná una lista en la barra lateral o creá una nueva abajo para empezar a organizar tus tareas académicas y proyectos.</p>
                </div>
            @endif
        </main>
    </div>

    <script>
        // Manejo del menú lateral en móviles
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Manejo del dropdown de Perfil / Cerrar Sesión
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Cierra el menú de usuario si se hace clic afuera del mismo
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('user-menu');
            if (!menu.classList.contains('hidden') && !e.target.closest('.relative')) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>