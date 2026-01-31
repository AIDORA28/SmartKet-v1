import { reactive, readonly } from 'vue'
import { api } from '../api'

/**
 * @description
 * Almacén de estado reactivo y centralizado para la autenticación.
 * Sigue el Principio de Separación de Responsabilidades (SoC).
 */

// El estado reactivo. No se exporta directamente para evitar mutaciones accidentales.
const state = reactive({
  user: null,
  tenant: null,
  entitlements: null, // <-- NUEVA PROPIEDAD
  isAuthenticated: false,
  // isLoading se usa para saber si estamos en el proceso de obtener los datos del usuario por primera vez.
  isLoading: true,
})

// Las acciones que pueden modificar el estado.
const actions = {
  /**
   * Obtiene los datos del usuario y del tenant desde el endpoint /api/me.
   * Es la fuente de verdad para saber si el usuario está autenticado.
   */
  async fetchUser() {
    state.isLoading = true

    try {
      const { data } = await api.get('/me')
      state.user = data.user
      state.tenant = data.tenant
      state.accessible_tenants = data.accessible_tenants // Guardamos los negocios accesibles
      state.isAuthenticated = true
      return data
    } catch (error) {
      console.error('Error de autenticación al obtener datos del usuario:', error)
      this.clearAuthData()
      return null
    } finally {
      state.isLoading = false
    }
  },

  /**
   * Obtiene los datos de entitlements (permisos, límites) desde su endpoint dedicado.
   */
  async fetchEntitlements() {
    if (!state.isAuthenticated) return

    try {
      const { data } = await api.get('/tenant/entitlements')
      state.entitlements = data
    } catch (error) {
      console.error('Error al obtener entitlements:', error)
      state.entitlements = { seats: [], modules: [] } // Dejar un valor por defecto en caso de error
    }
  },

  /**
   * Establece la sucursal actual de trabajo.
   */
  setBranch(branch) {
    state.current_branch = branch
    localStorage.setItem('smartket_branch_id', branch.id)
    localStorage.setItem('smartket_branch_name', branch.name)
  },

  /**
   * Limpia todos los datos de autenticación del estado y del localStorage.
   */
  clearAuthData() {
    state.user = null
    state.tenant = null
    state.current_branch = null // <-- LIMPIAR
    state.entitlements = null
    state.isAuthenticated = false
    state.isLoading = false
    localStorage.removeItem('smartket_token')
    localStorage.removeItem('smartket_tenant_id')
    localStorage.removeItem('smartket_branch_id') // <-- LIMPIAR
    localStorage.removeItem('smartket_branch_name')
  },
}

/**
 * Se exporta una versión de solo lectura del estado para que los componentes
 * puedan leerlo pero no modificarlo directamente. La modificación solo
 * es posible a través de las acciones exportadas.
 */
export const authStore = {
  state: readonly(state),
  ...actions,
  // Helper para recuperar branch al recargar
  loadBranchFromStorage() {
    const id = localStorage.getItem('smartket_branch_id')
    const name = localStorage.getItem('smartket_branch_name')
    if (id && name) {
      state.current_branch = { id, name }
    }
  }
}
