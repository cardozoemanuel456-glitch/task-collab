@php
// 1. CONTROLADORES DE MODOS CLARO / OSCURO PREMIUM (Estilo Login Corporativo)
$isDark = auth()->user()->dark_mode;

$bgBody = $isDark ? 'bg-[#09090b] text-zinc-200' : 'bg-slate-50 text-slate-800';
$bgSidebar = $isDark ? 'bg-[#121214] border-zinc-800/60' : 'bg-white border-slate-200/80';
$bgHeader = $isDark ? 'bg-[#121214]/80 backdrop-blur-md border-zinc-900' : 'bg-white/80 backdrop-blur-md border-slate-200/80';
$bgInput = $isDark ? 'bg-[#18181b] border-zinc-800 text-zinc-100' : 'bg-slate-50 border-slate-200 text-slate-900';
$textTitle = $isDark ? 'text-zinc-50 font-semibold' : 'text-slate-900 font-semibold';
$hoverSidebar = $isDark ? 'hover:bg-zinc-800/60 text-zinc-200' : 'hover:bg-slate-100 text-slate-900';
$activeSidebar = $isDark ? 'bg-indigo-600/15 text-indigo-400 font-medium border-l-2 border-indigo-500' : 'bg-indigo-50 text-indigo-700 font-semibold border-l-2 border-indigo-600';
$borderMuted = $isDark ? 'border-zinc-800/60' : 'border-slate-200/80';
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskCollab')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="{{ $bgBody }} font-sans min-h-screen flex transition-colors duration-200">

    <aside id="sidebar" class="{{ $bgSidebar }} w-64 border-r flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div class="p-4 border-b {{ $borderMuted }} flex items-center justify-between h-14">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 hover:opacity-80 transition-opacity">
                <div class="bg-indigo-600 p-1.5 rounded-lg text-white shadow-sm shadow-indigo-600/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="font-bold text-indigo-600 dark:text-indigo-400 tracking-tight text-xl">TaskCollab</span>
            </a>
            <button onclick="toggleSidebar()" class="md:hidden p-1 rounded-lg text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-zinc-800 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        {{-- NUEVO TABLERO - movido arriba --}}
        <div class="p-3 border-b {{ $isDark ? 'border-zinc-800 bg-[#0d0d0f]' : 'border-slate-200/80 bg-slate-50' }}">
            <form action="{{ route('paginas.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="titulo" required placeholder="Nuevo espacio..."
                    class="w-full {{ $bgInput }} border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 px-2.5 rounded-xl text-sm font-bold text-white cursor-pointer transition-colors shadow-sm">+</button>
            </form>
        </div>
        <div class="flex-1 overflow-y-auto p-3 space-y-6">
            <div>
                <ul class="space-y-0.5 mb-6">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center space-x-2.5 px-3 py-2 rounded-xl {{ $hoverSidebar }} text-sm transition-all {{ request()->routeIs('dashboard') ? $activeSidebar : '' }}">
                            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Inicio</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="px-3 text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Mis Espacios</h3>
                <ul class="space-y-0.5">
                    @forelse($paginasPrivadas as $p)
                    <li class="group relative">
                        <a href="{{ route('paginas.show', $p->id) }}"
                            class="flex items-center space-x-2.5 px-3 py-2 rounded-xl {{ $hoverSidebar }} text-sm transition-all {{ isset($pagina) && $pagina->id == $p->id ? $activeSidebar : '' }}">
                            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span class="truncate pr-6">{{ $p->titulo }}</span>
                        </a>
                        
                        <!-- Botón de tres puntos -->
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="toggleSpaceMenu(event, 'menu-{{ $p->id }}')" 
                                class="p-1 rounded-lg text-slate-400 cursor-pointer {{ $isDark ? 'hover:bg-zinc-800/60 hover:text-zinc-200' : 'hover:bg-slate-100 hover:text-slate-700' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Dropdown del espacio -->
                        <div id="menu-{{ $p->id }}" class="hidden absolute right-2 top-[90%] w-36 rounded-xl shadow-xl py-1 border z-50 space-menu-dropdown {{ $isDark ? 'bg-[#121214] border-zinc-800 text-zinc-300' : 'bg-white border-slate-200 text-slate-700' }}">
                            <button onclick="openEditSpaceModal(event, {{ $p->id }}, '{{ addslashes($p->titulo) }}')" 
                                class="w-full flex items-center text-left px-3 py-1.5 text-xs cursor-pointer {{ $isDark ? 'hover:bg-zinc-800 hover:text-zinc-100 text-zinc-300' : 'hover:bg-slate-50 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-3.5 h-3.5 mr-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Editar nombre
                            </button>
                            <hr class="{{ $borderMuted }} my-1">
                            <button onclick="openDeleteSpaceModal(event, {{ $p->id }}, '{{ addslashes($p->titulo) }}', '{{ route('paginas.destroy', $p->id) }}')" 
                                class="w-full flex items-center text-left px-3 py-1.5 text-xs text-red-500 font-medium cursor-pointer {{ $isDark ? 'hover:bg-zinc-800' : 'hover:bg-slate-50' }}">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>
                        </div>
                    </li>
                    @empty
                    <li class="text-xs text-slate-400 dark:text-zinc-500 px-3 py-1.5 italic">No hay espacios creados</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="px-3 text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Espacios Colaborativos</h3>
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
                    <li class="text-xs text-slate-400 dark:text-zinc-500 px-3 py-1.5 italic">Sin espacios compartidos</li>
                    @endforelse
                </ul>
            </div>
        </div>
        {{-- Handle de resize --}}
        <div id="sidebar-resize"
            class="absolute top-0 right-0 bottom-0 w-1.5 cursor-col-resize z-50 group">
            <div class="absolute inset-y-0 right-0 w-full group-hover:bg-indigo-500/40 group-active:bg-indigo-500/60 transition-colors rounded-full"></div>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden backend-blur-xs"></div>

    <div id="main-content" class="flex-1 flex flex-col md:pl-64 min-w-0 transition-all duration-200">
        <header class="h-14 {{ $bgHeader }} border-b flex items-center justify-between px-4 sticky top-0 z-20 w-full">

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

                {{-- Campana de notificaciones --}}
                <livewire:notification-bell />

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

        <main class="flex-1 p-6 md:p-12 w-full max-w-full block clear-both">
            <div class="w-full">
                @yield('content')
            </div>
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
                    mainContent.style.paddingLeft = sidebar.style.width || '';
                } else {
                    overlay.classList.remove('hidden');
                }
            } else {
                sidebar.classList.remove('md:translate-x-0');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');

                if (window.innerWidth >= 768) {
                    mainContent.classList.remove('md:pl-64');
                    mainContent.style.paddingLeft = '0px';
                } else {
                    overlay.classList.add('hidden');
                }
            }
        }

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const isHidden = sidebar.getBoundingClientRect().left < 0;

            if (window.innerWidth < 768) {
                mainContent.style.paddingLeft = '0px';
            } else {
                if (isHidden) {
                    mainContent.style.paddingLeft = '0px';
                    mainContent.classList.remove('md:pl-64');
                } else {
                    mainContent.style.paddingLeft = sidebar.style.width || '';
                    if (!sidebar.style.width) {
                        mainContent.classList.add('md:pl-64');
                    }
                }
            }
        });
        // ── Resize sidebar ──────────────────────────────────────
        (function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const handle = document.getElementById('sidebar-resize');

            let dragging = false,
                startX = 0,
                startW = 0;
            const MIN = 180,
                MAX = 440;

            handle.addEventListener('mousedown', (e) => {
                dragging = true;
                startX = e.clientX;
                startW = sidebar.offsetWidth;
                document.body.style.cursor = 'col-resize';
                document.body.style.userSelect = 'none';
                // Desactivar transición mientras se arrastra
                sidebar.style.transition = 'none';
                mainContent.style.transition = 'none';
                e.preventDefault();
            });

            document.addEventListener('mousemove', (e) => {
                if (!dragging) return;
                const w = Math.min(Math.max(startW + (e.clientX - startX), MIN), MAX);
                sidebar.style.width = w + 'px';
                if (window.innerWidth >= 768) {
                    mainContent.style.paddingLeft = w + 'px';
                }
            });

            document.addEventListener('mouseup', () => {
                if (!dragging) return;
                dragging = false;
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                sidebar.style.transition = '';
                mainContent.style.transition = '';
            });
        })();

        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        function toggleSpaceMenu(event, menuId) {
            event.preventDefault();
            event.stopPropagation();
            
            // Cerrar todos los demás menús de espacio abiertos
            document.querySelectorAll('.space-menu-dropdown').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.add('hidden');
                }
            });
            
            const menu = document.getElementById(menuId);
            menu.classList.toggle('hidden');
        }

        function openEditSpaceModal(event, spaceId, currentTitle) {
            event.preventDefault();
            event.stopPropagation();
            
            // Cerrar todos los menús
            document.querySelectorAll('.space-menu-dropdown').forEach(menu => menu.classList.add('hidden'));
            
            const modal = document.getElementById('edit-space-modal');
            const form = document.getElementById('edit-space-form');
            const input = document.getElementById('edit-space-titulo');
            
            form.action = `/paginas/${spaceId}`;
            input.value = currentTitle;
            
            modal.classList.remove('hidden');
            input.focus();
        }

        function closeEditSpaceModal() {
            document.getElementById('edit-space-modal').classList.add('hidden');
        }

        function openDeleteSpaceModal(event, spaceId, currentTitle, deleteRoute) {
            event.preventDefault();
            event.stopPropagation();
            
            // Cerrar todos los menús
            document.querySelectorAll('.space-menu-dropdown').forEach(menu => menu.classList.add('hidden'));
            
            const modal = document.getElementById('delete-space-modal');
            const form = document.getElementById('delete-space-form');
            const nameSpan = document.getElementById('delete-space-name');
            
            form.action = deleteRoute;
            nameSpan.textContent = currentTitle;
            
            modal.classList.remove('hidden');
        }

        function closeDeleteSpaceModal() {
            document.getElementById('delete-space-modal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const menu = document.getElementById('user-menu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('.relative')) {
                menu.classList.add('hidden');
            }
            
            // Cerrar dropdowns de los espacios si se hace click fuera
            if (!e.target.closest('.space-menu-dropdown') && !e.target.closest('button')) {
                document.querySelectorAll('.space-menu-dropdown').forEach(m => {
                    m.classList.add('hidden');
                });
            }
        });
    </script>

    <!-- Modal para Editar Nombre de Espacio -->
    <div id="edit-space-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-xs">
        <div class="w-full max-w-md rounded-2xl p-6 border shadow-2xl transition-all {{ $isDark ? 'bg-[#121214] border-zinc-800 text-zinc-200' : 'bg-white border-slate-200 text-slate-800' }}">
            <h3 class="text-base font-bold mb-4">Editar espacio</h3>
            <form id="edit-space-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="edit-space-titulo" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 {{ $isDark ? 'text-zinc-500' : 'text-slate-400' }}">Nombre del espacio</label>
                    <input type="text" id="edit-space-titulo" name="titulo" required 
                        class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all {{ $isDark ? 'bg-[#18181b] border-zinc-800 text-zinc-100' : 'bg-slate-50 border-slate-200 text-slate-900' }}">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditSpaceModal()" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $isDark ? 'bg-[#18181b] border border-zinc-800 text-zinc-300 hover:bg-zinc-800' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors shadow-sm cursor-pointer">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Confirmar Eliminación de Espacio -->
    <div id="delete-space-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-xs">
        <div class="w-full max-w-md rounded-2xl p-6 border shadow-2xl transition-all {{ $isDark ? 'bg-[#121214] border-zinc-800 text-zinc-200' : 'bg-white border-slate-200 text-slate-800' }}">
            <h3 class="text-base font-bold text-red-500 mb-2">¿Eliminar espacio?</h3>
            <p class="text-xs mb-4 {{ $isDark ? 'text-zinc-400' : 'text-slate-500' }}">Esta acción no se puede deshacer. Se eliminarán permanentemente todas las tareas y subpáginas de <span id="delete-space-name" class="font-semibold"></span>.</p>
            <form id="delete-space-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteSpaceModal()" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $isDark ? 'bg-[#18181b] border border-zinc-800 text-zinc-300 hover:bg-zinc-800' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="bg-red-600 hover:bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors shadow-sm cursor-pointer">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @stack('scripts')

    {{-- Native Web Notifications --}}
    <script>
        // Solicitar permiso de notificaciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            if ('Notification' in window && Notification.permission === 'default') {
                // Pedimos permiso de forma sutil después de un breve retraso
                setTimeout(() => {
                    Notification.requestPermission();
                }, 3000);
            }
        });

        // Escuchar evento de Livewire para lanzar notificación nativa
        document.addEventListener('livewire:init', () => {
            Livewire.on('new-notification', (params) => {
                const data = Array.isArray(params) ? params[0] : params;
                
                // Sonido sutil de notificación (opcional: usa un beep del sistema)
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = 800;
                    gain.gain.value = 0.08;
                    osc.start();
                    osc.stop(ctx.currentTime + 0.15);
                } catch(e) {}

                // Notificación nativa del navegador
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notif = new Notification(data.title || 'TaskCollab', {
                        body: data.body || 'Tienes una nueva notificación',
                        icon: '/favicon.ico',
                        tag: 'taskcollab-' + Date.now(),
                        silent: true
                    });

                    notif.onclick = function() {
                        window.focus();
                        if (data.url) {
                            window.location.href = data.url;
                        }
                        notif.close();
                    };

                    setTimeout(() => notif.close(), 6000);
                }
            });
        });
    </script>

</body>

</html>