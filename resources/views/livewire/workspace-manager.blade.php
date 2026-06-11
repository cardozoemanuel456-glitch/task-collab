<div>
    @if (session()->has('message'))
        <div class="p-2 mb-4 text-sm text-green-700 bg-green-100 rounded">
            {{ session('message') }}
        </div>
    @endif

    <h3 class="text-lg font-semibold mb-2">1. Crear Espacio de Trabajo</h3>
    <form wire:submit.prevent="createWorkspace" class="space-y-3 mb-6">
        <input type="text" wire:model="name" placeholder="Nombre del proyecto" class="w-full p-2 border rounded">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Guardar en Base de Datos
        </button>
    </form>

    <hr class="my-4">

    <h3 class="text-lg font-semibold mb-2">2. Tus Espacios Actuales</h3>
    <ul class="list-disc pl-5 space-y-1">
        @forelse($workspaces as $workspace)
            <li>
                <strong>{{ $workspace->name }}</strong> 
                <span class="text-xs bg-gray-200 px-2 py-0.5 rounded ml-2">Código: {{ $workspace->invite_code }}</span>
            </li>
        @empty
            <li class="text-gray-400 italic">No hay proyectos creados aún.</li>
        @endforelse
    </ul>
</div>