@extends('layouts.app')

@section('title', 'Login - SmartKet')

@section('content')
    <div class="min-h-screen flex bg-white font-sans">
        <!-- Brand Panel (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-red-700 via-red-800 to-red-900 relative overflow-hidden">
            <!-- Decorative circles -->
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
                        Gestiona tu negocio de manera <span class="text-yellow-400">inteligente</span>
                    </h1>
                    <p class="text-xl text-red-50/80 leading-relaxed mb-12">
                        Control total de inventario, ventas y clientes en una sola plataforma diseñada para personas reales.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-center space-x-4 group">
                            <div class="w-10 h-10 bg-yellow-400/20 rounded-xl flex items-center justify-center text-yellow-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-lg font-medium text-red-50">Control de inventario en tiempo real</span>
                        </div>
                        <div class="flex items-center space-x-4 group">
                            <div class="w-10 h-10 bg-yellow-400/20 rounded-xl flex items-center justify-center text-yellow-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            </div>
                            <span class="text-lg font-medium text-red-50">Reportes detallados y analytics</span>
                        </div>
                        <div class="flex items-center space-x-4 group">
                            <div class="w-10 h-10 bg-yellow-400/20 rounded-xl flex items-center justify-center text-yellow-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                            </div>
                            <span class="text-lg font-medium text-red-50">Soporte prioritario 24/7</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-20 text-red-200/50 text-sm font-medium">
                    &copy; {{ date('Y') }} SmartKet Global. Todos los derechos reservados.
                </div>
            </div>
        </div>

        <!-- Form Panel -->
        <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 lg:px-24 bg-slate-50 relative">
            <div class="max-w-md w-full mx-auto">
                <!-- Mobile Logo Only -->
                <div class="lg:hidden text-center mb-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                        <img src="/img/SmartKet.svg" alt="SmartKet" class="h-10 w-auto" />
                        <span class="text-xl font-black tracking-tighter">SMART<span class="text-red-700">ket</span></span>
                    </a>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-4xl font-black text-slate-900 mb-3 tracking-tight">Bienvenido de vuelta</h2>
                    <p class="text-slate-500 text-lg">Inicia sesión en tu cuenta para continuar gestionando tu negocio.</p>
                </div>

                <form id="loginForm" class="space-y-6" novalidate>
                    <div class="space-y-2">
                        <label for="login_id" class="text-sm font-bold text-slate-700 uppercase tracking-wider">Email o Usuario</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <input type="text" id="login_id" name="login_id" required autocomplete="username"
                                   class="block w-full h-14 bg-white border border-slate-200 rounded-2xl pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-600 transition-all text-lg shadow-sm" 
                                   placeholder="tucorreo@ejemplo.com" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-sm font-bold text-slate-700 uppercase tracking-wider">Contraseña</label>
                            <a href="#" class="text-sm font-bold text-red-600 hover:text-red-700">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                   class="block w-full h-14 bg-white border border-slate-200 rounded-2xl pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-600 transition-all text-lg shadow-sm" 
                                   placeholder="••••••••" />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="w-5 h-5 rounded border-slate-300 text-red-600 focus:ring-red-500 shadow-sm" />
                        <label for="remember" class="ml-3 text-slate-600 font-medium">Recordarme</label>
                    </div>

                    <button type="submit" id="submitBtn" aria-label="Entrar al panel" 
                            class="w-full h-14 bg-red-600 hover:bg-red-700 text-white font-black text-xl rounded-2xl transition-all shadow-xl shadow-red-200 active:scale-95 flex items-center justify-center space-x-2">
                        <span>Iniciar Sesión</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>

                    <div class="text-center pt-8 border-t border-slate-200">
                        <p class="text-slate-500 font-medium">
                            ¿Aún no tienes una cuenta? <br/>
                            <a href="{{ route('registro') }}" class="text-red-600 hover:text-red-700 font-black text-lg underline underline-offset-4 decoration-2">Regístrate gratis ahora</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const loginIdEl = document.getElementById('login_id');
        const passwordEl = document.getElementById('password');

        // --- Toastify Helper ---
        const showToast = (text, type = 'error') => {
            Toastify({
                text: text,
                duration: 3500,
                close: true,
                gravity: "top",
                position: "center",
                stopOnFocus: true,
                style: {
                    background: type === 'success' ? '#10b981' : '#ef4444',
                    borderRadius: '16px',
                    fontWeight: 'bold',
                    boxShadow: '0 20px 25px -5px rgb(0 0 0 / 0.1)'
                },
            }).showToast();
        };

        // Recuperar login_id del localStorage si existe
        const savedLoginId = localStorage.getItem('smartket_login_id');
        if (savedLoginId) {
            loginIdEl.value = savedLoginId;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            submitBtn.setAttribute('aria-busy', 'true');

            const login_id = loginIdEl.value.trim();
            const password = passwordEl.value;

            if (!login_id || !password) {
                showToast('Por favor, ingresa tus credenciales.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                submitBtn.removeAttribute('aria-busy');
                return;
            }

            const payload = { login_id, password };

            try {
                const res = await fetch("{{ env('API_BASE_URL', 'http://localhost:8000') }}/api/login", {
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
                    throw new Error(data?.message || 'Credenciales inválidas');
                }

                showToast('¡Éxito! Redirigiendo...', 'success');

                // Guardar login_id para futuros logins
                localStorage.setItem('smartket_login_id', login_id);

                const appUrl = '{{ env('APP_URL_APP', 'http://localhost:5174') }}'
                
                setTimeout(() => {
                    window.location.href = `${appUrl}/dashboard?token=${data.token}&tenantId=${data.tenant_id}`;
                }, 1000);

            } catch (err) {
                showToast(err.message || 'Error inesperado');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                submitBtn.removeAttribute('aria-busy');
            }
        });
    </script>
@endsection
