@extends('layouts.app')

@section('title', 'Login - SmartKet')

@section('content')
    <section class="bg-gray-50 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md mx-auto p-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="text-center mb-8">
                    <a href="{{ url('/') }}" class="inline-block" aria-label="Ir al landing de SmartKet">
                        <img src="/img/SmartKet.svg" alt="SmartKet" class="h-12 w-auto mx-auto" />
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 mt-4">Bienvenido de vuelta</h1>
                    <p class="text-gray-600">Accede a tu panel para gestionar tu negocio.</p>
                </div>
                
                <form id="loginForm" class="space-y-6" novalidate>
                    <div>
                        <label for="login_id" class="block text-base font-medium text-gray-700">Email o Nombre de Usuario</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                            <input type="text" id="login_id" name="login_id" required autocomplete="username"
                                   class="block w-full h-12 text-base rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600 pl-10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600" 
                                   placeholder="tucorreo@ejemplo.com o usuario-empleado" />
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-base font-medium text-gray-700">Contraseña</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                   class="block w-full h-12 text-base rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600 pl-10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600" 
                                   placeholder="••••••••" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" aria-label="Entrar al panel" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl text-lg transition-colors shadow-sm hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">Entrar</button>
                    <div id="msg" class="text-sm text-center" aria-live="polite" role="status"></div>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    ¿No tienes cuenta? 
                    <a href="{{ route('registro') }}" class="text-red-600 hover:text-red-700 font-semibold">Regístrate</a>
                </p>
            </div>
        </div>
    </section>

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
                    background: type === 'success' ? '#28a745' : '#dc3545',
                    borderRadius: '8px',
                    boxShadow: '0 3px 6px -1px rgba(0, 0, 0, 0.12), 0 10px 36px -4px rgba(77, 96, 232, 0.3)'
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
            submitBtn.textContent = 'Entrando...';
            submitBtn.setAttribute('aria-busy', 'true');

            const login_id = loginIdEl.value.trim();
            const password = passwordEl.value;

            if (!login_id || !password) {
                showToast('Por favor, ingresa tus credenciales.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Entrar';
                submitBtn.removeAttribute('aria-busy');
                return;
            }

            const payload = { login_id, password };

            try {
                const res = await fetch("{{ env('API_BASE_URL') }}/api/login", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    // El backend ahora devuelve un error de validación 422 con detalles
                    if (res.status === 422 && data.errors) {
                        const errorMsg = Object.values(data.errors).flat().join(' ');
                        throw new Error(errorMsg);
                    }
                    throw new Error(data?.message || 'Credenciales inválidas');
                }

                const token = data?.token;
                const tenantId = data?.tenant_id; 

                if (!token || !tenantId) { 
                    throw new Error('Respuesta de login incompleta');
                }

                showToast('¡Éxito! Redirigiendo a tu panel...', 'success');

                // Guardar login_id para futuros logins
                localStorage.setItem('smartket_login_id', login_id);
                localStorage.setItem('smartket_token', token);
                localStorage.setItem('smartket_tenant_id', tenantId);

                const appUrl = '{{ env('APP_URL_APP', 'http://localhost:5174') }}'
                const qs = new URLSearchParams({ token, tenantId }).toString();
                
                setTimeout(() => {
                    window.location.href = `${appUrl}/dashboard?${qs}`;
                }, 1000);

            } catch (err) {
                showToast(err.message || 'Error inesperado');
            } finally {
                if (window.location.href.indexOf('dashboard') === -1) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Entrar';
                    submitBtn.removeAttribute('aria-busy');
                }
            }
        });
    </script>
@endsection
