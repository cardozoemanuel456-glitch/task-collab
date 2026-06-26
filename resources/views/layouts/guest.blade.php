<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskCollab') }}</title>

<<<<<<< HEAD
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/logo.png">
        <link rel="shortcut icon" href="/logo.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
=======
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
>>>>>>> feature-login

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>
<<<<<<< HEAD
    <body class="antialiased bg-slate-950 text-slate-100">
        {{ $slot }}
=======
    <body class="font-sans antialiased bg-[#0b0f19] text-white">
        
        <div class="w-full min-h-screen">
            {{ $slot }}
        </div>

>>>>>>> feature-login
    </body>
</html>