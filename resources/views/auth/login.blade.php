<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row bg-slate-950 text-slate-100">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 justify-center items-center p-12 border-r border-slate-800">
            <div class="max-w-md text-center md:text-left">
                <span class="text-xs font-bold tracking-widest text-indigo-400 uppercase bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-500/30">
                    HARD-CODE dev 2026
                </span>
                <h1 class="text-5xl font-black tracking-tight mt-4 text-white">
                    Task<span class="text-indigo-500">Collab</span>
                </h1>
                <p class="mt-4 text-slate-400 text-lg leading-relaxed">
                    Gestioná tus proyectos al estilo Kanban. Simple y colaborativo 
                </p>
                <div class="mt-8 flex gap-2 justify-center md:justify-start">
                    <span class="h-2 w-12 rounded-full bg-indigo-500"></span>
                    <span class="h-2 w-2 rounded-full bg-slate-700"></span>
                    <span class="h-2 w-2 rounded-full bg-slate-700"></span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex justify-center items-center p-8 sm:p-12 md:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/40 p-8 rounded-2xl border border-slate-800/80 backdrop-blur-sm shadow-2xl">
                
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-white tracking-tight">¡Hola de nuevo!</h2>
                    <p class="text-slate-400 text-sm mt-1">Ingresá tus credenciales para acceder a tus tableros.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-300 mb-2">Correo Electrónico</label>
                        <input id="email" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu_usuario@alumnos.tup" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-300 mb-2">Contraseña</label>
                        <input id="password" class="block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/50 shadow-inner" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-indigo-500/50 focus:ring-offset-slate-950" name="remember">
                            <span class="ms-2 text-slate-400 hover:text-slate-300 transition">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-indigo-400 hover:text-indigo-300 transition font-medium" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-950 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-800/60 text-center text-sm text-slate-400">
                    ¿No tenés cuenta? 
                    <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition">
                        Registrate acá
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>