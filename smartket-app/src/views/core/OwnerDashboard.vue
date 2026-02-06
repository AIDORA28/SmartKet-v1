<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30 backdrop-blur-xl bg-white/90">
      <div class="px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center gap-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/20">
                <Crown :size="24" class="text-white" />
              </div>
              <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Panel de Control Empresarial</h1>
                <p class="text-sm text-gray-500 font-medium">Gestión completa de tu negocio</p>
              </div>
            </div>
          </div>
          
          <!-- Quick Actions -->
          <div class="flex items-center gap-3">
            <button class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:border-gray-300 text-gray-700 font-semibold text-sm transition-all hover:shadow-md flex items-center gap-2">
              <Download :size="16" />
              <span class="hidden md:inline">Exportar Datos</span>
            </button>
            <button class="px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-red-500 text-white font-bold text-sm shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
              <Plus :size="16" />
              <span class="hidden md:inline">Nueva Acción</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1600px] mx-auto">
      <!-- Context Selectors -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <SKCard title="Empresa Activa" class="bg-gradient-to-br from-blue-50 to-blue-100/50 border-blue-200">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
              <Building2 :size="24" class="text-white" />
            </div>
            <div class="flex-1">
              <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-1">Negocio</p>
              <select class="w-full bg-white border-0 text-gray-900 font-bold text-lg rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option>{{ currentTenant?.business_name || 'Mi Empresa' }}</option>
              </select>
              <p class="text-xs text-blue-600 font-semibold mt-1">Plan: Profesional</p>
            </div>
          </div>
        </SKCard>

        <SKCard title="Sucursal Activa" class="bg-gradient-to-br from-green-50 to-green-100/50 border-green-200">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20">
              <MapPin :size="24" class="text-white" />
            </div>
            <div class="flex-1">
              <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-1">Ubicación</p>
              <select 
                v-model="selectedBranchId"
                @change="updateBranch"
                class="w-full bg-white border-0 text-gray-900 font-bold text-lg rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 cursor-pointer"
              >
                <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                  {{ branch.name }}
                </option>
              </select>
            </div>
          </div>
        </SKCard>

        <SKCard title="Usuario Activo" class="bg-gradient-to-br from-purple-50 to-purple-100/50 border-purple-200">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/20">
              <User :size="24" class="text-white" />
            </div>
            <div class="flex-1">
              <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-1">Propietario</p>
              <h3 class="text-xl font-black text-gray-900">{{ user?.name }}</h3>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-900 mt-1">
                {{ user?.roles?.[0] || 'owner' }}
              </span>
            </div>
          </div>
        </SKCard>
      </div>

      <!-- Quick Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
          <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
              <TrendingUp :size="20" class="text-white" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
              +12.5%
            </span>
          </div>
          <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Ventas Hoy</p>
          <h3 class="text-3xl font-black text-gray-900">S/ 1,240</h3>
          <p class="text-xs text-gray-400 mt-2">vs S/ 1,102 ayer</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
          <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
              <ShoppingCart :size="20" class="text-white" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700">
              Activo
            </span>
          </div>
          <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Pedidos</p>
          <h3 class="text-3xl font-black text-gray-900">45</h3>
          <p class="text-xs text-gray-400 mt-2">8 en preparación</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
          <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform duration-300">
              <Users :size="20" class="text-white" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-orange-100 text-orange-700">
              Nuevo
            </span>
          </div>
          <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Clientes</p>
          <h3 class="text-3xl font-black text-gray-900">128</h3>
          <p class="text-xs text-gray-400 mt-2">+12 esta semana</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
          <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
              <Package :size="20" class="text-white" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-purple-100 text-purple-700">
              Stock
            </span>
          </div>
          <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Productos</p>
          <h3 class="text-3xl font-black text-gray-900">342</h3>
          <p class="text-xs text-gray-400 mt-2">12 por reorden</p>
        </div>
      </div>

      <!-- Management Modules -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
            <Settings :size="20" class="text-gray-400" />
            Módulos de Gestión
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Gestión de Sucursales -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300" @click="goTo('/app/settings/branches')">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg shadow-cyan-500/20 flex-shrink-0">
                <Building2 :size="28" class="text-white" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900 mb-1">Sucursales</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Administra tus sedes y puntos de venta</p>
                <div class="mt-3 flex items-center text-red-600 font-bold text-sm">
                  <span>Gestionar</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>

          <!-- Gestión de Usuarios -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300" @click="goTo('/app/core/staff')">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20 flex-shrink-0">
                <Users :size="28" class="text-white" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900 mb-1">Usuarios</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Gestiona staff y accesos al sistema</p>
                <div class="mt-3 flex items-center text-red-600 font-bold text-sm">
                  <span>Gestionar</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>

          <!-- Roles y Permisos -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300" @click="goTo('/app/core/roles')">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/20 flex-shrink-0">
                <Shield :size="28" class="text-white" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900 mb-1">Roles</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Define permisos y seguridad granular</p>
                <div class="mt-3 flex items-center text-red-600 font-bold text-sm">
                  <span>Gestionar</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>

          <!-- Configuración -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300" @click="goTo('/app/settings/company')">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 flex-shrink-0">
                <Settings :size="28" class="text-white" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900 mb-1">Configuración</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Ajustes generales de la empresa</p>
                <div class="mt-3 flex items-center text-red-600 font-bold text-sm">
                  <span>Gestionar</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>

          <!-- Métodos de Pago -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300" @click="goTo('/app/settings/payment-methods')">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 flex-shrink-0">
                <CreditCard :size="28" class="text-white" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900 mb-1">Pagos</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Configura métodos de cobro</p>
                <div class="mt-3 flex items-center text-red-600 font-bold text-sm">
                  <span>Gestionar</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>

          <!-- Administrar Plan -->
          <SKCard class="cursor-pointer hover:shadow-2xl hover:scale-105 transition-all duration-300 bg-gradient-to-br from-gray-900 to-gray-800 border-gray-700">
            <div class="flex items-start gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-500/30 flex-shrink-0">
                <Crown :size="28" class="text-gray-900" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-black text-white mb-1">Plan PRO</h3>
                <p class="text-sm text-gray-300 leading-relaxed">Upgrade y facturación</p>
                <div class="mt-3 flex items-center text-yellow-400 font-bold text-sm">
                  <span>Ver Detalles</span>
                  <ChevronRight :size="16" class="ml-1" />
                </div>
              </div>
            </div>
          </SKCard>
        </div>
      </div>

      <!-- System Status -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <SKCard title="Estado del Sistema" class="bg-gradient-to-br from-gray-900 to-gray-800 border-gray-700">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse" />
                <span class="text-gray-300 font-semibold">Base de Datos</span>
              </div>
              <span class="text-green-400 font-bold text-sm">Conectado</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse" />
                <span class="text-gray-300 font-semibold">API</span>
              </div>
              <span class="text-green-400 font-bold text-sm">Operativo</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse" />
                <span class="text-gray-300 font-semibold">Módulos Activos</span>
              </div>
              <span class="text-white font-bold text-sm">3/9</span>
            </div>
            <div class="pt-4 border-t border-gray-700">
              <p class="text-xs text-gray-500 text-center">SmartKet v1.0.0 Enterprise</p>
            </div>
          </div>
        </SKCard>

        <SKCard title="Actividad Reciente" class="max-h-[400px] overflow-auto">
          <div class="space-y-4">
            <div v-for="i in 5" :key="i" class="flex gap-4 items-start">
              <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                <Activity :size="18" class="text-gray-600" />
              </div>
              <div class="flex-1">
                <p class="text-sm font-bold text-gray-900">Venta #{{ 10230 + i }} completada</p>
                <p class="text-xs text-gray-500 mt-0.5">
                  Hace {{ i * 15 }} minutos • 
                  <span class="text-red-600 font-semibold">S/ {{ (35.50 * i).toFixed(2) }}</span>
                </p>
              </div>
            </div>
          </div>
        </SKCard>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '@/store/auth'
import { api } from '../../api'
import SKCard from '../../components/compartido/ui/SKCard.vue'
import SKButton from '../../components/compartido/ui/SKButton.vue'
import {
  Crown,
  Building2,
  MapPin,
  User,
  TrendingUp,
  ShoppingCart,
  Users,
  Package,
  Settings,
  CreditCard,
  Shield,
  ChevronRight,
  Plus,
  Download,
  Activity
} from 'lucide-vue-next'

const router = useRouter()
const user = computed(() => authStore.state.user)
const currentTenant = computed(() => authStore.state.tenant)
const branches = ref([])
const selectedBranchId = ref(null)

const loadBranches = async () => {
  try {
    const res = await api.get('/branches')
    branches.value = res.data
    selectedBranchId.value = authStore.state.current_branch?.id || branches.value[0]?.id
  } catch (e) {
    console.error("Error loading branches", e)
  }
}

const updateBranch = () => {
  const branch = branches.value.find(b => b.id === selectedBranchId.value)
  if (branch) {
    authStore.setBranch(branch)
    window.location.reload()
  }
}

const goTo = (path) => {
  router.push(path)
}

onMounted(() => {
  loadBranches()
})
</script>

<style scoped>
/* Inter font for professional look */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

* {
  font-family: 'Inter', sans-serif;
}
</style>
