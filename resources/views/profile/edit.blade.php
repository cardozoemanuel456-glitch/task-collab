@extends('paginas.app')

@section('title', 'Configuración - TaskCollab')

@section('breadcrumb', 'Configuración')

@section('content')

@php
    $isDark = auth()->user()->dark_mode;

    $card       = $isDark
                    ? 'bg-[#121214] border border-zinc-800/60 rounded-2xl p-6'
                    : 'bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm';
    $label      = 'block text-xs font-semibold uppercase tracking-wider mb-1.5 ' . ($isDark ? 'text-zinc-500' : 'text-slate-400');
    $input      = 'w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all '
                    . ($isDark ? 'bg-[#18181b] border-zinc-800 text-zinc-100' : 'bg-slate-50 border-slate-200 text-slate-900');
    $divider    = 'border-t mb-5 ' . ($isDark ? 'border-zinc-800/60' : 'border-slate-200/80');
    $h2         = 'font-bold text-base mb-0.5 ' . ($isDark ? 'text-zinc-100' : 'text-slate-800');
    $desc       = 'text-xs ' . ($isDark ? 'text-zinc-500' : 'text-slate-400');
    $navBase    = 'flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium transition-all cursor-pointer ';
    $navItem    = $navBase . ($isDark ? 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/60' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100');
    $navActive  = $navBase . ($isDark ? 'text-indigo-400 bg-indigo-600/15' : 'text-indigo-700 bg-indigo-50');
    $btnPrimary = 'bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors shadow-sm cursor-pointer';
    $btnDanger  = 'bg-red-600/10 hover:bg-red-600/20 text-red-500 text-xs font-bold px-4 py-2 rounded-xl transition-colors border border-red-500/30 cursor-pointer';
    $sectionLabel = 'text-xs font-bold uppercase tracking-widest px-3 mb-2 ' . ($isDark ? 'text-zinc-600' : 'text-slate-400');
@endphp

<div class="flex gap-8 items-start">

    {{-- ── Sidebar de settings ─────────────────────────────── --}}
    <aside class="w-44 shrink-0 sticky top-20">
        <p class="{{ $sectionLabel }}">Cuenta</p>
        <nav class="space-y-0.5" id="settings-nav">

            <button onclick="scrollToSection('perfil')" data-section="perfil"
                class="{{ $navActive }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Perfil
            </button>

            <button onclick="scrollToSection('password')" data-section="password"
                class="{{ $navItem }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Contraseña
            </button>

            <button onclick="scrollToSection('cuenta')" data-section="cuenta"
                class="{{ $navItem }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Cuenta
            </button>

        </nav>
    </aside>

    {{-- ── Contenido principal ─────────────────────────────── --}}
    <div class="flex-1 space-y-5 min-w-0">

        {{-- Información del Perfil --}}
        <section id="perfil" class="{{ $card }}">
            <div class="mb-5">
                <h2 class="{{ $h2 }}">Información del Perfil</h2>
                <p class="{{ $desc }}">Actualizá tu nombre y dirección de email.</p>
            </div>
            <hr class="{{ $divider }}">

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="{{ $label }}">Nombre</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}" required
                           class="{{ $input }}">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $user->email) }}" required
                           class="{{ $input }}">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" class="{{ $btnPrimary }}">Guardar cambios</button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-xs text-emerald-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardado correctamente
                        </span>
                    @endif
                </div>
            </form>
        </section>

        {{-- Contraseña --}}
        <section id="password" class="{{ $card }}">
            <div class="mb-5">
                <h2 class="{{ $h2 }}">Contraseña</h2>
                <p class="{{ $desc }}">Usá una contraseña larga y segura para proteger tu cuenta.</p>
            </div>
            <hr class="{{ $divider }}">

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="{{ $label }}">Contraseña actual</label>
                    <input type="password" id="current_password" name="current_password"
                           autocomplete="current-password" class="{{ $input }}">
                    @error('current_password', 'updatePassword')
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="{{ $label }}">Nueva contraseña</label>
                    <input type="password" id="password" name="password"
                           autocomplete="new-password" class="{{ $input }}">
                    @error('password', 'updatePassword')
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="{{ $label }}">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password" class="{{ $input }}">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" class="{{ $btnPrimary }}">Actualizar contraseña</button>
                    @if (session('status') === 'password-updated')
                        <span class="text-xs text-emerald-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Contraseña actualizada
                        </span>
                    @endif
                </div>
            </form>
        </section>

        {{-- Eliminar cuenta --}}
        <section id="cuenta" class="{{ $card }}">
            <div class="mb-5">
                <h2 class="font-bold text-base mb-0.5 {{ $isDark ? 'text-red-400' : 'text-red-600' }}">
                    Eliminar cuenta
                </h2>
                <p class="{{ $desc }}">Esta acción es permanente e irreversible. Se eliminarán todos tus tableros y tareas.</p>
            </div>
            <hr class="border-t mb-5 {{ $isDark ? 'border-red-900/40' : 'border-red-100' }}">

            <form method="post" action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('¿Estás seguro? Esta acción no puede deshacerse.')">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <label for="delete_password" class="{{ $label }}">
                        Confirmá tu contraseña para continuar
                    </label>
                    <input type="password" id="delete_password" name="password"
                           placeholder="••••••••"
                           class="{{ $input }} max-w-xs">
                    @error('password', 'userDeletion')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="{{ $btnDanger }}">
                    Eliminar mi cuenta
                </button>
            </form>
        </section>

    </div>
</div>

@push('scripts')
<script>
    // Scroll suave a sección y activar link en sidebar
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setActiveNav(id);
    }

    function setActiveNav(id) {
        const activeClass  = '{{ $navActive }}';
        const normalClass  = '{{ $navItem }}';
        document.querySelectorAll('#settings-nav button').forEach(btn => {
            btn.className = btn.dataset.section === id ? activeClass : normalClass;
        });
    }

    // Scroll-spy: resalta el link según qué sección está visible
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('section[id]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) setActiveNav(entry.target.id);
            });
        }, { rootMargin: '-20% 0px -70% 0px' });

        sections.forEach(s => observer.observe(s));
    });
</script>
@endpush

@endsection