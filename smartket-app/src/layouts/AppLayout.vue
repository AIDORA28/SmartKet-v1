<template>
  <div :class="themeClass" class="flex h-screen font-sans bg-gray-50">
    <TheSidebar 
      :is-open="sidebarOpen" 
      :navigation="navigation"
      @close="sidebarOpen = false"
      @logout="logout"
    />

    <!-- Main Content Area -->
    <div class="lg:pl-72 flex flex-col flex-1 w-full transition-all duration-300">
      <TheHeader 
        :user="user"
        :current-tenant="currentTenant"
        :current-sucursal="currentSucursal"
        :accessible-tenants="accessibleTenants"
        :has-multiple-tenants="hasMultipleTenants"
        @open-sidebar="sidebarOpen = true"
        @switch-tenant="switchTenant"
      />

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
        <div class="container mx-auto p-4 md:p-8">
            <SetupPrompt :visible="showSetupPrompt" @go-setup="goSetup" @dismiss="dismissSetup" />
            <router-view v-slot="{ Component }">
              <transition name="page" mode="out-in">
                <component :is="Component" />
              </transition>
            </router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '@/store/auth'
import { api, audit } from '@/api'
import SetupPrompt from '@/components/SetupPrompt.vue'
import TheSidebar from '@/components/compartido/layout/TheSidebar.vue'
import TheHeader from '@/components/compartido/layout/TheHeader.vue'

const router = useRouter()
const sidebarOpen = ref(false)

// Get data from Store
const user = computed(() => authStore.state.user)
const currentTenant = computed(() => authStore.state.tenant)
const accessibleTenants = computed(() => authStore.state.accessible_tenants || [])
const hasMultipleTenants = computed(() => accessibleTenants.value.length > 1)
const themeClass = computed(() => `theme-${currentTenant.value?.business_type || 'default'}`)

// Navigation Definition (Inspired by V5)
// Navigation Definition (SmartKet v5 Standard)
const navigation = computed(() => {
  return [
    { name: 'Dashboard', href: '/app/dashboard', emoji: '📊' }, //, icon: HomeIcon },
    { name: 'POS', href: '/app/ventas', emoji: '💳', badge: 'Principal' },
    { name: 'Cajas', href: '/app/finanzas', emoji: '💰' },
    { name: 'Ventas', href: '/app/ventas', emoji: '🛒' },
    { name: 'Clientes', href: '/app/clientes', emoji: '👥' },
    { name: 'Productos', href: '/app/productos', emoji: '📦' },
    { name: 'Inventario', href: '/app/inventario', emoji: '📋' },
    { name: 'Compras', href: '/app/proveedores', emoji: '🛍️' },
    { name: 'Proveedores', href: '/app/proveedores', emoji: '🚚' },
    { name: 'Lotes', href: '/app/inventario', emoji: '🗂️' },
    { name: 'Reportes', href: '/app/reportes', emoji: '📈' },
    { name: 'Analytics', href: '/app/reportes', emoji: '🔬', badge: 'Pro' }
  ]
})

const currentSucursal = computed(() => authStore.state.current_branch || { name: 'Principal' })

const showSetupPrompt = ref(false)

onMounted(async () => {
    // Refresh user and tenants data
    await authStore.fetchUser()
    // Restore branch selection if available
    authStore.loadBranchFromStorage()
    
    if (currentTenant.value && !currentTenant.value.setup_complete) {
        showSetupPrompt.value = true
    }
})

const switchTenant = (tenant) => {
  if (tenant.id === currentTenant.value?.id) return
  
  localStorage.setItem('smartket_tenant_id', tenant.id)
  window.location.reload() // Reload to clean memory and apply new tenant context
}

const logout = () => {
  authStore.clearAuthData()
  const landing = import.meta.env.VITE_LANDING_URL || `${location.protocol}//${location.hostname}:8002`
  window.location.href = `${landing}/login`
}

const goSetup = () => {
  showSetupPrompt.value = false
  router.push({ name: 'setup-fiscal' })
}

const dismissSetup = () => {
  showSetupPrompt.value = false
}
</script>

<style scoped>
/* Modern Page Transition */
.page-enter-active, .page-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.page-enter-from {
  opacity: 0;
  transform: translateY(20px) scale(0.98);
}
.page-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(1.02);
}

/* Premium Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { 
  background: rgba(0,0,0,0.1); 
  border-radius: 10px;
  border: 2px solid transparent;
  background-clip: content-box;
}
::-webkit-scrollbar-thumb:hover { background: rgba(220, 38, 38, 0.5); }
</style>