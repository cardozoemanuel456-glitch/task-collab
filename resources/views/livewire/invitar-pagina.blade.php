<div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
  <h3 class="text-xl font-bold mb-4 text-gray-800">Invitar a un colaborador</h3>

  <!-- Formulario -->
  <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
    <input type="email" wire:model="email" wire:loading.attr="disabled"
      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      placeholder="usuario@ejemplo.com">

    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
  </div>

  <button wire:click="send" wire:loading.attr="disabled"
    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition disabled:opacity-50">
    <span wire:loading.remove>Enviar Invitación</span>
    <span wire:loading>Enviando...</span>
  </button>

  <!-- Mensaje de Éxito -->
  @if($successMessage)
    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
      <p class="text-green-800 font-medium">{{ $successMessage }}</p>
      @if($inviteCode)
        <div class="mt-3 bg-white p-3 rounded border border-green-300 flex justify-between items-center">
          <span class="font-mono text-lg font-bold text-gray-800">{{ $inviteCode }}</span>
          <button onclick="navigator.clipboard.writeText('{{ $inviteCode }}'); alert('Código copiado!')"
            class="text-xs text-indigo-600 hover:underline">
            Copiar
          </button>
        </div>
      @endif
    </div>
  @endif

  <!-- Mensaje de Error -->
  @if($errorMessage)
    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
      {{ $errorMessage }}
    </div>
  @endif

  <div class="mt-4 text-right">
    <button wire:click="$dispatch('close-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cerrar</button>
  </div>
</div>