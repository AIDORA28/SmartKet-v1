<template>
  <header class="sticky top-0 z-40 flex h-20 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white/95 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="$emit('open-sidebar')">
      <span class="sr-only">Open sidebar</span>
      <Menu :size="24" />
    </button>

    <!-- Dynamic Context Info -->
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center">
      <div class="flex items-center space-x-3">
        <div class="h-11 w-11 bg-gradient-to-br from-red-600 to-red-800 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-red-500/20">
          {{ currentTenant?.business_name?.charAt(0) || 'S' }}
        </div>
        <div>
          <h2 class="text-sm font-black text-gray-900 leading-none">{{ currentTenant?.business_name || 'SmartKet' }}</h2>
          <p class="text-[10px] text-gray-500 font-semibold uppercase mt-1 tracking-wider">Sucursal: {{ currentSucursal?.name || 'Principal' }}</p>
        </div>
      </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-x-4 lg:gap-x-6">
      <!-- Multi-business Switcher -->
      <div v-if="hasMultipleTenants" class="relative">
        <button @click="switchingBusiness = !switchingBusiness" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-xl transition-all border border-gray-200 active:scale-95">
           <Building2 :size="18" class="text-gray-600" />
           <span class="hidden sm:inline text-xs font-bold text-gray-700">Cambiar Negocio</span>
           <ChevronDown :size="14" :class="[switchingBusiness ? 'rotate-180' : '', 'text-gray-400 transition-transform duration-300']" />
        </button>
        
        <transition name="dropdown">
          <div v-if="switchingBusiness" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-50 transform origin-top-right">
              <div class="px-4 py-2 border-b border-gray-50 mb-2">
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Mis Negocios</p>
              </div>
              <button 
                v-for="t in accessibleTenants" 
                :key="t.id"
                @click="handleSwitch(t)"
                class="w-full flex items-center px-4 py-3 hover:bg-red-50 transition-all text-left group"
                :class="[t.id === currentTenant?.id ? 'bg-red-50 border-r-4 border-red-600' : '']"
              >
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 font-bold text-gray-500 group-hover:bg-red-600 group-hover:text-white transition-all transform group-hover:rotate-6">
                  {{ t.business_name.charAt(0) }}
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-800 leading-tight">{{ t.business_name }}</p>
                  <p class="text-[10px] text-gray-400 uppercase">{{ t.business_type || 'General' }}</p>
                </div>
                <Check v-if="t.id === currentTenant?.id" :size="16" class="ml-auto text-red-600" />
              </button>
          </div>
        </transition>
      </div>

      <div class="h-8 w-px bg-gray-200"></div>

      <!-- User Profile Dropdown -->
      <div class="relative">
        <button 
          @click="profileOpen = !profileOpen"
          class="flex items-center space-x-3 hover:bg-gray-50 px-3 py-2 rounded-xl transition-all group"
        >
          <div class="text-right hidden sm:block">
            <p class="text-xs font-black text-gray-900 leading-none group-hover:text-red-600 transition-colors">{{ user?.name }}</p>
            <p class="text-[10px] text-gray-500 uppercase mt-1 font-semibold tracking-wider">{{ user?.roles?.[0] || 'Staff' }}</p>
          </div>
          <div class="h-11 w-11 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-md transition-all group-hover:border-red-500 group-hover:shadow-lg">
            <span class="font-black text-gray-700 text-lg group-hover:scale-110 transition-transform">{{ user?.name?.charAt(0) }}</span>
          </div>
          <ChevronDown :size="14" :class="[profileOpen ? 'rotate-180' : '', 'text-gray-400 transition-transform duration-300 hidden sm:block']" />
        </button>

        <!-- Dropdown Menu -->
        <transition name="dropdown">
          <div v-if="profileOpen" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-50 transform origin-top-right">
            <!-- User Info -->
            <div class="px-4 py-3 border-b border-gray-100">
              <p class="text-sm font-black text-gray-900">{{ user?.name }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ user?.email }}</p>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 mt-2 uppercase tracking-wider">
                {{ user?.roles?.[0] || 'staff' }}
              </span>
            </div>

            <!-- Menu Options -->
            <div class="py-2">
              <button 
                @click="goToProfile"
                class="w-full flex items-center px-4 py-3 hover:bg-gray-50 transition-all text-left group"
              >
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center mr-3 group-hover:bg-blue-500/20 transition-all">
                  <UserCircle :size="18" class="text-blue-600" />
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-900">Mi Perfil</p>
                  <p class="text-xs text-gray-500">Ver y editar información</p>
                </div>
              </button>

              <button 
                @click="goToSettings"
                class="w-full flex items-center px-4 py-3 hover:bg-gray-50 transition-all text-left group"
              >
                <div class="w-9 h-9 rounded-xl bg-gray-500/10 flex items-center justify-center mr-3 group-hover:bg-gray-500/20 transition-all">
                  <Settings :size="18" class="text-gray-600" />
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-900">Configuración</p>
                  <p class="text-xs text- gray-500">Preferencias del sistema</p>
                </div>
              </button>

              <!-- Panel de Mi Negocio (Owner only) -->
              <button 
                v-if="user?.roles?.includes('owner')"
                @click="goToOwnerPanel"
                class="w-full flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 transition-all text-left group border-t border-gray-100 mt-2 pt-4"
              >
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mr-3 shadow-md shadow-red-500/20 group-hover:scale-110 transition-transform">
                  <Crown :size="18" class="text-white" />
                </div>
                <div>
                  <p class="text-sm font-black text-gray-900 group-hover:text-red-600 transition-colors">Panel de Mi Negocio</p>
                  <p class="text-xs text-gray-500">Gestión empresarial completa</p>
                </div>
                <ChevronRight :size="16" class="ml-auto text-red-600 opacity-0 group-hover:opacity-100 transition-opacity" />
              </button>
            </div>

            <!-- Logout -->
            <div class="border-t border-gray-100 pt-2 mt-2">
              <button 
                @click="handleLogout"
                class="w-full flex items-center px-4 py-3 hover:bg-red-50 transition-all text-left group"
              >
                <div class="w-9 h-9 rounded-xl bg-red-500/10 flex items-center justify-center mr-3 group-hover:bg-red-500/20 transition-all">
                  <LogOut :size="18" class="text-red-600" />
                </div>
                <div>
                  <p class="text-sm font-bold text-red-600">Cerrar Sesión</p>
                  <p class="text-xs text-gray-500">Salir del sistema</p>
                </div>
              </button>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Menu, 
  Building2, 
  ChevronDown, 
  Check, 
  UserCircle, 
  Settings, 
  Crown, 
  LogOut,
  ChevronRight
} from 'lucide-vue-next'

defineProps({
  user: Object,
  currentTenant: Object,
  currentSucursal: Object,
  accessibleTenants: Array,
  hasMultipleTenants: Boolean
})

const emit = defineEmits(['open-sidebar', 'switch-tenant'])

const router = useRouter()
const switchingBusiness = ref(false)
const profileOpen = ref(false)

const handleSwitch = (tenant) => {
  switchingBusiness.value = false
  emit('switch-tenant', tenant)
}

const goToProfile = () => {
  profileOpen.value = false
  router.push({ name: 'profile' }) // You'll need to create this route
}

const goToSettings = () => {
  profileOpen.value = false
  router.push({ name: 'settings-company' })
}

const goToOwnerPanel = () => {
  profileOpen.value = false
  router.push({ name: 'owner-dashboard' })
}

const handleLogout = () => {
  profileOpen.value = false
  // Logout logic (would typically call authStore.logout())
  localStorage.removeItem('smartket_token')
  localStorage.removeItem('smartket_tenant_id')
  const landing = import.meta.env.VITE_LANDING_URL || `${location.protocol}//${location.hostname}:8002`
  window.location.href = `${landing}/login`
}
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}
</style>
