<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-black text-gray-900 tracking-tight">
            Hola, <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">{{ user?.name?.split(' ')[0] }}</span> 👋
          </h1>
          <p class="text-gray-600 mt-2 font-medium">
            Aquí tienes las métricas de <span class="text-gray-900 font-bold">{{ currentBranch?.name || 'tu negocio' }}</span> hoy
          </p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-3 rounded-2xl shadow-sm border border-gray-200">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
          </span>
          <span class="text-sm font-bold text-gray-700">Sistema Operativo</span>
        </div>
      </div>
    </div>

    <!-- Quick Stats Grid (8 metrics like the reference image) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Revenue -->
      <button @click="router.push({ name: 'finanzas' })" class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group text-left cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
            <TrendingUp :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs S/ 1,102</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
              +12.5%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Ingresos</p>
        <h3 class="text-3xl font-black text-gray-900">S/ 1,240</h3>
        <div class="mt-4 h-10">
          <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
            <polyline fill="none" stroke="#10b981" stroke-width="2" points="0,25 20,20 40,15 60,18 80,10 100,12" />
            <polyline fill="url(#gradient-green)" stroke="none" points="0,30 0,25 20,20 40,15 60,18 80,10 100,12 100,30" />
            <defs>
              <linearGradient id="gradient-green" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0" />
              </linearGradient>
            </defs>
          </svg>
        </div>
      </button>

      <!-- New Customers -->
      <button @click="router.push({ name: 'clientes' })" class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group text-left cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
            <Users :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs 32</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-blue-100 text-blue-700">
              +15.6%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Nuevos Clientes</p>
        <h3 class="text-3xl font-black text-gray-900">37</h3>
        <div class="mt-4">
          <div class="flex items-center justify-between text-xs">
            <span class="text-gray-500">Meta: 40</span>
            <span class="font-bold text-blue-600">92.5%</span>
          </div>
          <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 92.5%"></div>
          </div>
        </div>
      </button>

      <!-- Orders / Transactions -->
      <button @click="router.push({ name: 'reportes' })" class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group text-left cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform duration-300">
            <ShoppingCart :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs 520</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-red-100 text-red-700">
              -3.2%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Transacciones</p>
        <h3 class="text-3xl font-black text-gray-900">503</h3>
        <p class="text-xs text-gray-500 mt-4">
          <span class="text-orange-600 font-bold">12</span> pedidos en proceso
        </p>
      </button>

      <!-- Conversion Rate -->
      <button @click="router.push({ name: 'reportes' })" class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group text-left cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
            <Target :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs 58%</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-purple-100 text-purple-700">
              +5.2%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Tasa Conversión</p>
        <h3 class="text-3xl font-black text-gray-900">61%</h3>
        <p class="text-xs text-gray-500 mt-4">Muy por encima del promedio</p>
      </button>

      <!-- Products Sold -->
      <button @click="router.push({ name: 'productos' })" class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group text-left cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-500/20 group-hover:scale-110 transition-transform duration-300">
            <Package :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs 1,204</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-pink-100 text-pink-700">
              +8.3%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Productos Vendidos</p>
        <h3 class="text-3xl font-black text-gray-900">1,304</h3>
        <p class="text-xs text-gray-500 mt-4">Top: Pollo a la brasa (245)</p>
      </button>

      <!-- Average Ticket -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/20 group-hover:scale-110 transition-transform duration-300">
            <Receipt :size="20" class="text-white" />
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-400 font-semibold">vs S/ 22</p>
            <span class="text-xs font-black px-2 py-1 rounded-full bg-cyan-100 text-cyan-700">
              +12.7%
            </span>
          </div>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Ticket Promedio</p>
        <h3 class="text-3xl font-black text-gray-900">S/ 24.80</h3>
        <p class="text-xs text-gray-500 mt-4">+S/ 2.80 vs mes pasado</p>
      </div>

      <!-- Stock Alerts -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-300">
            <AlertTriangle :size="20" class="text-white" />
          </div>
          <span class="text-xs font-black px-2 py-1 rounded-full bg-amber-100 text-amber-700">
            Alerta
          </span>
        </div>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Stock Bajo</p>
        <h3 class="text-3xl font-black text-gray-900">8</h3>
        <p class="text-xs text-gray-500 mt-4">Requieren reabastecimiento</p>
      </div>

      <!-- Quick Action Button -->
      <button 
        @click="$router.push({ name: 'ventas' })"
        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all flex flex-col items-center justify-center text-center gap-3 group border-2 border-transparent hover:border-gray-600 hover:scale-105 active:scale-95"
      >
        <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center group-hover:scale-110 group-hover:rotate-90 transition-all duration-300">
          <Plus :size="24" class="text-white" />
        </div>
        <div>
          <h3 class="font-black text-lg">Nueva Venta</h3>
          <p class="text-xs text-gray-400">Ir al Punto de Venta</p>
        </div>
      </button>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Sales Chart -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-lg font-black text-gray-900">Ventas de la Semana</h3>
            <p class="text-xs text-gray-500 mt-1">Comparación con semana anterior</p>
          </div>
          <div class="flex items-center gap-2">
            <button class="px-3 py-1.5 text-xs font-bold bg-gray-100 rounded-lg hover:bg-gray-200">7D</button>
            <button class="px-3 py-1.5 text-xs font-bold bg-red-600 text-white rounded-lg">30D</button>
            <button class="px-3 py-1.5 text-xs font-bold bg-gray-100 rounded-lg hover:bg-gray-200">90D</button>
          </div>
        </div>
        <div class="h-64 flex items-end justify-between gap-2">
          <div v-for="(day, i) in weekData" :key="i" class="flex-1 flex flex-col items-center gap-2">
            <div class="w-full bg-gray-100 rounded-t-lg relative overflow-hidden group cursor-pointer hover:bg-gray-200 transition-colors" :style="{ height: `${day.percentage}%` }">
              <div class="absolute inset-0 bg-gradient-to-t from-red-600 to-red-500" :style="{ height: `${day.value / day.max * 100}%` }"></div>
              <div class="absolute inset-0 flex items-start justify-center pt-2">
                <span class="text-[10px] font-black text-white opacity-0 group-hover:opacity-100 transition-opacity">S/ {{ day.value }}</span>
              </div>
            </div>
            <span class="text-xs font-bold text-gray-600">{{ day.label }}</span>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-lg font-black text-gray-900">Productos Más Vendidos</h3>
            <p class="text-xs text-gray-500 mt-1">Top 5 del mes</p>
          </div>
          <button class="px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            Ver todo
          </button>
        </div>
        <div class="space-y-4">
          <div v-for="(product, i) in topProducts" :key="i" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
            <div class="w-10 h-10 bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl flex items-center justify-center text-white font-black shadow-md">
              {{ i + 1 }}
            </div>
            <div class="flex-1">
              <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ product.name }}</h4>
              <p class="text-xs text-gray-500">{{ product.sold }} vendidos</p>
            </div>
            <div class="text-right">
              <p class="text-sm font-black text-gray-900">S/ {{ product.revenue }}</p>
              <p class="text-xs text-emerald-600 font-bold">+{{ product.growth }}%</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-black text-gray-900">Últimas Ventas</h3>
          <p class="text-xs text-gray-500 mt-1">Transacciones recientes</p>
        </div>
        <button @click="$router.push({ name: 'ventas' })" class="px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors flex items-center gap-2">
          Ver todas
          <ArrowRight :size="14" />
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-100">
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">ID</th>
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">Cliente</th>
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">Productos</th>
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">Total</th>
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">Estado</th>
              <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wider pb-3">Hora</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="sale in recentSales" :key="sale.id" class="hover:bg-gray-50 transition-colors cursor-pointer">
              <td class="py-4 text-sm font-bold text-gray-900">#{{ sale.id }}</td>
              <td class="py-4 text-sm font-medium text-gray-700">{{ sale.customer }}</td>
              <td class="py-4 text-sm text-gray-600">{{ sale.items }} items</td>
              <td class="py-4 text-sm font-black text-gray-900">S/ {{ sale.total }}</td>
              <td class="py-4">
                <span :class="[
                  'text-xs font-bold px-2.5 py-1 rounded-full',
                  sale.status === 'Completado' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'
                ]">
                  {{ sale.status }}
                </span>
              </td>
              <td class="py-4 text-sm text-gray-500">{{ sale.time }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { authStore } from '@/store/auth'
import {
  TrendingUp,
  Users,
  ShoppingCart,
  Package,
  Target,
  Receipt,
  AlertTriangle,
  Plus,
  ArrowRight
} from 'lucide-vue-next'

const user = computed(() => authStore.state.user)
const currentBranch = computed(() => authStore.state.current_branch)

// Mock data for charts (would come from API)
const weekData = [
  { label: 'Lun', value: 850, max: 1500, percentage: 80 },
  { label: 'Mar', value: 920, max: 1500, percentage: 85 },
  { label: 'Mié', value: 1100, max: 1500, percentage: 95 },
  { label: 'Jue', value: 980, max: 1500, percentage: 88 },
  { label: 'Vie', value: 1240, max: 1500, percentage: 100 },
  { label: 'Sáb', value: 1350, max: 1500, percentage: 98 },
  { label: 'Dom', value: 890, max: 1500, percentage: 82 },
]

const topProducts = [
  { name: 'Pollo a la Brasa 1/4', sold: 245, revenue: '3,675', growth: 15 },
  { name: 'Arroz Chaufa', sold: 189, revenue: '2,646', growth: 8 },
  { name: 'Chicharrón Mixto', sold: 156, revenue: '2,340', growth: 22 },
  { name: 'Papa a la Huancaína', sold: 134, revenue: '1,072', growth: 12 },
  { name: 'Inca Kola 1.5L', sold: 98, revenue: '588', growth: 5 },
]

const recentSales = [
  { id: '10234', customer: 'Juan Pérez', items: 3, total: '45.50', status: 'Completado', time: '14:35' },
  { id: '10233', customer: 'María García', items: 5, total: '78.20', status: 'Completado', time: '14:28' },
  { id: '10232', customer: 'Carlos López', items: 2, total: '32.00', status: 'En preparación', time: '14:15' },
  { id: '10231', customer: 'Ana Torres', items: 4, total: '56.90', status: 'Completado', time: '14:08' },
  { id: '10230', customer: 'Roberto Silva', items: 1, total: '18.50', status: 'Completado', time: '13:52' },
]
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

* {
  font-family: 'Inter', sans-serif;
}
</style>
