import axios from 'axios'

// Axios instance configured for SmartKet API
export const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  timeout: 15000,
  withCredentials: true, // Senior Security: Permite enviar cookies HttpOnly
})

// Attach Tenant ID header on every request
api.interceptors.request.use(
  (config) => {
    // El token ya no se envía manualmente en el header Authorization, 
    // se envía automáticamente vía cookie HttpOnly gracias a withCredentials: true
    const tenantId = localStorage.getItem('smartket_tenant_id')

    config.headers = config.headers || {}

    // Agregamos el header X-Tenant-ID que nuestra API requiere
    if (tenantId) {
      config.headers['X-Tenant-ID'] = tenantId
    }

    return config
  },
  (error) => Promise.reject(error)
)

// Interceptor de respuesta para manejar errores 401 de forma centralizada.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error?.response?.status
    if (status === 401) {
      // Si la API nos rechaza, es señal de que el token es inválido o ha expirado.
      // Limpiamos el localStorage para evitar bucles. El router se encargará
      // de la redirección final.
      localStorage.removeItem('smartket_token')
      localStorage.removeItem('smartket_tenant_id')
    }
    return Promise.reject(error)
  }
)// Vue plugin to expose the axios instance as $api
export default {
  install(app) {
    app.config.globalProperties.$api = api
  },
}

// Auditoría: función auxiliar para registrar eventos
export async function audit(eventType, message = '', route = '', meta = {}) {
  try {
    await api.post('/audit', { event_type: eventType, message, route, meta })
  } catch (err) {
    // Silenciamos errores de auditoría para no interrumpir UX
    // console.warn('Audit error', err)
  }
}
