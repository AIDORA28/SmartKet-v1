import { createRouter, createWebHistory } from 'vue-router'
import { authStore } from '../store/auth'

// Public and private views
const Dashboard = () => import('../views/Dashboard.vue')
const Productos = () => import('../views/Productos.vue')
const BusinessTypeSettings = () => import('../views/BusinessTypeSettings.vue')
const AppLayout = () => import('../layouts/AppLayout.vue')
const Onboarding = () => import('../views/Onboarding.vue')
const SetupFiscal = () => import('../views/SetupFiscal.vue')
const SelectBranch = () => import('../views/SelectBranch.vue')

// Pollería module views (lazy-loaded)
const PolleriaMesero = () => import('../views/polleria/MeseroOrders.vue')
const PolleriaCocina = () => import('../views/polleria/CocinaScreen.vue')
const PolleriaCaja = () => import('../views/polleria/CajaPOS.vue')
const PolleriaDelivery = () => import('../views/polleria/DeliveryScreen.vue')
const PolleriaAlmacen = () => import('../views/polleria/AlmacenInventory.vue')
const PolleriaAdmin = () => import('../views/polleria/AdminPanel.vue')

// Admin System Views
const RolesPage = () => import('../views/admin/RolesPage.vue')
const StaffPage = () => import('../views/admin/StaffPage.vue')


const routes = [
  {
    path: '/app',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      { path: 'dashboard', name: 'dashboard', component: Dashboard },
      { path: 'productos', name: 'productos', component: Productos },
      { path: 'settings/business-type', name: 'settings-business-type', component: BusinessTypeSettings },
      { path: 'onboarding', name: 'onboarding', component: Onboarding },
      { path: 'setup/fiscal', name: 'setup-fiscal', component: SetupFiscal },
      // Pollería module routes with role-based access
      { path: 'polleria/mesero', name: 'polleria-mesero', component: PolleriaMesero, meta: { roles: ['mesero'] } },
      { path: 'polleria/cocina', name: 'polleria-cocina', component: PolleriaCocina, meta: { roles: ['cocina'] } },
      { path: 'polleria/caja', name: 'polleria-caja', component: PolleriaCaja, meta: { roles: ['caja'] } },
      { path: 'polleria/delivery', name: 'polleria-delivery', component: PolleriaDelivery, meta: { roles: ['delivery'] } },
      { path: 'polleria/almacen', name: 'polleria-almacen', component: PolleriaAlmacen, meta: { roles: ['almacen'] } },
      { path: 'polleria/admin', name: 'polleria-admin', component: PolleriaAdmin, meta: { roles: ['admin'] } },

      // Admin System Routes
      { path: 'admin/roles', name: 'admin-roles', component: RolesPage, meta: { roles: ['admin'] } },
      { path: 'admin/staff', name: 'admin-staff', component: StaffPage, meta: { roles: ['admin'] } },
      { path: 'admin/staff', name: 'admin-staff', component: StaffPage, meta: { roles: ['admin'] } },
    ],
  },
  { path: '/select-branch', name: 'select-branch', component: SelectBranch },
  // Redirect legacy URLs to the new /app prefix
  { path: '/:pathMatch(.*)*', redirect: to => `/app/${to.params.pathMatch.join('/')}` },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})

/**
 * @description
 * Guardia de Navegación Global.
 * Resuelve el problema de la "página en blanco" al asegurar que los datos
 * del usuario existan ANTES de renderizar una ruta privada.
 */
router.beforeEach(async (to) => {
  // 1. Capturar y almacenar el token/tenant si vienen en la URL desde el landing.
  const tokenFromQuery = to.query.token
  const tenantIdFromQuery = to.query.tenantId

  if (tokenFromQuery && tenantIdFromQuery) {
    localStorage.setItem('smartket_token', tokenFromQuery)
    localStorage.setItem('smartket_tenant_id', tenantIdFromQuery)

    // Recargamos el estado del usuario inmediatamente
    await authStore.fetchUser()

    // Limpiamos la URL redirigiendo a la misma ruta pero sin queries
    return { path: to.path, query: {}, replace: true }
  }

  if (authStore.state.isLoading) {
    try {
      await authStore.fetchUser()
      // Recuperar branch si existe
      authStore.loadBranchFromStorage()
    } catch (e) {
      console.error('Error in router while fetching user:', e)
    }
  }

  const isAuthenticated = authStore.state.isAuthenticated
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  // 3. Lógica de redirección auth
  if (requiresAuth && !isAuthenticated) {
    const landing = import.meta.env.VITE_LANDING_URL || 'http://localhost:8002'
    window.location.href = `${landing}/login`
    return false
  }

  // 3.5 Lógica de Selección de Sucursal
  if (isAuthenticated && to.name !== 'select-branch' && requiresAuth) {
    const userBranches = authStore.state.user?.branches || []
    const currentBranch = authStore.state.current_branch

    // Si no ha seleccionado branch y tiene > 0 branches
    if (!currentBranch && userBranches.length > 0) {
      // Si solo tiene una, autoseleccionar
      if (userBranches.length === 1) {
        authStore.setBranch(userBranches[0])
      } else {
        // Si tiene varias, forzar selección
        return { name: 'select-branch' }
      }
    }
  }

  // 4. Lógica de roles
  const requiredRoles = to.meta.roles || []
  const userRoles = authStore.state.user?.roles || []

  if (requiresAuth && requiredRoles.length > 0) {
    const hasRole = requiredRoles.some(role => userRoles.includes(role) || userRoles.includes('admin'))
    if (!hasRole) {
      return { name: 'dashboard' }
    }
  }

  // 5. Si todo está en orden, permitir la navegación.
  return true
})

export default router
