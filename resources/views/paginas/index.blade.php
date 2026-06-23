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
            Seleccioná un tablero de trabajo en la barra lateral o creá uno nuevo abajo para empezar a organizar tus actividades académicas.
        </p>
    </div>

@endsection