@php $isDark = auth()->user()->dark_mode; @endphp

<div class="
    {{ $isDark ? 'bg-[#121214] border-zinc-800 text-zinc-100' : 'bg-white border-slate-200 text-slate-800' }}
    rounded-2xl border shadow-2xl p-6 w-full
">
    {{-- Título --}}
    <div class="flex items-center space-x-2 mb-5">
        <div class="p-2 rounded-xl {{ $isDark ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-indigo-50 border border-indigo-200' }}">
            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h3 class="text-base font-bold {{ $isDark ? 'text-zinc-50' : 'text-slate-900' }}">Invitar a un colaborador</h3>
    </div>

    {{-- Input Email --}}
    <div class="mb-4">
        <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 {{ $isDark ? 'text-zinc-400' : 'text-slate-500' }}">
            Correo electrónico
        </label>
        <input
            type="email"
            wire:model="email"
            wire:loading.attr="disabled"
            placeholder="usuario@ejemplo.com"
            class="w-full border rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all disabled:opacity-60
                {{ $isDark
                    ? 'bg-[#18181b] border-zinc-700 text-zinc-100 placeholder-zinc-500'
                    : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'
                }}"
        >
        @error('email')
            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    {{-- Botón Enviar --}}
    <button
        wire:click="send"
        wire:loading.attr="disabled"
        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 px-4 rounded-xl font-semibold text-sm transition-colors shadow-sm shadow-indigo-600/20 disabled:opacity-50 cursor-pointer"
    >
        <span wire:loading.remove>Enviar Invitación</span>
        <span wire:loading class="flex items-center justify-center space-x-2">
            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span>Enviando...</span>
        </span>
    </button>

    {{-- Mensaje de Éxito --}}
    @if($successMessage)
        <div class="mt-4 p-3 rounded-xl border {{ $isDark ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-green-50 border-green-200 text-green-800' }}">
            <p class="text-sm font-medium">{{ $successMessage }}</p>
            @if($inviteCode)
                <div class="mt-3 p-2.5 rounded-xl flex items-center justify-between {{ $isDark ? 'bg-zinc-800 border border-zinc-700' : 'bg-white border border-green-200' }}">
                    <span class="font-mono text-base font-bold tracking-widest {{ $isDark ? 'text-zinc-100' : 'text-slate-800' }}">{{ $inviteCode }}</span>
                    <button
                        onclick="navigator.clipboard.writeText('{{ $inviteCode }}').then(() => { this.textContent = '¡Copiado!'; setTimeout(() => this.textContent = 'Copiar', 2000); })"
                        class="text-xs font-semibold px-2 py-1 rounded-lg transition-colors cursor-pointer {{ $isDark ? 'text-indigo-400 hover:bg-zinc-700' : 'text-indigo-600 hover:bg-indigo-50' }}"
                    >
                        Copiar
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Mensaje de Error --}}
    @if($errorMessage)
        <div class="mt-4 p-3 rounded-xl border {{ $isDark ? 'bg-red-500/10 border-red-500/20 text-red-400' : 'bg-red-50 border-red-200 text-red-700' }}">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    {{-- Cerrar --}}
    <div class="mt-4 text-right">
        <button
            wire:click="$dispatch('close-modal')"
            class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors cursor-pointer {{ $isDark ? 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100' }}"
        >
            Cerrar
        </button>
    </div>
</div>