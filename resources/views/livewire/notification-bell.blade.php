@php
    $isDark = auth()->user()->dark_mode;
@endphp

<div class="relative"
     x-data="{ open: false }"
     x-on:click.outside="open = false"
     wire:poll.15s="checkNew">

    {{-- Botón de campana --}}
    <button x-on:click="open = !open"
        class="p-2 rounded-xl border transition-all cursor-pointer flex items-center justify-center relative
        {{ $isDark
            ? 'bg-[#18181b] border-zinc-800 text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800'
            : 'bg-white border-slate-200 text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}"
        title="Notificaciones">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Badge de notificaciones no leídas --}}
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center rounded-full text-[10px] font-bold text-white bg-red-500 shadow-sm shadow-red-500/30 px-1 animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel desplegable de notificaciones --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
         x-cloak
         class="absolute right-0 mt-2 w-80 max-h-[420px] rounded-2xl shadow-2xl border overflow-hidden z-50
         {{ $isDark ? 'bg-[#121214] border-zinc-800' : 'bg-white border-slate-200' }}">

        {{-- Header del panel --}}
        <div class="flex items-center justify-between px-4 py-3 border-b {{ $isDark ? 'border-zinc-800/60' : 'border-slate-200/80' }}">
            <h4 class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-zinc-300' : 'text-slate-700' }}">
                <svg class="w-3.5 h-3.5 inline-block mr-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notificaciones
                @if($unreadCount > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/15 text-red-500">{{ $unreadCount }}</span>
                @endif
            </h4>
            <div class="flex items-center space-x-1">
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="text-[10px] font-semibold px-2 py-1 rounded-lg transition-colors cursor-pointer
                        {{ $isDark ? 'text-indigo-400 hover:bg-zinc-800' : 'text-indigo-600 hover:bg-slate-100' }}"
                        title="Marcar todas como leídas">
                        Leer todo
                    </button>
                @endif
                @if($notifications->count() > 0)
                    <button wire:click="clearAll"
                        class="text-[10px] font-semibold px-2 py-1 rounded-lg transition-colors cursor-pointer
                        {{ $isDark ? 'text-zinc-500 hover:bg-zinc-800 hover:text-red-400' : 'text-slate-400 hover:bg-slate-100 hover:text-red-500' }}"
                        title="Limpiar todas">
                        Limpiar
                    </button>
                @endif
            </div>
        </div>

        {{-- Lista de notificaciones --}}
        <div class="overflow-y-auto max-h-[340px] divide-y {{ $isDark ? 'divide-zinc-800/50' : 'divide-slate-100' }}">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $typeIcon = match($data['type'] ?? '') {
                        'task_assigned' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />',
                        'task_status_updated' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
                        default => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                    };
                    $typeColor = match($data['type'] ?? '') {
                        'task_assigned' => 'text-indigo-500 bg-indigo-500/10',
                        'task_status_updated' => 'text-amber-500 bg-amber-500/10',
                        default => 'text-slate-400 bg-slate-400/10'
                    };
                @endphp

                <div wire:click="markAsRead('{{ $notification->id }}')"
                     class="flex items-start gap-3 px-4 py-3 transition-colors cursor-pointer group
                     {{ $isUnread
                        ? ($isDark ? 'bg-indigo-500/5 hover:bg-zinc-800/60' : 'bg-indigo-50/50 hover:bg-slate-50')
                        : ($isDark ? 'hover:bg-zinc-800/40' : 'hover:bg-slate-50/80') }}">

                    {{-- Icono del tipo --}}
                    <div class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center {{ $typeColor }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            {!! $typeIcon !!}
                        </svg>
                    </div>

                    {{-- Contenido --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs leading-relaxed {{ $isDark ? 'text-zinc-300' : 'text-slate-700' }}">
                            <span class="font-bold {{ $isDark ? 'text-zinc-100' : 'text-slate-900' }}">{{ $data['sender_name'] ?? 'Usuario' }}</span>
                            {{ $data['message'] ?? '' }}
                            <span class="font-semibold {{ $isDark ? 'text-indigo-400' : 'text-indigo-600' }}">"{{ Str::limit($data['task_title'] ?? '', 30) }}"</span>
                        </p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] {{ $isDark ? 'text-zinc-600' : 'text-slate-400' }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if(!empty($data['pagina_title']))
                                <span class="text-[10px] px-1.5 py-0.5 rounded-md {{ $isDark ? 'bg-zinc-800 text-zinc-500' : 'bg-slate-100 text-slate-400' }}">
                                    {{ Str::limit($data['pagina_title'], 20) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Indicador de no leída --}}
                    @if($isUnread)
                        <div class="shrink-0 w-2 h-2 rounded-full bg-indigo-500 mt-1.5 shadow-sm shadow-indigo-500/50"></div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-10 px-4">
                    <svg class="w-10 h-10 mb-3 {{ $isDark ? 'text-zinc-700' : 'text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-xs font-semibold {{ $isDark ? 'text-zinc-500' : 'text-slate-400' }}">Sin notificaciones</p>
                    <p class="text-[10px] {{ $isDark ? 'text-zinc-600' : 'text-slate-300' }} mt-0.5">Las nuevas aparecerán aquí</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
