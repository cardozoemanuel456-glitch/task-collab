@php
    $isDark = auth()->user()->dark_mode;

    $bgBody        = $isDark ? 'bg-[#121212] text-neutral-200'                    : 'bg-neutral-50 text-neutral-800';
    $bgSidebar     = $isDark ? 'bg-[#1e1e1e] border-neutral-800'                  : 'bg-white border-neutral-200';
    $bgHeader      = $isDark ? 'bg-[#121212] border-neutral-900'                  : 'bg-neutral-50 border-neutral-200';
    $bgInput       = $isDark ? 'bg-[#121212] border-neutral-800 text-neutral-200' : 'bg-neutral-100 border-neutral-300 text-neutral-800';
    $textTitle     = $isDark ? 'text-neutral-100'                                 : 'text-neutral-900';
    $hoverSidebar  = $isDark ? 'hover:bg-neutral-800'                             : 'hover:bg-neutral-100';
    $activeSidebar = $isDark ? 'bg-neutral-800 text-white font-medium'            : 'bg-neutral-200 text-neutral-900 font-semibold';
    $borderMuted   = $isDark ? 'border-neutral-800'                               : 'border-neutral-200';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskCollab')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="{{ $bgBody }} font-sans min-h-screen flex transition-colors duration-200">

    <aside id="sidebar" class="{{ $bgSidebar }} w-64 border-r flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div class="p-4 border-b {{ $borderMuted }} flex items-center justify-between">
            <span class="font-bold text-teal-600 tracking-wider text-lg">TaskCollab</span>
            <button onclick="toggleSidebar()" class="md:hidden text-xl cursor-pointer hover:text-red-500">✕</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            <div>
                <h3 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-2">Mis Tableros</h3>
                <ul class="space-y-1">
                    @forelse($paginasPrivadas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}"
                               class="flex items-center space-x-2 p-2 rounded-xl {{ $hoverSidebar }} text-sm {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <span>{{ $p->icono ?? '📊' }}</span>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-neutral-400 px-2 py-1 italic">No hay tableros creados</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-2">Espacios Colaborativos</h3>
                <ul class="space-y-1">
                    @forelse($paginasColaborativas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}"
                               class="flex items-center space-x-2 p-2 rounded-xl {{ $hoverSidebar }} text-sm {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <span>{{ $p->icono ?? '👥' }}</span>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-neutral-400 px-2 py-1 italic">Sin tableros compartidos</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="p-4 border-t {{ $isDark ? 'border-neutral-800 bg-[#1a1a1a]' : 'border-neutral-200 bg-neutral-100' }}">
            <form action="{{ route('paginas.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="titulo" required placeholder="+ Nuevo tablero..."
                       class="w-full {{ $bgInput }} border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-teal-600">
                <button type="submit"
                        class="bg-teal-600 hover:bg-teal-500 px-2.5 rounded-xl text-xs font-bold text-white cursor-pointer">+</button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/50 z-30 hidden md:hidden"></div>

    <div id="main-content" class="flex-1 flex flex-col md:pl-64 min-w-0 transition-all duration-200">

        <header class="h-14 {{ $bgHeader }} border-b flex items-center justify-between px-4 sticky top-0 z-20">
            <button onclick="toggleSidebar()"
                    class="p-2 rounded-xl border text-lg {{ $isDark ? 'bg-[#1e1e1e] border-neutral-800' : 'bg-white border-neutral-300' }} focus:outline-none cursor-pointer">
                ☰
            </button>

            <div class="text-sm font-medium truncate px-2 hidden md:block {{ $textTitle }}">
                @yield('breadcrumb', 'Inicio')
            </div>

            <div class="flex items-center space-x-3">
                @yield('header-actions')

                <form action="{{ route('dark-mode.toggle') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit"
                            class="p-2 rounded-xl text-sm border transition cursor-pointer {{ $isDark ? 'bg-neutral-800 border-neutral-700 hover:bg-neutral-700' : 'bg-white border-neutral-300 hover:bg-neutral-100' }}"
                            title="Cambiar modo de pantalla">
                        {{ $isDark ? '☀️ Claro' : ' Oscuro' }}
                    </button>
                </form>

                <div class="relative inline-block text-left">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center space-x-1 p-2 rounded-xl text-sm font-medium border transition cursor-pointer {{ $isDark ? 'border-neutral-800 bg-neutral-900 hover:bg-neutral-800 text-neutral-200' : 'border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-800' }}">
                        <span>👤 {{ auth()->user()->name }}</span>
                        <span class="text-xs">▼</span>
                    </button>

                    <div id="user-menu"
                         class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 border z-50 {{ $isDark ? 'bg-[#1e1e1e] border-neutral-800 text-neutral-300' : 'bg-white border-neutral-200 text-neutral-700' }}">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm {{ $isDark ? 'hover:bg-neutral-800 text-neutral-200' : 'hover:bg-neutral-100 text-neutral-700' }}">
                            ⚙️ Editar Perfil
                        </a>
                        <hr class="{{ $borderMuted }}">
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-500 font-semibold {{ $isDark ? 'hover:bg-neutral-800' : 'hover:bg-neutral-100' }} cursor-pointer">
                                🚪 Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-12 max-w-4xl w-full mx-auto">
            @yield('content')
        </main>

    </div>

    <script>
        // CAMBIO 3: Nueva lógica JavaScript que calcula la posición real de la barra lateral para ocultarla o mostrarla
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');
            
            const isHidden = sidebar.getBoundingClientRect().left < 0;
            
            if (isHidden) {
                // Si está oculta, la muestra
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                sidebar.classList.add('md:translate-x-0');
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.add('md:pl-64'); // Empuja el contenido en PC
                } else {
                    overlay.classList.remove('hidden'); // Muestra fondo oscuro en móvil
                }
            } else {
                // Si está visible, la oculta por completo hacia la izquierda
                sidebar.classList.remove('md:translate-x-0');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.remove('md:pl-64'); // Expande el contenido en PC al 100%
                } else {
                    overlay.classList.add('hidden'); // Oculta fondo oscuro en móvil
                }
            }
        }

        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        window.addEventListener('click', function (e) {
            const menu = document.getElementById('user-menu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('.relative')) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')

</body>
</html>