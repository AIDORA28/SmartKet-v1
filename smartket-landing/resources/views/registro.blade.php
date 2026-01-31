@extends('layouts.app')

@section('title', 'Registro de Negocio - SmartKet')

@section('content')
    <div class="min-h-screen flex bg-white font-sans overflow-hidden" 
         x-data="{ 
            step: 1, 
            totalSteps: 3, 
            nombreNegocio: '', 
            email: '', 
            password: '', 
            selectedRubro: 'polleria',
            selectedPlan: 'free',
            submitting: false,
            
            nextStep() {
                if (this.step === 1 && !this.selectedRubro) {
                    showToast('Por favor, selecciona un rubro.');
                    return;
                }
                if (this.step === 2) {
                    if (this.nombreNegocio.length < 3) {
                        showToast('El nombre del negocio es demasiado corto.');
                        return;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                        showToast('Ingresa un correo válido.');
                        return;
                    }
                    if (!/^(?=.*[A-Z])(?=.*\d).{8,}$/.test(this.password)) {
                        showToast('Contraseña débil (8+ carác., A-Z, 0-9).');
                        return;
                    }
                }
                if (this.step < this.totalSteps) this.step++;
            },
            
            prevStep() {
                if (this.step > 1) this.step--;
            }
         }">
        
        <!-- Brand Panel (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-red-700 via-red-800 to-red-900 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-black/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            
            <div class="relative z-10 flex flex-col justify-center px-16 xl:px-24 text-white w-full">
                <div class="mb-12">
                    <a href="{{ url('/') }}" class="inline-flex items-center space-x-4 group">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/20 group-hover:scale-105 transition-transform">
                            <img src="/img/SmartKet.svg" alt="SmartKet" class="h-10 w-auto filter brightness-0 invert" />
                        </div>
                        <span class="text-3xl font-black tracking-tighter uppercase italic">Smart<span class="text-yellow-400">Ket</span></span>
                    </a>
                </div>

                <div class="max-w-md">
                    <h1 class="text-5xl font-black mb-8 leading-tight tracking-tight">
                        Haz crecer tu negocio hoy mismo
                    </h1>
                    <p class="text-xl text-red-50/80 leading-relaxed mb-12">
                        Únete a la plataforma que está simplificando la vida de cientos de emprendedores. 
                    </p>

                    <!-- Dynamically showing value based on step -->
                    <div class="space-y-6">
                        <template x-if="step === 1">
                            <div class="animate-fade-in space-y-4">
                                <h3 class="text-2xl font-bold text-yellow-400">Paso 1: Tu Identidad</h3>
                                <p class="text-lg text-red-50">SmartKet se adapta a tu rubro para darte las herramientas exactas que necesitas.</p>
                            </div>
                        </template>
                        <template x-if="step === 2">
                            <div class="animate-fade-in space-y-4">
                                <h3 class="text-2xl font-bold text-yellow-400">Paso 2: Seguridad</h3>
                                <p class="text-lg text-red-50">Tus datos están protegidos con estándares bancarios. Crea una contraseña robusta.</p>
                            </div>
                        </template>
                        <template x-if="step === 3">
                            <div class="animate-fade-in space-y-4">
                                <h3 class="text-2xl font-bold text-yellow-400">Paso 3: Tu Estrategia</h3>
                                <p class="text-lg text-red-50">Elige cómo quieres empezar. Puedes cambiar de plan en cualquier momento.</p>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="mt-20 text-red-200/50 text-sm font-medium">
                    &copy; {{ date('Y') }} SmartKet Global. Todos los derechos reservados.
                </div>
            </div>
        </div>

        <!-- Form Panel (Wizard) -->
        <div class="flex-1 flex flex-col justify-center py-12 px-8 sm:px-12 lg:px-24 bg-slate-50 relative overflow-y-auto">
            <div class="max-w-xl w-full mx-auto">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                        <img src="/img/SmartKet.svg" alt="SmartKet" class="h-10 w-auto" />
                        <span class="text-xl font-black tracking-tighter text-slate-900">SMART<span class="text-red-700">ket</span></span>
                    </a>
                </div>

                <!-- Progress Header -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-black uppercase tracking-widest text-red-600" x-text="'Paso ' + step + ' de ' + totalSteps"></span>
                        <div class="flex gap-2">
                            <template x-for="i in totalSteps">
                                <div class="h-2 w-12 rounded-full transition-all duration-300"
                                     :class="i <= step ? 'bg-red-600' : 'bg-slate-200'"></div>
                            </template>
                        </div>
                    </div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight" 
                        x-text="step === 1 ? '¿Qué negocio tienes?' : (step === 2 ? 'Crea tu cuenta' : '¿Cómo quieres empezar?')"></h2>
                </div>

                <!-- Step 1: Grupo de Negocio -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <!-- Card: HORECA (Habilitado) -->
                        <label class="relative group cursor-pointer border-2 rounded-3xl p-8 bg-white transition-all duration-300 flex items-start gap-6"
                               :class="selectedRubro === 'polleria' ? 'border-red-600 bg-red-50/50 shadow-xl shadow-red-100' : 'border-slate-100 hover:border-red-400'">
                            <input type="radio" x-model="selectedRubro" value="polleria" class="sr-only" />
                            <div class="text-6xl group-hover:scale-110 transition-transform flex-shrink-0">🍽️</div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-2xl font-black text-slate-900 leading-none">Gastronomía (HORECA)</h3>
                                    <span class="bg-red-600 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-full">Habilitado</span>
                                </div>
                                <p class="text-slate-500 text-sm mb-4">Gestión de mesas, comandas, recetas y producción.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100">Pollería</span>
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100">Pizzería</span>
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100">Restaurante</span>
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100">Cafetería</span>
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100">Bar</span>
                                </div>
                            </div>
                        </label>

                        <!-- Card: RETAIL (Muy pronto) -->
                        <div class="relative border-2 border-slate-100 rounded-3xl p-8 bg-slate-50 opacity-50 grayscale flex items-start gap-6">
                            <div class="text-6xl flex-shrink-0">🛒</div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-2xl font-black text-slate-700 leading-none">Comercio (Retail)</h3>
                                    <span class="bg-slate-400 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-full italic">Muy pronto</span>
                                </div>
                                <p class="text-slate-400 text-sm mb-4">Stock por códigos de barras, vencimientos y balanzas.</p>
                                <div class="flex flex-wrap gap-2 text-slate-400">
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Minimarket</span>
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Bodega</span>
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Farmacia</span>
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Ferretería</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card: SERVICIOS (Muy pronto) -->
                        <div class="relative border-2 border-slate-100 rounded-3xl p-8 bg-slate-50 opacity-50 grayscale flex items-start gap-6">
                            <div class="text-6xl flex-shrink-0">💇</div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-2xl font-black text-slate-700 leading-none">Servicios</h3>
                                    <span class="bg-slate-400 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-full italic">Muy pronto</span>
                                </div>
                                <p class="text-slate-400 text-sm mb-4">Agenda de citas, turnos y comisiones de personal.</p>
                                <div class="flex flex-wrap gap-2 text-slate-400">
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Peluquería</span>
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Spa</span>
                                    <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Lavandería</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Credentials -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-600">Nombre de tu Negocio</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <input type="text" x-model="nombreNegocio" class="block w-full h-14 bg-white border border-slate-200 rounded-2xl pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-600 transition-all text-lg shadow-sm" placeholder="Ej: TuNegocio" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-600">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <input type="email" x-model="email" class="block w-full h-14 bg-white border border-slate-200 rounded-2xl pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-600 transition-all text-lg shadow-sm" placeholder="tucorreo@ejemplo.com" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-600">Contraseña Maestra</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input type="password" x-model="password" class="block w-full h-14 bg-white border border-slate-200 rounded-2xl pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-600 transition-all text-lg shadow-sm" placeholder="Mínimo 8 caracteres" />
                        </div>
                    </div>
                </div>

                <!-- Step 3: Plan -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Plan Option: Free -->
                        <label class="relative flex items-center p-6 border-2 rounded-3xl cursor-pointer transition-all bg-white"
                               :class="selectedPlan === 'free' ? 'border-red-600 ring-2 ring-red-100' : 'border-slate-100 hover:border-slate-200'">
                            <input type="radio" x-model="selectedPlan" value="free" class="sr-only" />
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-slate-900">Plan Empoderado (Gratis)</h4>
                                <p class="text-slate-500 text-sm">Gestiona tu stock y ventas sin costo para siempre.</p>
                            </div>
                            <div class="text-2xl font-black text-red-600">S/ 0</div>
                        </label>
                        
                        <!-- Plan Option: Pro Trial -->
                        <label class="relative flex items-center p-6 border-2 rounded-3xl cursor-pointer transition-all bg-white"
                               :class="selectedPlan === 'pro_trial' ? 'border-red-600 ring-2 ring-red-100' : 'border-slate-100 hover:border-slate-200'">
                            <input type="radio" x-model="selectedPlan" value="pro_trial" class="sr-only" />
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xl font-bold text-slate-900">Prueba Pro (Trial)</h4>
                                    <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase px-2 py-0.5 rounded-full">Recomendado</span>
                                </div>
                                <p class="text-slate-500 text-sm">Facturación electrónica y multi-local por 14 días.</p>
                            </div>
                            <div class="text-2xl font-black text-slate-900">S/ 120<span class="text-xs text-slate-400 font-normal"> /mes</span></div>
                        </label>

                        <!-- Plan Option: Full Power (Testing) -->
                        <label class="relative flex items-center p-6 border-2 border-dashed rounded-3xl cursor-pointer transition-all"
                               :class="selectedPlan === 'full_power' ? 'border-purple-600 bg-purple-50/30' : 'border-slate-200 hover:border-purple-300'">
                            <input type="radio" x-model="selectedPlan" value="full_power" class="sr-only" />
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xl font-bold text-slate-900">Full Power</h4>
                                    <span class="bg-purple-600 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-full animate-pulse">Developer Mode</span>
                                </div>
                                <p class="text-slate-500 text-sm">Todo ilimitado sin restricciones. Solo para pruebas.</p>
                            </div>
                            <div class="text-2xl font-black text-purple-600">GRATIS</div>
                        </label>
                    </div>
                    <div class="bg-red-50 p-4 rounded-2xl flex gap-3 items-start border border-red-100">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-xs text-red-700 leading-relaxed font-medium">Podrás cambiar de plan en cualquier momento desde tu panel de configuración. No se requiere pago ahora.</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-10 flex gap-4">
                    <button type="button" x-show="step > 1" @click="prevStep()" 
                            class="h-16 px-8 border-2 border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        Atrás
                    </button>
                    
                    <button type="button" x-show="step < totalSteps" @click="nextStep()"
                            class="flex-1 h-16 bg-slate-900 text-white font-black text-xl rounded-2xl hover:bg-slate-800 transition-all flex items-center justify-center space-x-3">
                        <span>Siguiente</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>

                    <button type="button" x-show="step === totalSteps" @click="submitRegister()" :disabled="submitting"
                            class="flex-1 h-16 bg-red-600 text-white font-black text-xl rounded-2xl hover:bg-red-700 transition-all shadow-2xl shadow-red-200 flex items-center justify-center space-x-3 disabled:opacity-50">
                        <template x-if="!submitting">
                            <div class="flex items-center gap-3">
                                <span>Empezar mi Aventura</span>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                        </template>
                        <template x-if="submitting">
                            <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                    </button>
                </div>

                <div class="text-center mt-10" x-show="step === 1">
                    <p class="text-slate-500 font-medium">¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-black underline underline-offset-4 decoration-2">Inicia sesión</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Toastify Helper (Access via global if needed or keep here) ---
        const showToast = (text, type = 'error') => {
            Toastify({
                text: text,
                duration: 4000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: type === 'success' ? '#10b981' : '#ef4444',
                    borderRadius: '16px',
                    fontWeight: 'bold',
                },
            }).showToast();
        };

        // Added register function to Alpine scope via inter-op or direct Alpine script
        document.addEventListener('alpine:init', () => {
            Alpine.data('registerWizard', () => ({
                // Inherit or redfine if needed - simpler to keep logic in x-data
            }));
        });

        // Mapping Alpine logic to a main submit for backend integration
        async function submitRegister() {
            const scope = Alpine.$data(document.querySelector('[x-data]'));
            scope.submitting = true;

            const payload = {
                nombre_negocio: scope.nombreNegocio,
                email: scope.email,
                password: scope.password,
                rubro: scope.selectedRubro,
                plan: scope.selectedPlan
            };

            try {
                const res = await fetch("{{ env('API_BASE_URL', 'http://localhost:8000') }}/api/register", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    if (res.status === 422 && data.errors) {
                        const errorMsg = Object.values(data.errors).flat().join(' ');
                        throw new Error(errorMsg);
                    }
                    throw new Error(data?.message || 'Error en el registro');
                }

                showToast('¡Negocio creado con éxito!', 'success');
                setTimeout(() => {
                    const appUrl = '{{ env('APP_URL_APP', 'http://localhost:5174') }}';
                    window.location.href = `${appUrl}/dashboard?token=${data.token}&tenantId=${data.tenant_id}`;
                }, 1500);

            } catch (err) {
                showToast(err.message || 'Error inesperado');
                scope.submitting = false;
            }
        }

        // Expose to window for Alpine's @click
        window.submitRegister = submitRegister;
    </script>
@endsection
