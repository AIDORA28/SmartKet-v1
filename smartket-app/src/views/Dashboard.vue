<template>
  <div>
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
          Hola, <span class="text-indigo-600 dark:text-indigo-400">{{ user?.name?.split(' ')[0] }}</span> 👋
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">
          Aquí tienes el pulso de tu negocio <span class="font-medium text-gray-700 dark:text-gray-300">{{ currentBranch?.name }}</span> hoy.
        </p>
      </div>
      <div class="flex items-center gap-3 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <span class="relative flex h-3 w-3 ml-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 pr-2">Sistema Operativo</span>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Card 1 -->
      <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
          <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
              <span class="material-icons text-xl">payments</span>
            </div>
            <span class="text-xs font-semibold bg-green-400/20 text-green-100 px-2 py-1 rounded-full">+12% vs ayer</span>
          </div>
          <p class="text-indigo-100 text-sm font-medium mb-1">Ventas Totales</p>
          <h3 class="text-3xl font-bold">S/ 1,240.50</h3>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
          <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
            <span class="material-icons text-orange-500">shopping_cart</span> // Corrección: shopping_bag -> shopping_cart para compatibilidad
          </div>
          <span class="text-xs font-semibold text-gray-400">Hoy</span> // Sin cambio vs ayer simulado
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Pedidos</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">45</h3>
      </div>

      <!-- Card 3 -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
          <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
             <span class="material-icons text-blue-500">people</span>
          </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Clientes Nuevos</p>
        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">12</h3>
      </div>

      <!-- Card 4 (Action) -->
      <button 
        @click="$router.push({ name: 'ventas' })"
        class="bg-gray-900 dark:bg-gray-700 rounded-2xl p-6 text-white shadow-lg hover:bg-gray-800 dark:hover:bg-gray-600 transition-all flex flex-col items-center justify-center text-center gap-3 group border border-transparent hover:border-gray-500"
      >
        <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
           <span class="material-icons">add</span>
        </div>
        <div>
          <h3 class="font-bold text-lg">Nueva Venta</h3>
          <p class="text-xs text-gray-400">Ir al Punto de Venta</p>
        </div>
      </button>
    </div>

    <!-- Modules Grid (Command Center) -->
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-gray-400">grid_view</span>
      Centro de Mando
    </h2>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
      <div 
        v-for="mod in modules" 
        :key="mod.name"
        @click="goToModule(mod)"
        class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 cursor-pointer transition-all hover:shadow-lg relative overflow-hidden"
      >
        <!-- In Process Badge -->
        <div v-if="!mod.active" class="absolute top-0 right-0 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg z-10">
          WIP
        </div>

        <div class="flex flex-col items-center text-center pt-2">
          <span class="text-3xl mb-3 transform group-hover:scale-110 transition-transform duration-300">{{ mod.emoji }}</span>
          <h3 class="font-bold text-gray-800 dark:text-white text-sm leading-tight">{{ mod.name }}</h3>
          <p class="text-[10px] text-gray-400 mt-1">{{ mod.desc }}</p>
        </div>
      </div>
    </div>

    <!-- Recent Activity & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Recent Activity Feed -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4">Actividad Reciente</h3>
        <div class="space-y-6">
           <div v-for="i in 3" :key="i" class="flex gap-4">
             <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center border-4 border-white dark:border-gray-800">
                  <span class="material-icons text-sm text-gray-500">receipt</span>
                </div>
                <div v-if="i !== 3" class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-full bg-gray-100 dark:bg-gray-700 -z-10"></div>
             </div>
             <div>
               <p class="text-sm font-medium text-gray-800 dark:text-white">Venta #{{ 10230 + i }} completada</p>
               <p class="text-xs text-gray-500">Hace {{ i * 15 }} minutos • <span class="text-indigo-500 font-medium">S/ {{ (35.50 * i).toFixed(2) }}</span></p>
             </div>
           </div>
        </div>
      </div>

      <!-- System Health / Status -->
      <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
         <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600 rounded-full blur-3xl opacity-20"></div>
         
         <h3 class="font-bold mb-4 flex items-center gap-2">
           <span class="material-icons text-indigo-400">dns</span>
           Estado del Sistema
         </h3>

         <div class="space-y-4 relative z-10">
           <div class="flex justify-between items-center text-sm">
             <span class="text-gray-400">Base de Datos</span>
             <span class="text-green-400 font-medium flex items-center gap-1">
               <span class="w-2 h-2 rounded-full bg-green-400"></span> Conectado
             </span>
           </div>
           
           <div class="flex justify-between items-center text-sm">
             <span class="text-gray-400">Sucursal</span>
             <span class="text-white font-medium">{{ currentBranch?.name }}</span>
           </div>

           <div class="flex justify-between items-center text-sm">
             <span class="text-gray-400">Módulos Activos</span>
             <span class="text-white font-medium">3/9</span>
           </div>

           <div class="pt-4 mt-2 border-t border-gray-700">
             <p class="text-xs text-gray-500 text-center">SmartKet v1.0.0 Enterprise</p>
           </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '../store/auth'

const router = useRouter()
const user = computed(() => authStore.state.user)
const currentBranch = computed(() => authStore.state.current_branch)

const modules = [
  { name: 'Ventas', emoji: '🛒', route: 'ventas', desc: 'Facturación', active: false },
  { name: 'Inventario', emoji: '📉', route: 'inventario', desc: 'Control de Stock', active: false },
  { name: 'Clientes', emoji: '👥', route: 'clientes', desc: 'CRM Básico', active: false },
  { name: 'Proveedores', emoji: '🚚', route: 'proveedores', desc: 'Gestión de Compras', active: false },
  { name: 'Personal', emoji: '👔', route: 'admin-staff', desc: 'RRHH', active: true },
  { name: 'Roles', emoji: '🛡️', route: 'admin-roles', desc: 'Seguridad', active: true },
  { name: 'Reportes', emoji: '📊', route: 'reportes', desc: 'Analytics', active: false },
  { name: 'Finanzas', emoji: '💰', route: 'finanzas', desc: 'Caja Chica', active: false },
]

const goToModule = (mod) => {
    try {
        router.push({ name: mod.route })
    } catch (e) {
        // Fallback layout route?
        router.push({ name: 'dashboard' })
    }
}
</script>
