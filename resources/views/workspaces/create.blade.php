<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crear Workspace
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('workspaces.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded" required>
                            @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium">Descripción</label>
                            <textarea name="description" class="mt-1 block w-full rounded">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
