@php
    // 1. CONTROLADORES DE MODOS CLARO / OSCURO PREMIUM (Estilo Login Corporativo)
    $isDark = auth()->user()->dark_mode;

    $bgBody        = $isDark ? 'bg-[#09090b] text-zinc-200'                       : 'bg-slate-50 text-slate-800';
    $bgSidebar     = $isDark ? 'bg-[#121214] border-zinc-800/60'                  : 'bg-white border-slate-200/80';
    $bgHeader      = $isDark ? 'bg-[#121214]/80 backdrop-blur-md border-zinc-900' : 'bg-white/80 backdrop-blur-md border-slate-200/80';
    $bgInput       = $isDark ? 'bg-[#18181b] border-zinc-800 text-zinc-100'       : 'bg-slate-50 border-slate-200 text-slate-900';
    $textTitle     = $isDark ? 'text-zinc-50 font-semibold'                       : 'text-slate-900 font-semibold';
    $hoverSidebar  = $isDark ? 'hover:bg-zinc-800/60 text-zinc-200'                : 'hover:bg-slate-100 text-slate-900';
    $activeSidebar = $isDark ? 'bg-indigo-600/15 text-indigo-400 font-medium border-l-2 border-indigo-500' : 'bg-indigo-50 text-indigo-700 font-semibold border-l-2 border-indigo-600';
    $borderMuted   = $isDark ? 'border-zinc-800/60'                               : 'border-slate-200/80';
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
        
        <div class="p-4 border-b {{ $borderMuted }} flex items-center justify-between h-14">
            <div class="flex items-center space-x-2.5">
                <div class="bg-indigo-600 p-1.5 rounded-lg text-white shadow-sm shadow-indigo-600/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="font-bold text-slate-900 indigo:text-zinc-50 tracking-tight text-base">TaskCollab</span>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden p-1 rounded-lg text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-zinc-800 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-6">
            <div>
                <h3 class="px-3 text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Mis Tableros</h3>
                <ul class="space-y-0.5">
                    @forelse($paginasPrivadas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}"
                               class="flex items-center space-x-2.5 px-3 py-2 rounded-xl {{ $hoverSidebar }} text-sm transition-all {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-slate-400 dark:text-zinc-500 px-3 py-1.5 italic">No hay tableros creados</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="px-3 text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Espacios Colaborativos</h3>
                <ul class="space-y-0.5">
                    @forelse($paginasColaborativas as $p)
                        <li>
                            <a href="{{ route('paginas.show', $p->id) }}"
                               class="flex items-center space-x-2.5 px-3 py-2 rounded-xl {{ $hoverSidebar }} text-sm transition-all {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="truncate">{{ $p->titulo }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-xs text-slate-400 dark:text-zinc-500 px-3 py-1.5 italic">Sin tableros compartidos</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="p-3 border-t {{ $isDark ? 'border-zinc-800 bg-[#0d0d0f]' : 'border-slate-200/80 bg-slate-50' }}">
            <form action="{{ route('paginas.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="titulo" required placeholder="Nuevo tablero..."
                       class="w-full {{ $bgInput }} border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 px-2.5 rounded-xl text-sm font-bold text-white cursor-pointer transition-colors shadow-sm">+</button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden backend-blur-xs"></div>

    <div id="main-content" class="flex-1 flex flex-col md:pl-64 min-w-0 transition-all duration-200">

        <header class="h-14 {{ $bgHeader }} border-b flex items-center justify-between px-4 sticky top-0 z-20">
            
            <button onclick="toggleSidebar()"
                    class="p-2 rounded-xl border text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200 focus:outline-none cursor-pointer transition-colors {{ $isDark ? 'bg-[#18181b] border-zinc-800' : 'bg-white border-slate-200' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="text-xs font-semibold uppercase tracking-wider px-2 hidden md:block {{ $textTitle }}">
                @yield('breadcrumb', 'Inicio')
            </div>

            <div class="flex items-center space-x-2.5">
                @yield('header-actions')

                <form action="{{ route('dark-mode.toggle') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit"
                            class="p-2 rounded-xl border transition-all cursor-pointer flex items-center justify-center text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200 {{ $isDark ? 'bg-[#18181b] border-zinc-800 hover:bg-zinc-800' : 'bg-white border-slate-200 hover:bg-slate-50' }}"
                            title="Cambiar tema">
                        @if($isDark)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        @endif
                    </button>
                </form>

                <div class="relative inline-block text-left">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-semibold border transition-all cursor-pointer {{ $isDark ? 'border-zinc-800 bg-[#18181b] hover:bg-zinc-800/80 text-zinc-200' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700' }}">
                        <svg class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ auth()->user()->name }}</span>
                        <svg class="w-3 h-3 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="user-menu"
                         class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-xl py-1 border z-50 transition-all {{ $isDark ? 'bg-[#121214] border-zinc-800 text-zinc-300' : 'bg-white border-slate-200 text-slate-700' }}">
                        
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center px-4 py-2 text-xs font-medium transition-colors {{ $isDark ? 'hover:bg-zinc-800 text-zinc-200' : 'hover:bg-slate-50 text-slate-700' }}">
                            <svg class="w-3.5 h-3.5 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configurar Perfil
                        </a>
                        
                        <hr class="{{ $borderMuted }} my-1">
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center text-left px-4 py-2 text-xs text-red-500 font-semibold transition-colors {{ $isDark ? 'hover:bg-zinc-800' : 'hover:bg-slate-50' }} cursor-pointer">
                                <svg class="w-3.5 h-3.5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar Sesión
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');
            
            const isHidden = sidebar.getBoundingClientRect().left < 0;
            
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                sidebar.classList.add('md:translate-x-0');
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.add('md:pl-64');
                } else {
                    overlay.classList.remove('hidden');
                }
            } else {
                sidebar.classList.remove('md:translate-x-0');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.remove('md:pl-64');
                } else {
                    overlay.classList.add('hidden');
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