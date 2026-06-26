<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row bg-slate-950 text-slate-100">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 justify-center items-center p-12 border-r border-slate-800">
           <div class="max-w-md text-center md:text-left">
           <div class="mb-4 flex justify-center w-full">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo TaskCollab" 
                     class="h-58 w-58 md:h-74 md:w-74 object-contain filter drop-shadow-[0_0_20px_rgba(99,102,241,0.35)]">
            </div>

            <span class="text-xs font-bold tracking-widest text-indigo-400 uppercase bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-500/20">
                HARD-CODE dev 2026
            </span>
                <h1 class="text-5xl font-black tracking-tight mt-4 text-white">
                    Task<span class="text-indigo-500">Collab</span>
                </h1>
                <p class="mt-4 text-slate-400 text-lg leading-relaxed">
                    Unite al tablero y empezá a coordinar tus entregas y tareas con tu equipo en tiempo real.
                </p>
                <div class="mt-8 flex gap-2 justify-center md:justify-start">
                    <span class="h-2 w-2 rounded-full bg-slate-700"></span>
                    <span class="h-2 w-12 rounded-full bg-indigo-500"></span>
                    <span class="h-2 w-2 rounded-full bg-slate-700"></span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex justify-center items-center p-8 sm:p-12 md:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/40 p-8 rounded-2xl border border-slate-800/80 backdrop-blur-sm shadow-2xl">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Crear una cuenta</h2>
                    <p class="text-slate-400 text-sm mt-1">Completá tus datos para darte de alta en la plataforma.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">Nombre Completo</label>
                        <input id="name" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nombre Completo" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-300 mb-1">Correo Electrónico</label>
                        <input id="email" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu_correo@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-300 mb-1">Contraseña</label>
                        <input id="password" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-300 mb-1">Confirmar Contraseña</label>
                        <input id="password_confirmation" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-950 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Registrarse
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-4 border-t border-slate-800/60 text-center text-sm text-slate-400">
                    ¿Ya estás registrado? 
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition">
                        Iniciá sesión acá
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>