@extends('paginas.app')

@section('title', 'TaskCollab - Panel de Control')

@section('breadcrumb', 'Inicio')

@section('content')

@php $isDark = auth()->user()->dark_mode; @endphp

<div class="flex flex-col items-center justify-center py-16 px-4">

    {{-- Logo decorativo --}}
    <div class="flex justify-center mb-6 pointer-events-none select-none">
        <img src="{{ asset('logo-tc.png') }}" alt=""
            class="logo-tc-dashboard {{ $isDark ? 'logo-tc-dashboard-dark' : 'logo-tc-dashboard-light' }}">
    </div>

    <h1 class="text-4xl font-bold mb-2 text-center {{ $isDark ? 'text-zinc-50' : 'text-slate-900' }}">
        ¡Bienvenido a TaskCollab!
    </h1>
    <p class="text-sm text-center max-w-sm mb-10 {{ $isDark ? 'text-zinc-500' : 'text-slate-400' }}">
        Seleccioná un espacio en la barra lateral, creá uno nuevo o ingresá un código de invitación para unirte a un espacio compartido.
    </p>

    {{-- Widget: Ingresar código de invitación --}}
    <div class="w-full max-w-sm">
        <div class="rounded-2xl border p-5 {{ $isDark ? 'bg-[#121214] border-zinc-800' : 'bg-white border-slate-200 shadow-sm' }}">
            <div class="flex items-center space-x-2 mb-4">
                <div class="p-1.5 rounded-lg {{ $isDark ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-indigo-50 border border-indigo-100' }}">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-sm font-bold {{ $isDark ? 'text-zinc-100' : 'text-slate-800' }}">Tengo un código de invitación</h2>
            </div>

            @if(session('success'))
                <div class="mb-3 p-3 rounded-xl text-xs font-medium {{ $isDark ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-green-50 border border-green-200 text-green-700' }}">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-3 p-3 rounded-xl text-xs font-medium {{ $isDark ? 'bg-red-500/10 border border-red-500/20 text-red-400' : 'bg-red-50 border border-red-200 text-red-700' }}">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('paginas.invitar.accept.code') }}" class="flex gap-2">
                @csrf
                <input
                    type="text"
                    name="code"
                    maxlength="4"
                    placeholder="Ej: 0MK9"
                    autocomplete="off"
                    required
                    class="flex-1 uppercase font-mono tracking-widest text-center border rounded-xl px-3 py-2.5 text-sm font-bold focus:outline-none focus:ring-1 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all
                        {{ $isDark ? 'bg-[#18181b] border-zinc-700 text-zinc-100 placeholder-zinc-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-300' }}"
                >
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 rounded-xl text-sm font-bold transition-colors shadow-sm cursor-pointer whitespace-nowrap">
                    Unirme
                </button>
            </form>
            <p class="text-xs mt-2.5 {{ $isDark ? 'text-zinc-600' : 'text-slate-400' }}">
                El código de 4 letras lo encontrás en el correo de invitación que te enviaron.
            </p>
        </div>
    </div>

</div>

@endsection