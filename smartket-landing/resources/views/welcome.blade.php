@extends('layouts.app')

@section('title', 'SmartKet - El ERP tan fácil como una red social')

@section('content')
    <!-- Hero Section v5 Style -->
    <section class="relative bg-gradient-to-br from-red-700 via-red-800 to-red-900 text-white overflow-hidden">
        <!-- Floating Decoratives -->
        <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-64 h-64 bg-red-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Hero Content -->
                <div class="text-left space-y-8">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-sm font-medium animate-fade-in">
                        <span class="flex h-2 w-2 rounded-full bg-yellow-400 animate-ping"></span>
                        <span>Nueva versión v1.2 disponible</span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-[1.1] tracking-tight">
                        El ERP más <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-400">fácil de usar</span> <br/>
                        para tu negocio
                    </h1>
                    
                    <p class="text-xl text-red-100/90 leading-relaxed max-w-xl">
                        Diseñado para emprendedores que buscan profesionalismo sin complicaciones.
                        Controla ventas, stock y clientes con la simplicidad de una red social.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a href="{{ route('registro') }}" class="group relative inline-flex items-center justify-center bg-white text-red-700 px-8 py-4 rounded-2xl font-bold text-lg transition-all hover:bg-slate-50 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)] active:scale-95">
                            🚀 Comenzar Gratis
                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                        <button 
                            class="inline-flex items-center justify-center border-2 border-white/30 backdrop-blur-sm text-white hover:bg-white/10 px-8 py-4 rounded-2xl font-bold text-lg transition-all active:scale-95"
                            onclick="document.getElementById('caracteristicas').scrollIntoView({ behavior: 'smooth' })"
                        >
                            📋 Ver Funciones
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4 text-xs font-semibold uppercase tracking-widest text-red-200/70">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Sin tarjeta</span>
                        <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                        <span>14 días gratis</span>
                        <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                        <span>Soporte Lite</span>
                    </div>
                </div>
                
                <!-- Hero Visual with Interactive Badges -->
                <div class="relative group">
                    <!-- Glass Badges -->
                    <div class="absolute -top-6 -left-6 z-20 bg-white/90 backdrop-blur shadow-2xl p-4 rounded-2xl border border-white flex items-center gap-3 animate-bounce-slow">
                        <div class="bg-green-100 p-2 rounded-lg text-green-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase">Venta realizada</p>
                            <p class="text-sm font-black text-gray-900">+ S/ 1,250.00</p>
                        </div>
                    </div>

                    <div class="absolute -bottom-10 -right-6 z-20 bg-amber-400 p-4 rounded-2xl shadow-2xl border-4 border-white flex items-center gap-3 animate-float">
                        <div class="bg-white/20 p-2 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4v10M4 11v10l8 4" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-red-900 font-bold uppercase">Stock Alerta</p>
                            <p class="text-sm font-black text-red-900">Pollo a la Brasa (Bajo)</p>
                        </div>
                    </div>

                    <div class="relative bg-white/5 backdrop-blur-sm rounded-[2rem] shadow-2xl p-4 border border-white/20 transition-all duration-700 group-hover:rotate-0 rotate-2">
                        <video 
                            autoplay 
                            muted 
                            loop
                            playsinline
                            class="w-full rounded-[1.5rem] shadow-inner"
                        >
                            <source src="/video/Minimarket.mp4" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <div class="bg-white py-6 border-b border-gray-100">
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
    <section id="caracteristicas" class="bg-slate-50 py-12 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-red-600 font-bold uppercase tracking-widest text-sm">Características Elite</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mt-2">Todo lo que necesitas, <span class="text-red-600 underline decoration-slate-200">radicalmente simple</span></h2>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-slate-500 leading-relaxed">Olvídate de manuales eternos. SmartKet está construido para que empieces a vender desde el minuto uno.</p>
            </div>

            <div x-data="{ activeTab: 1 }" class="max-w-6xl mx-auto">
                <div class="flex justify-center flex-wrap gap-4 mb-12">
                    <button @click="activeTab = 1" :class="{'bg-red-600 text-white shadow-xl scale-105': activeTab === 1, 'bg-white text-slate-500 hover:bg-slate-100': activeTab !== 1}" class="px-8 py-3 font-bold rounded-2xl transition-all duration-300 border border-slate-200/50">Usabilidad Radical</button>
                    <button @click="activeTab = 2" :class="{'bg-red-600 text-white shadow-xl scale-105': activeTab === 2, 'bg-white text-slate-500 hover:bg-slate-100': activeTab !== 2}" class="px-8 py-3 font-bold rounded-2xl transition-all duration-300 border border-slate-200/50">Arranque en Minutos</button>
                    <button @click="activeTab = 3" :class="{'bg-red-600 text-white shadow-xl scale-105': activeTab === 3, 'bg-white text-slate-500 hover:bg-slate-100': activeTab !== 3}" class="px-8 py-3 font-bold rounded-2xl transition-all duration-300 border border-slate-200/50">Crecimiento Modular</button>
                </div>

                <div class="relative bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 p-8 md:p-12 overflow-hidden min-h-[500px]">
                    <div x-show="activeTab === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <h3 class="text-3xl font-black text-slate-900">Diseñado para personas, no para ingenieros</h3>
                            <p class="text-slate-600 text-lg">Si sabes usar WhatsApp, sabes usar SmartKet. Nuestra interfaz intuitiva elimina el miedo a la tecnología.</p>
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Punto de Venta (POS) ultrarápido
                                </li>
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Gestión de stock sin errores
                                </li>
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Reportes automatizados por WhatsApp
                                </li>
                            </ul>
                        </div>
                        <div class="bg-slate-100 rounded-2xl p-4 shadow-inner">
                            <img src="https://placehold.co/800x600/f87171/ffffff?text=Dashboard+Mobile" alt="SmartKet Dashboard" class="rounded-xl shadow-2xl">
                        </div>
                    </div>

                    <div x-show="activeTab === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <h3 class="text-3xl font-black text-slate-900">Configuración en tiempo récord</h3>
                            <p class="text-slate-600 text-lg">Te registras, respondes 3 preguntas sobre tu negocio y ¡boom! ya tienes tu inventario listo.</p>
                             <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Sin instalaciones pesadas
                                </li>
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Asistente inteligente de rubro
                                </li>
                            </ul>
                        </div>
                        <div class="bg-slate-100 rounded-2xl p-4 shadow-inner">
                            <img src="https://placehold.co/800x600/fb923c/ffffff?text=Express+Setup" alt="SmartKet Setup" class="rounded-xl shadow-2xl">
                        </div>
                    </div>

                    <div x-show="activeTab === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <h3 class="text-3xl font-black text-slate-900">Escala cuando tú quieras</h3>
                            <p class="text-slate-600 text-lg">No pagas por funciones que no usas. Si abres una sucursal o necesitas delivery, activa el módulo con un clic.</p>
                             <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Módulos para Restaurantes y Retail
                                </li>
                                <li class="flex items-center gap-3 text-slate-700 font-medium">
                                    <div class="bg-red-100 p-1 rounded-full text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 14.1414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                    Gestión de sucursales centralizada
                                </li>
                            </ul>
                        </div>
                        <div class="bg-slate-100 rounded-2xl p-4 shadow-inner">
                            <img src="https://placehold.co/800x600/fbbf24/ffffff?text=Modular+System" alt="SmartKet Escalamiento" class="rounded-xl shadow-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Background Decoratives -->
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-100/50 rounded-full blur-3xl"></div>
    </section>

    <!-- Comparative Section -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-slate-900">¿Por qué SmartKet?</h2>
                <p class="text-slate-500 mt-4">La diferencia entre lo complejo y lo inteligente.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100">
                    <h3 class="text-xl font-bold text-slate-400 mb-6 uppercase tracking-wider">ERP Tradicional</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-slate-400">
                            <svg class="w-5 h-5 mt-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Instalación lenta y costosa
                        </li>
                        <li class="flex items-start gap-3 text-slate-400">
                            <svg class="w-5 h-5 mt-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Interfaz abrumadora y gris
                        </li>
                        <li class="flex items-start gap-3 text-slate-400">
                            <svg class="w-5 h-5 mt-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Soporte que tarda días
                        </li>
                    </ul>
                </div>
                
                <div class="bg-red-50 p-8 rounded-3xl border-2 border-red-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <h3 class="text-xl font-bold text-red-600 mb-6 uppercase tracking-wider relative z-10">SmartKet Elite</h3>
                    <ul class="space-y-4 relative z-10">
                        <li class="flex items-start gap-3 text-red-900 font-semibold">
                            <svg class="w-5 h-5 mt-1 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Listo en 5 minutos (Cloud)
                        </li>
                        <li class="flex items-start gap-3 text-red-900 font-semibold">
                            <svg class="w-5 h-5 mt-1 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Interfaz moderna e intuitiva
                        </li>
                        <li class="flex items-start gap-3 text-red-900 font-semibold">
                            <svg class="w-5 h-5 mt-1 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Soporte prioritario por WhatsApp
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="precios" class="py-24 bg-slate-900 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-red-600/10 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black italic">Precios Honestos</h2>
                <p class="text-slate-400 mt-4">Sin contratos ocultos. Cancela cuando quieras.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Free Plan -->
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-3xl border border-slate-700 hover:border-slate-500 transition-all">
                    <h3 class="text-lg font-bold mb-2">Primeros Pasos</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-4xl font-black">Gratis</span>
                        <span class="text-slate-400 ml-2">/14 días</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Todo el sistema abierto</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 1 Usuario</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Soporte Base</li>
                    </ul>
                    <a href="{{ route('registro') }}" class="block text-center py-3 px-6 rounded-xl border border-white/20 hover:bg-white/10 font-bold transition-all">Empezar ahora</a>
                </div>

                <!-- Pro Plan -->
                <div class="bg-white text-slate-900 p-8 rounded-3xl border-4 border-red-600 shadow-2xl md:scale-110 relative z-20">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-red-600 text-white px-4 py-1 rounded-full text-xs font-black uppercase tracking-tighter">Más Popular</div>
                    <h3 class="text-lg font-bold mb-2">Negocio Pro</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-4xl font-black">S/ 49</span>
                        <span class="text-slate-500 ml-2">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm text-slate-600">
                        <li class="flex items-center gap-2 font-bold"><svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Ventas Ilimitadas</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 3 Usuarios</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Módulos pollería/retail</li>
                    </ul>
                    <a href="{{ route('registro') }}" class="block text-center py-3 px-6 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg transition-all">Obtener Pro</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-3xl border border-slate-700 hover:border-slate-500 transition-all">
                    <h3 class="text-lg font-bold mb-2">Corporativo</h3>
                    <div class="flex items-baseline mb-6">
                        <span class="text-4xl font-black">S/ 120</span>
                        <span class="text-slate-400 ml-2">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Múltiples Sucursales</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Usuarios Ilimitados</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Soporte 24/7 VIP</li>
                    </ul>
                    <a href="{{ route('registro') }}" class="block text-center py-3 px-6 rounded-xl border border-white/20 hover:bg-white/10 font-bold transition-all">Contactar Ventas</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900">Lo que dicen de nosotros</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <div class="group bg-slate-50 p-10 rounded-[2rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="flex gap-1 text-amber-400 mb-6 group-hover:scale-110 transition-transform origin-left">
                        @for($i=0; $i<5; $i++) <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-slate-700 text-lg italic leading-relaxed mb-8">"SmartKet cambió las reglas del juego. Pasamos de un sistema torpe a tener el control total en un día. ¡Ventas arriba un 20%!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-lg">
                            <img src="https://i.pravatar.cc/150?img=1" alt="Juan Pérez" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-none">Juan Pérez</p>
                            <p class="text-slate-500 text-xs mt-1">Dueño de Pollos Del Rey</p>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-slate-50 p-10 rounded-[2rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="flex gap-1 text-amber-400 mb-6 group-hover:scale-110 transition-transform origin-left">
                        @for($i=0; $i<5; $i++) <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-slate-700 text-lg italic leading-relaxed mb-8">"Lo que más me gusta es la simplicidad. Los reportes son claros y directos. 100% recomendado para cualquier minimarket."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-lg">
                            <img src="https://i.pravatar.cc/150?img=2" alt="Maria Garcia" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-none">Maria Garcia</p>
                            <p class="text-slate-500 text-xs mt-1">Gerente de Minimarket 'La Esquina'</p>
                        </div>
                    </div>
                </div>

                <div class="group bg-slate-50 p-10 rounded-[2rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="flex gap-1 text-amber-400 mb-6 group-hover:scale-110 transition-transform origin-left">
                        @for($i=0; $i<5; $i++) <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-slate-700 text-lg italic leading-relaxed mb-8">"En menos de 5 minutos ya estaba añadiendo mis productos. El soporte por WhatsApp es increíblemente atento."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-lg">
                            <img src="https://i.pravatar.cc/150?img=3" alt="Carlos Rodriguez" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-none">Carlos Rodriguez</p>
                            <p class="text-slate-500 text-xs mt-1">Fundador de FarmaBien</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-black text-center mb-12">Preguntas Frecuentes</h2>
            <div x-data="{ active: null }" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full flex items-center justify-between p-6 text-left font-bold text-slate-900">
                        ¿Realmente es gratis los primeros 14 días?
                        <svg class="w-5 h-5 transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 pb-6 text-slate-600">
                        ¡Claro! Sin tarjetas de crédito, sin complicaciones. Queremos que pruebes la potencia de SmartKet sin riesgos.
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full flex items-center justify-between p-6 text-left font-bold text-slate-900">
                        ¿Mis datos están seguros en la nube?
                        <svg class="w-5 h-5 transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 pb-6 text-slate-600">
                        Cada cliente en SmartKet posee su propia base de datos aislada en PostgreSQL. Tus datos nunca se mezclan con los de otros negocios.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="bg-slate-950 relative overflow-hidden">
        <div class="absolute inset-0 bg-red-600/5 blur-3xl rounded-full"></div>
        <div class="max-w-4xl mx-auto text-center py-24 px-4 sm:px-6 lg:px-8 relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                ¿Listo para simplificar <br/> la gestión de tu negocio?
            </h2>
            <p class="mt-6 text-xl text-slate-400">Únete a cientos de negocios que ya están creciendo con claridad y control.</p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('registro') }}" class="inline-flex items-center justify-center px-10 py-5 rounded-2xl shadow-xl text-lg font-bold text-white bg-red-600 hover:bg-red-700 transition-all active:scale-95">
                    🚀 Empezar Gratis Ahora
                </a>
                <a href="#" class="inline-flex items-center justify-center px-10 py-5 rounded-2xl text-lg font-bold text-slate-300 border border-slate-800 hover:bg-slate-900 transition-all">
                    📞 Hablar con un asesor
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-6">
                        <img src="/img/SmartKet.svg" alt="SmartKet" class="h-8 w-auto">
                        <span class="ml-3 text-xl font-black tracking-tighter">SMART<span class="text-red-600">ket</span></span>
                    </div>
                    <p class="text-slate-500 max-w-sm">El ERP diseñado para que tú te centres en vender, no en aprender a usar un software complejo.</p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Producto</h4>
                    <ul class="space-y-4 text-slate-500">
                        <li><a href="#" class="hover:text-red-600">Funciones</a></li>
                        <li><a href="#precios" class="hover:text-red-600">Precios</a></li>
                        <li><a href="#" class="hover:text-red-600">Seguridad</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Compañía</h4>
                    <ul class="space-y-4 text-slate-500">
                        <li><a href="#" class="hover:text-red-600">Sobre nosotros</a></li>
                        <li><a href="#" class="hover:text-red-600">Contacto</a></li>
                        <li><a href="#" class="hover:text-red-600">Legal</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-400 text-sm">&copy; {{ date('Y') }} SmartKet. Todos los derechos reservados.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-400 hover:text-red-600 transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
@endsection

