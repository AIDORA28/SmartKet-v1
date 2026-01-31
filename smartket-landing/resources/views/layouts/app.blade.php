<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartKet')</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if(!Route::is('login') && !Route::is('registro'))
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <a href="{{ url('/') }}" class="flex items-center" aria-label="Ir al landing de SmartKet">
                    <img src="/img/SmartKet.svg" alt="SmartKet" class="h-12 w-auto" />
                    <span class="ml-3 text-2xl font-bold">
                        <span class="text-amber-500 uppercase tracking-wide">SMART</span>
                        <span class="text-gray-900 lowercase">ket</span>
                    </span>
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-red-600 px-6 py-2 rounded-lg font-medium">Iniciar Sesión</a>
                    <a href="{{ route('registro') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">Registrarse</a>
                </div>
            </div>
        </div>
    </nav>
    @endif
    <div class="min-h-screen">
        @yield('content')
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
