@extends('layouts.app')

@section('title', 'Registro de Negocio - SmartKet')

@section('content')
    <section class="bg-linear-to-b from-red-50 to-white py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">
                <!-- Selector de tipo de negocio -->
                <div class="order-last lg:order-first">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-full flex flex-col">
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('img/SmartKet.svg') }}" alt="SmartKet" class="h-10 w-auto">
                            <div>
                                <p class="text-xs text-gray-500">Selecciona tu tipo de negocio</p>
                                <h2 class="text-lg font-semibold text-gray-900">¿Qué negocio tienes?</h2>
                            </div>
                        </div>

                        <div id="businessTypeGrid" class="grid grid-cols-3 gap-3">
                            <!-- Card: Pollería (única opción habilitada) -->
                            <label class="group cursor-pointer border rounded-xl p-4 text-center hover:shadow-md hover:border-red-500 transition-all duration-200 has-checked:bg-red-50 has-checked:border-red-600 has-checked:ring-2 has-checked:ring-red-500" title="Gestión de recetas, combos y ventas">
                                <input type="radio" name="rubro" value="polleria" class="sr-only" checked required />
                                <div class="text-3xl mb-2">🍗</div>
                                <div class="font-semibold text-gray-800">Pollería</div>
                            </label>

                            <!-- Card bloqueada: Minimarket -->
                            <div class="relative border rounded-xl p-4 text-center bg-gray-50 opacity-60 cursor-not-allowed" aria-disabled="true" title="Bloqueado temporalmente. Enfocados en Pollería.">
                                <div class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Pronto</div>
                                <div class="text-3xl mb-2">🛒</div>
                                <div class="font-semibold text-gray-600">Minimarket</div>
                            </div>

                            <!-- Card bloqueada: Restaurante -->
                            <div class="relative border rounded-xl p-4 text-center bg-gray-50 opacity-60 cursor-not-allowed" aria-disabled="true" title="Bloqueado temporalmente. Enfocados en Pollería.">
                                <div class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Pronto</div>
                                <div class="text-3xl mb-2">🍽️</div>
                                <div class="font-semibold text-gray-600">Restaurante</div>
                            </div>

                            <!-- Card bloqueada: Farmacia -->
                            <div class="relative border rounded-xl p-4 text-center bg-gray-50 opacity-60 cursor-not-allowed" aria-disabled="true" title="Bloqueado temporalmente. Enfocados en Pollería.">
                                <div class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Pronto</div>
                                <div class="text-3xl mb-2">💊</div>
                                <div class="font-semibold text-gray-600">Farmacia</div>
                            </div>
                            
                            <!-- Card bloqueada: Genérico -->
                            <div class="relative border rounded-xl p-4 text-center bg-gray-50 opacity-60 cursor-not-allowed" aria-disabled="true" title="Bloqueado temporalmente. Enfocados en Pollería.">
                                <div class="absolute top-2 right-2 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Pronto</div>
                                <div class="text-3xl mb-2">�</div>
                                <div class="font-semibold text-gray-600">Otro</div>
                            </div>
                        </div>

                        <!-- Previsualización de funcionalidades -->
                        <div id="businessPreview" class="mt-6">
                            <h3 class="text-base font-semibold text-gray-800 mb-2">Previsualización</h3>
                            <ul id="previewList" class="text-sm text-gray-700 list-disc ml-5 space-y-1">
                                <li>Selecciona un tipo para ver funcionalidades clave.</li>
                            </ul>
                        </div>

                        <p class="mt-auto pt-6 text-xs text-gray-500">Por ahora, solo Pollería está habilitada. Los demás rubros se irán activando progresivamente.</p>
                    </div>
                </div>

                <!-- Formulario de registro -->
                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-full flex flex-col">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Registrar nuevo negocio</h1>
                        <form id="registroForm" class="space-y-6" novalidate>
                            <div>
                                <label for="nombreNegocio" class="block text-base font-medium text-gray-700">Nombre del Negocio</label>
                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏪</span>
                                    <input type="text" id="nombreNegocio" name="nombreNegocio" required aria-describedby="nombreHelp" autocomplete="organization"
                                           class="block w-full h-12 text-base rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600 pl-10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600" 
                                           placeholder="Mi Minimarket" />
                                </div>
                                <p id="nombreHelp" class="mt-1 text-sm text-gray-500">El nombre se mostrará en tu panel y documentos.</p>
                            </div>
                            <div>
                                <label for="email" class="block text-base font-medium text-gray-700">Email</label>
                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                                    <input type="email" id="email" name="email" required aria-describedby="emailHelp" autocomplete="email"
                                           class="block w-full h-12 text-base rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600 pl-10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600" 
                                           placeholder="tucorreo@ejemplo.com" />
                                </div>
                                <p id="emailHelp" class="mt-1 text-sm text-gray-500">Usa un correo que revises con frecuencia.</p>
                            </div>
                            <div>
                                <label for="password" class="block text-base font-medium text-gray-700">Contraseña</label>
                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                                    <input type="password" id="password" name="password" required minlength="8" aria-describedby="passwordHelp" autocomplete="new-password"
                                           class="block w-full h-12 text-base rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600 pl-10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600" 
                                           placeholder="••••••••" />
                                </div>
                                <p id="passwordHelp" class="mt-1 text-sm text-gray-500">Mínimo 8 caracteres. Recomendado incluir números y símbolos.</p>
                            </div>
                            <button type="submit" id="submitBtn" aria-label="Registrar negocio" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl text-lg transition-colors shadow-sm hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">Registrar</button>
                            <div id="msg" class="text-sm mt-2" aria-live="polite" role="status"></div>
                        </form>
                        <p class="mt-auto pt-6 text-center text-sm text-gray-600">¿Ya tienes cuenta? 
                            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-semibold">Inicia sesión</a>
                        </p>
                    </div>
                </div>
            </div>

            <script>
                const form = document.getElementById('registroForm');
                const submitBtn = document.getElementById('submitBtn');
                const nombreEl = document.getElementById('nombreNegocio');
                const emailEl = document.getElementById('email');
                const passwordEl = document.getElementById('password');
                const rubroInputs = Array.from(document.querySelectorAll('input[name="rubro"]'));
                const previewList = document.getElementById('previewList');

                const featuresMap = {
                    polleria: ['Recetas e insumos', 'Combos y menús', 'Ventas y reportes'],
                };

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

                // Autosave: recuperar
                const saved = JSON.parse(localStorage.getItem('smartket_register') || '{}');
                if (saved.nombre_negocio) nombreEl.value = saved.nombre_negocio;
                if (saved.email) emailEl.value = saved.email;
                if (saved.rubro === 'polleria') {
                    const found = rubroInputs.find(r => r.value === 'polleria');
                    if (found) {
                        found.checked = true;
                        updatePreview('polleria');
                    }
                } else {
                    updatePreview('polleria');
                }

                // Autosave: guardar
                const persist = () => {
                    const selected = 'polleria';
                    localStorage.setItem('smartket_register', JSON.stringify({
                        nombre_negocio: nombreEl.value.trim(),
                        email: emailEl.value.trim(),
                        rubro: selected,
                    }));
                };
                [nombreEl, emailEl, passwordEl, ...rubroInputs].forEach(el => el.addEventListener('input', persist));

                // Previsualización dinámica
                function updatePreview(rubro) {
                    const items = featuresMap[rubro] || [];
                    previewList.innerHTML = items.length
                        ? items.map(f => `<li>${f}</li>`).join('')
                        : '<li>Selecciona un tipo para ver funcionalidades clave.</li>';
                }
                rubroInputs.forEach(r => r.addEventListener('change', () => updatePreview('polleria')));

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Registrando...';
                    submitBtn.setAttribute('aria-busy', 'true');

                    // Validaciones cliente
                    const nombre = nombreEl.value.trim();
                    const email = emailEl.value.trim();
                    const password = passwordEl.value;
                    const passOk = /^(?=.*[A-Z])(?=.*\d).{8,}$/.test(password);
                    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

                    if (nombre.length < 3) {
                        showToast('El nombre del negocio debe tener al menos 3 caracteres.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Registrar';
                        submitBtn.removeAttribute('aria-busy');
                        return;
                    }
                    if (!emailOk) {
                        showToast('Por favor, ingresa un correo electrónico válido.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Registrar';
                        submitBtn.removeAttribute('aria-busy');
                        return;
                    }
                    if (!passOk) {
                        showToast('La contraseña debe tener 8+ caracteres, 1 mayúscula y 1 número.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Registrar';
                        submitBtn.removeAttribute('aria-busy');
                        return;
                    }

                    const payload = {
                        nombre_negocio: nombre,
                        email,
                        password,
                        rubro: 'polleria',
                    };

                    try {
                        const res = await fetch("{{ env('API_BASE_URL') }}/api/register", {
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
                            throw new Error(data?.message || 'Error al registrar el negocio');
                        }

                        const token = data?.token;
                        const tenantId = data?.tenant_id;

                        if (!token || !tenantId) {
                            throw new Error('Registro incompleto: la respuesta no contiene el token o el ID del negocio.');
                        }

                        showToast('¡Registro Exitoso! Redirigiendo a tu panel...', 'success');

                        // Limpiar datos de registro del localStorage
                        localStorage.removeItem('smartket_register');
                        
                        // Guardar credenciales para la app
                        localStorage.setItem('smartket_token', token);
                        localStorage.setItem('smartket_tenant_id', tenantId);

                        const appUrl = '{{ env('APP_URL_APP', 'http://localhost:5174') }}';
                        const qs = new URLSearchParams({ token, tenantId }).toString();
                        
                        setTimeout(() => {
                            window.location.href = `${appUrl}/dashboard?${qs}`;
                        }, 1500);

                    } catch (err) {
                        showToast(err.message || 'Error inesperado. Por favor, intenta de nuevo.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Registrar';
                        submitBtn.removeAttribute('aria-busy');
                    }
                });
            </script>
        </div>
    </section>
@endsection
