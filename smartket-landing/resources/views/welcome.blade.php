@extends('layouts.app')

@section('title', 'SmartKet - El ERP tan fácil como una red social')

@section('content')
    <!-- Hero Section v5 Style -->
    <section class="bg-gradient-to-r from-red-700 to-red-800 text-white overflow-hidden shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-left">
                    <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight">
                        El ERP más <span class="text-yellow-300">fácil de usar</span> para tu negocio
                    </h1>
                    <p class="text-xl mb-8 text-red-50 text-opacity-90 leading-relaxed">
                        Diseñado especialmente para pequeñas y medianas empresas que quieren 
                        un sistema profesional sin complicaciones. ¡Por fin un ERP que todos pueden usar!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('registro') }}" class="inline-block bg-white text-red-700 hover:bg-gray-100 px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl text-center">
                            🚀 Comenzar Gratis
                        </a>
                        <button 
                            class="w-full sm:w-auto border-2 border-white text-white hover:bg-white hover:text-red-700 px-8 py-4 rounded-xl font-bold text-lg transition-all text-center"
                            onclick="document.getElementById('caracteristicas').scrollIntoView({ behavior: 'smooth' })"
                        >
                            📋 Ver Características
                        </button>
                    </div>
                    <div class="flex items-center text-sm text-red-100 space-x-2">
                        <span class="bg-red-600/50 px-2 py-1 rounded">✨ Sin tarjeta</span>
                        <span class="bg-red-600/50 px-2 py-1 rounded">✨ 14 días gratis</span>
                        <span class="bg-red-600/50 px-2 py-1 rounded">✨ Soporte 24/7</span>
                    </div>
                </div>
                
                <!-- Hero Video/Visual -->
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl p-2 transform rotate-2 hover:rotate-0 transition-all duration-500">
                        <video 
                            autoplay 
                            muted 
                            loop
                            playsinline
                            class="w-full rounded-xl shadow-inner border border-gray-100"
                        >
                            <source src="/video/Minimarket.mp4" type="video/mp4" />
                            Tu navegador no soporta el elemento video.
                        </video>
                        <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-red-900 px-4 py-2 rounded-full text-xs font-black shadow-xl animate-pulse">
                            DEMO EN VIVO
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold text-gray-500">CON LA CONFIANZA DE NEGOCIOS COMO EL TUYO</p>
            <div class="mt-6 grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
                <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                    <p class="text-lg font-bold text-gray-400">Pollos Hermanos</p>
                </div>
                <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                    <p class="text-lg font-bold text-gray-400">Minimarket ACME</p>
                </div>
                <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                    <p class="text-lg font-bold text-gray-400">Farmacia Cruz Verde</p>
                </div>
                <div class="col-span-1 flex justify-center md:col-span-3 lg:col-span-1">
                    <p class="text-lg font-bold text-gray-400">Restaurante El Buen Sabor</p>
                </div>
                <div class="col-span-2 flex justify-center md:col-span-3 lg:col-span-1">
                    <p class="text-lg font-bold text-gray-400">Bodega Don Pepe</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section with Tabs -->
    <section id="caracteristicas" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Todo lo que necesitas, radicalmente simple</h2>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">Hemos quitado lo complejo para que te centres en lo que importa: vender más.</p>
            </div>

            <div x-data="{ activeTab: 1 }" class="max-w-4xl mx-auto">
                <div class="flex justify-center border-b border-gray-200">
                    <button @click="activeTab = 1" :class="{'border-red-600 text-red-600': activeTab === 1, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 1}" class="px-6 py-3 font-medium border-b-2 transition-colors">Usabilidad Radical</button>
                    <button @click="activeTab = 2" :class="{'border-red-600 text-red-600': activeTab === 2, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 2}" class="px-6 py-3 font-medium border-b-2 transition-colors">Arranque en Minutos</button>
                    <button @click="activeTab = 3" :class="{'border-red-600 text-red-600': activeTab === 3, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 3}" class="px-6 py-3 font-medium border-b-2 transition-colors">Crecimiento Modular</button>
                </div>

                <div class="mt-8">
                    <div x-show="activeTab === 1" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="text-gray-700">
                            <h3 class="text-2xl font-bold mb-4">Diseñado para personas, no para ingenieros</h3>
                            <p class="mb-4">Si sabes usar Facebook, sabes usar SmartKet. Nuestra interfaz es visual, limpia y te guía en cada paso. Vender, añadir un producto o ver un reporte es tan fácil como publicar una foto.</p>
                            <ul class="list-disc list-inside space-y-2">
                                <li>Punto de Venta (POS) rápido e intuitivo.</li>
                                <li>Gestión de inventario sin complicaciones.</li>
                                <li>Reportes que entiendes de un vistazo.</li>
                            </ul>
                        </div>
                        <img src="https://placehold.co/600x400/f87171/ffffff?text=Interfaz+Simple" alt="Interfaz simple de SmartKet" class="rounded-lg shadow-lg">
                    </div>
                    <div x-show="activeTab === 2" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="text-gray-700">
                            <h3 class="text-2xl font-bold mb-4">Tu negocio funcionando en 5 minutos</h3>
                            <p class="mb-4">Olvídate de largas llamadas de ventas e instalaciones complejas. Con SmartKet, te registras, configuras tus datos básicos con nuestro asistente y empiezas a vender. Así de simple.</p>
                             <ul class="list-disc list-inside space-y-2">
                                <li>Registro sin fricción, solo con tu email.</li>
                                <li>Asistente de configuración inicial guiado.</li>
                                <li>14 días de prueba gratis, sin compromiso.</li>
                            </ul>
                        </div>
                        <img src="https://placehold.co/600x400/fb923c/ffffff?text=Setup+Rápido" alt="Asistente de configuración de SmartKet" class="rounded-lg shadow-lg">
                    </div>
                    <div x-show="activeTab === 3" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="text-gray-700">
                            <h3 class="text-2xl font-bold mb-4">Empieza simple, crece sin límites</h3>
                            <p class="mb-4">No te abrumamos con cientos de opciones que no necesitas. Empiezas con un sistema limpio y funcional, y activas módulos adicionales (como delivery o gestión de mesas) solo cuando tu negocio esté listo para escalar.</p>
                             <ul class="list-disc list-inside space-y-2">
                                <li>Sistema base con todo lo esencial.</li>
                                <li>Módulos específicos por tipo de negocio (Pollería, etc.).</li>
                                <li>Planes flexibles que se adaptan a tu crecimiento.</li>
                            </ul>
                        </div>
                        <img src="https://placehold.co/600x400/fbbf24/ffffff?text=Módulos+Flexibles" alt="Módulos adicionales de SmartKet" class="rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">No confíes en nuestra palabra, confía en sus resultados</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200">
                    <p class="text-gray-700 mb-6">"SmartKet cambió las reglas del juego para nosotros. Pasamos de un sistema torpe que nadie entendía a tener el control total de nuestro inventario y ventas en un día. ¡Las ventas aumentaron un 20%!"</p>
                    <div class="flex items-center">
                        <img src="https://i.pravatar.cc/150?img=1" alt="Juan Pérez" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-bold text-gray-900">Juan Pérez</p>
                            <p class="text-gray-600">Dueño de Pollos Del Rey</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200">
                    <p class="text-gray-700 mb-6">"Lo que más me gusta es la simplicidad. No necesito ser un experto en tecnología para saber si estoy ganando o perdiendo dinero. Los reportes son claros y directos. 100% recomendado."</p>
                    <div class="flex items-center">
                        <img src="https://i.pravatar.cc/150?img=2" alt="Maria Garcia" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-bold text-gray-900">Maria Garcia</p>
                            <p class="text-gray-600">Gerente de Minimarket 'La Esquina'</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200">
                    <p class="text-gray-700 mb-6">"El proceso de registro fue increíblemente rápido. Literalmente, en menos de 5 minutos ya estaba añadiendo mis productos. El soporte también es muy atento. Se siente como si estuvieran de tu lado."</p>
                    <div class="flex items-center">
                        <img src="https://i.pravatar.cc/150?img=3" alt="Carlos Rodriguez" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-bold text-gray-900">Carlos Rodriguez</p>
                            <p class="text-gray-600">Fundador de FarmaBien</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="bg-gray-900">
        <div class="max-w-4xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">¿Listo para simplificar la gestión de tu negocio?</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-gray-300">Únete a cientos de negocios que ya están creciendo con claridad y control. Tu prueba de 14 días te espera.</p>
            <a href="{{ route('registro') }}" class="mt-8 w-full inline-flex items-center justify-center px-8 py-4 border border-transparent rounded-xl shadow-sm text-base font-medium text-red-600 bg-white hover:bg-gray-100 sm:w-auto">
                🚀 Empezar Gratis Ahora
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="flex justify-center space-x-6 md:order-2">
                <a href="#" class="text-gray-400 hover:text-gray-500">Facebook</a>
                <a href="#" class="text-gray-400 hover:text-gray-500">Twitter</a>
                <a href="#" class="text-gray-400 hover:text-gray-500">LinkedIn</a>
            </div>
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-base text-gray-400">&copy; 2025 SmartKet. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

