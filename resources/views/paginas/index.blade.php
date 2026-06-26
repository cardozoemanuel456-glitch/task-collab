@extends('paginas.app')

@section('title', 'TaskCollab - Panel de Control')

@section('breadcrumb', 'Inicio')

@section('content')

    <div class="text-center py-20">
        <span class="text-6xl"></span>
        <h2 class="text-5xl font-bold mt-4 {{ auth()->user()->dark_mode ? 'text-neutral-100' : 'text-neutral-900' }}">
            ¡Bienvenido a TaskCollab!
        </h2>
        <p class="{{ auth()->user()->dark_mode ? 'text-neutral-500' : 'text-neutral-400' }} text-sm mt-2 max-w-sm mx-auto">
            Seleccioná un espacio de trabajo en la barra lateral o creá uno nuevo para empezar a organizar tus actividades.
        </p>
        {{-- Logo debajo del mensaje --}}
        <div class="flex justify-center mt-8 pointer-events-none select-none">
            <img src="{{ asset('logo-tc.png') }}" alt=""
                class="logo-tc-dashboard {{ auth()->user()->dark_mode ? 'logo-tc-dashboard-dark' : 'logo-tc-dashboard-light' }}">
        </div>
    </div>

@endsection