<template>
  <div :class="themeClass" class="flex h-screen font-sans bg-gray-50">
    <!-- Mobile sidebar overlay -->
    <div v-if="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
      <div class="fixed inset-0 flex">
        <div class="relative flex w-full max-w-xs flex-1 flex-col bg-gradient-to-b from-red-600 to-red-800">
          <div class="absolute right-0 top-0 -mr-12 pt-2">
            <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
              <span class="sr-only">Close sidebar</span>
              <XMarkIcon class="h-6 w-6 text-white" />
            </button>
          </div>
          <div class="flex flex-shrink-0 items-center px-6 py-5">
            <img class="h-8 w-auto filter brightness-0 invert" src="/img/SmartKet.svg" alt="SmartKet" />
            <span class="ml-3 text-xl font-bold text-white uppercase tracking-wider">SmartKet</span>
          </div>
          <nav class="flex-1 space-y-1 px-4 pb-4 overflow-y-auto">
             <template v-for="item in navigation" :key="item.name">
                <router-link 
                  v-if="!item.expandable"
                  :to="item.href" 
                  class="group flex items-center px-3 py-2 text-sm font-semibold rounded-md transition-all duration-200"
                  :class="[isCurrent(item.href) ? 'bg-red-700 text-white shadow-lg' : 'text-red-100 hover:bg-red-700 hover:text-white']"
                >
                  <span class="mr-3 text-lg">{{ item.emoji }}</span>
                  {{ item.name }}
                </router-link>
                <div v-else>
                   <button 
                    @click="toggleExpand(item.name)"
                    class="w-full flex items-center justify-between px-3 py-2 text-sm font-semibold rounded-md text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200"
                  >
                    <div class="flex items-center">
                      <span class="mr-3 text-lg">{{ item.emoji }}</span>
                      {{ item.name }}
                    </div>
                    <ChevronDownIcon :class="[expanded[item.name] ? 'rotate-180' : '', 'h-4 w-4 transition-transform duration-200']" />
                  </button>
                  <div v-if="expanded[item.name]" class="mt-1 ml-8 space-y-1">
                    <router-link 
                      v-for="sub in item.subModules" 
                      :key="sub.name"
                      :to="sub.href"
                      class="block px-3 py-2 text-xs font-medium rounded-md text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200"
                      :class="[isCurrent(sub.href) ? 'bg-red-700 text-white shadow-md' : '']"
                    >
                      {{ sub.name }}
                    </router-link>
                  </div>
                </div>
             </template>
          </nav>
        </div>
      </div>
    </div>

    <!-- Static sidebar for desktop -->
    <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0 z-50">
      <div class="flex flex-col flex-grow bg-gradient-to-b from-red-600 to-red-800 overflow-y-auto">
        <div class="flex items-center flex-shrink-0 px-6 py-8">
          <img class="h-10 w-auto filter brightness-0 invert" src="/img/SmartKet.svg" alt="SmartKet" />
          <div class="ml-4">
            <h1 class="text-white font-black text-xl leading-none">SMART<span class="text-yellow-400">KET</span></h1>
            <p class="text-red-200 text-[10px] uppercase tracking-[0.2em] font-bold">Business Suite</p>
          </div>
        </div>
        <nav class="flex-1 px-4 space-y-2 mt-4 pb-8">
          <template v-for="item in navigation" :key="item.name">
            <div v-if="item.expandable">
              <button 
                @click="toggleExpand(item.name)"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 border border-transparent"
                :class="[expanded[item.name] ? 'bg-red-900/40 border-red-500/30' : 'text-red-100 hover:bg-red-700/50 hover:text-white']"
              >
                <div class="flex items-center">
                  <span class="mr-3 text-xl bg-red-500/20 w-8 h-8 flex items-center justify-center rounded-lg">{{ item.emoji }}</span>
                  {{ item.name }}
                </div>
                <ChevronDownIcon :class="[expanded[item.name] ? 'rotate-180' : '', 'h-4 w-4 transition-transform duration-200']" />
              </button>
              <div v-if="expanded[item.name]" class="mt-2 ml-6 space-y-1 border-l-2 border-red-500/20">
                <router-link 
                  v-for="sub in item.subModules" 
                  :key="sub.name"
                  :to="sub.href"
                  class="block ml-4 px-4 py-2 text-xs font-semibold rounded-lg text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200"
                  :class="[isCurrent(sub.href) ? 'bg-red-700 text-white shadow-md' : '']"
                >
                  {{ sub.name }}
                </router-link>
              </div>
            </div>
            <router-link 
              v-else
              :to="item.href" 
              class="group flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 border border-transparent"
              :class="[isCurrent(item.href) ? 'bg-white text-red-700 shadow-xl scale-105' : 'text-red-100 hover:bg-red-700/50 hover:text-white']"
            >
              <span class="mr-3 text-xl w-8 h-8 flex items-center justify-center rounded-lg" :class="[isCurrent(item.href) ? 'bg-red-100' : 'bg-red-500/20']">{{ item.emoji }}</span>
              {{ item.name }}
              <div v-if="item.badge" class="ml-auto bg-yellow-400 text-red-900 text-[10px] px-2 py-0.5 rounded-full font-black">{{ item.badge }}</div>
            </router-link>
          </template>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-red-500/20">
          <button @click="logout" class="flex w-full items-center px-4 py-3 text-sm font-bold text-red-200 hover:bg-red-700 hover:text-white rounded-xl transition-all">
            <span class="mr-3 text-xl">🚪</span>
            Cerrar Sesión
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-72 flex flex-col flex-1 w-full">
      <!-- Navbar Superior Improved -->
      <header class="sticky top-0 z-40 flex h-20 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
          <span class="sr-only">Open sidebar</span>
          <Bars3Icon class="h-6 w-6" />
        </button>

        <!-- Dynamic Context Info -->
        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center">
          <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
              {{ currentTenant?.nombre_negocio?.charAt(0) || 'S' }}
            </div>
            <div>
              <h2 class="text-sm font-bold text-gray-900 leading-none">{{ currentTenant?.nombre_negocio || 'SmartKet' }}</h2>
              <p class="text-[10px] text-gray-500 uppercase mt-1">Sucursal: {{ currentSucursal?.nombre || 'Principal' }}</p>
            </div>
          </div>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-x-4 lg:gap-x-6">
          <!-- Multi-business Switcher (UI from V5) -->
          <div v-if="hasMultipleTenants" class="relative">
            <button @click="switchingBusiness = !switchingBusiness" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-all border border-gray-200">
               <span class="text-lg">🏢</span>
               <span class="hidden sm:inline text-xs font-bold text-gray-700">Cambiar Negocio</span>
               <ChevronDownIcon class="h-3 w-3 text-gray-400" />
            </button>
            
            <div v-if="switchingBusiness" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 py-3 z-50">
                <div class="px-4 py-2 border-b border-gray-50 mb-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Mis Negocios</p>
                </div>
                <button 
                  v-for="t in accessibleTenants" 
                  :key="t.id"
                  @click="switchTenant(t)"
                  class="w-full flex items-center px-4 py-3 hover:bg-red-50 transition-all text-left group"
                  :class="[t.id === currentTenant?.id ? 'bg-red-50 border-r-4 border-red-600' : '']"
                >
                  <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 font-bold text-gray-500 group-hover:bg-red-600 group-hover:text-white transition-all">
                    {{ t.nombre_negocio.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-bold text-gray-800 leading-tight">{{ t.nombre_negocio }}</p>
                    <p class="text-[10px] text-gray-400 uppercase">{{ t.rubro || 'General' }}</p>
                  </div>
                  <CheckIcon v-if="t.id === currentTenant?.id" class="ml-auto h-4 w-4 text-red-600" />
                </button>
            </div>
          </div>

          <div class="h-8 w-px bg-gray-200"></div>

          <!-- User Menu -->
          <div class="flex items-center space-x-3">
             <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-gray-900 leading-none">{{ user?.name }}</p>
                <p class="text-[10px] text-gray-500 uppercase mt-1">{{ user?.roles?.[0] || 'Staff' }}</p>
             </div>
             <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-md cursor-pointer hover:border-red-600 transition-all">
                <span class="font-bold text-gray-600">{{ user?.name?.charAt(0) }}</span>
             </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50">
        <div class="container mx-auto p-4 md:p-8">
            <SetupPrompt :visible="showSetupPrompt" @go-setup="goSetup" @dismiss="dismissSetup" />
            <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
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
import { useRouter, useRoute } from 'vue-router'
import { authStore } from '../store/auth'
import { api, audit } from '@/api'
import SetupPrompt from '@/components/SetupPrompt.vue'
import { 
  Bars3Icon, 
  XMarkIcon, 
  ChevronDownIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const route = useRoute()
const sidebarOpen = ref(false)
const switchingBusiness = ref(false)
const expanded = ref({})

// Get data from Store
const user = computed(() => authStore.state.user)
const currentTenant = computed(() => authStore.state.tenant)
const accessibleTenants = computed(() => authStore.state.accessible_tenants || [])
const hasMultipleTenants = computed(() => accessibleTenants.value.length > 1)
const themeClass = computed(() => `theme-${currentTenant.value?.rubro || 'default'}`)

// Navigation Definition (Inspired by V5)
const navigation = computed(() => {
  const all = [
    { name: 'Dashboard', href: '/app/dashboard', emoji: '🏠' },
    { 
        name: 'Core', 
        emoji: '⚙️',
        expandable: true,
        subModules: [
            { name: 'Ajustes de Empresa', href: '/app/settings/company' },
            { name: 'Gestión de Sucursales', href: '/app/settings/branches' },
            { name: 'Usuarios y Roles', href: '/app/settings/users' }
        ]
    },
    { name: 'Productos', href: '/app/productos', emoji: '📦', badge: 'Admin' },
    { name: 'Ventas', href: '/app/ventas', emoji: '🛒' },
    { name: 'Inventario', href: '/app/inventario', emoji: '📉' }
  ]
  
  // Specific modules by business type
  if (currentTenant.value?.rubro === 'polleria') {
    all.push({ 
        name: 'Pollería', 
        emoji: '🍗',
        expandable: true,
        subModules: [
            { name: 'Mesas / Mesero', href: '/app/polleria/mesero' },
            { name: 'Cocina', href: '/app/polleria/cocina' },
            { name: 'Caja', href: '/app/polleria/caja' },
            { name: 'Delivery', href: '/app/polleria/delivery' }
        ]
    })
  }

  return all
})

const isCurrent = (path) => route.path.startsWith(path)

const toggleExpand = (name) => {
  expanded.value[name] = !expanded.value[name]
}

const showSetupPrompt = ref(false)

onMounted(async () => {
    // Refresh user and tenants data
    await authStore.fetchUser()
    
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
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
</style>