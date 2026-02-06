<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg shadow-cyan-500/20">
            <BarChart3 :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Reportes y Análisis</h1>
            <p class="text-sm text-gray-500 font-semibold">Información del negocio • {{ currentDate }}</p>
          </div>
        </div>

        <div class="flex gap-3">
          <select 
            v-model="selectedPeriod"
            class="px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-cyan-500 focus:ring-0 font-bold text-gray-700"
          >
            <option value="today">Hoy</option>
            <option value="week">Esta Semana</option>
            <option value="month">Este Mes</option>
            <option value="year">Este Año</option>
            <option value="custom">Personalizado</option>
          </select>
          
          <button 
            @click="exportReport"
            class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-cyan-500 text-white rounded-xl font-bold shadow-lg shadow-cyan-600/30 hover:shadow-cyan-600/40 transition-all hover:scale-105 flex items-center gap-2"
          >
            <Download :size="20" />
            Exportar
          </button>
        </div>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1800px] mx-auto">
      <!-- Report Type Selector -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <button 
          v-for="type in reportTypes" 
          :key="type.id"
          @click="activeReport = type.id"
          :class="[
            'p-4 rounded-2xl border-2 transition-all text-left',
            activeReport === type.id 
              ? 'bg-cyan-50 border-cyan-500' 
              : 'bg-white border-gray-200 hover:border-gray-300'
          ]"
        >
          <component 
            :is="type.icon" 
            :size="24" 
            :class="activeReport === type.id ? 'text-cyan-600' : 'text-gray-400'"
            class="mb-2"
          />
          <h3 :class="[
            'font-black text-sm',
            activeReport === type.id ? 'text-cyan-900' : 'text-gray-900'
          ]">
            {{ type.label }}
          </h3>
          <p class="text-xs text-gray-500 mt-1">{{ type.description }}</p>
        </button>
      </div>

      <!-- Sales Report -->
      <div v-if="activeReport === 'sales'">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <DollarSign :size="24" class="text-emerald-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">+12%</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Ingresos Totales</p>
            <h3 class="text-3xl font-black text-gray-900">S/ 24,580.50</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                <ShoppingCart :size="24" class="text-blue-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700">+8%</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Ventas Realizadas</p>
            <h3 class="text-3xl font-black text-gray-900">458</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
                <TrendingUp :size="24" class="text-purple-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Ticket Promedio</p>
            <h3 class="text-3xl font-black text-gray-900">S/ 53.67</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                <Users :size="24" class="text-amber-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Clientes Únicos</p>
            <h3 class="text-3xl font-black text-gray-900">342</h3>
          </div>
        </div>

        <!-- Sales Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-black text-gray-900">Detalle de Ventas</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase">Fecha</th>
                  <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase">Ventas</th>
                  <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase">Ingresos</th>
                  <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase">Ticket Prom.</th>
                  <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase">Método</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="sale in mockSalesData" :key="sale.date" class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ sale.date }}</td>
                  <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ sale.count }}</td>
                  <td class="px-6 py-4 text-sm font-black text-emerald-600">S/ {{ sale.revenue.toFixed(2) }}</td>
                  <td class="px-6 py-4 text-sm font-semibold text-gray-700">S/ {{ sale.avgTicket.toFixed(2) }}</td>
                  <td class="px-6 py-4">
                    <div class="flex gap-2 text-xs">
                      <span class="font-bold text-green-700">{{ sale.cash }}% Efectivo</span>
                      <span class="font-bold text-blue-700">{{ sale.card }}% Tarjeta</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Products Report -->
      <div v-if="activeReport === 'products'">
        <!-- Top Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-black text-gray-900 mb-4">🏆 Productos Más Vendidos</h3>
            <div class="space-y-3">
              <div v-for="(product, index) in topProducts" :key="product.id" class="flex items-center gap-4">
                <div :class="[
                  'w-10 h-10 rounded-xl flex items-center justify-center font-black text-lg',
                  index === 0 ? 'bg-amber-500 text-white' :
                  index === 1 ? 'bg-gray-400 text-white' :
                  index === 2 ? 'bg-orange-600 text-white' :
                  'bg-gray-100 text-gray-600'
                ]">
                  {{ index + 1 }}
                </div>
                <div class="flex-1">
                  <h4 class="font-bold text-gray-900">{{ product.name }}</h4>
                  <p class="text-xs text-gray-500">{{ product.sold }} unidades vendidas</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-black text-gray-900">S/ {{ product.revenue.toFixed(2) }}</p>
                  <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                    <div class="bg-cyan-600 h-1.5 rounded-full" :style="`width: ${product.percentage}%`"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-black text-gray-900 mb-4">📊 Rendimiento por Categoría</h3>
            <div class="space-y-4">
              <div v-for="cat in categoryPerformance" :key="cat.name" class="space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-bold text-gray-900">{{ cat.name }}</span>
                  <span class="text-sm font-black text-emerald-600">S/ {{ cat.revenue.toFixed(2) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2 rounded-full transition-all"
                    :style="`width: ${cat.percentage}%`"
                  ></div>
                </div>
                <p class="text-xs text-gray-500">{{ cat.sold }} productos • {{ cat.percentage }}% del total</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Customers Report -->
      <div v-if="activeReport === 'customers'">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                <Users :size="24" class="text-indigo-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Clientes</p>
            <h3 class="text-3xl font-black text-gray-900">342</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <UserPlus :size="24" class="text-emerald-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">+24</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Nuevos Este Mes</p>
            <h3 class="text-3xl font-black text-gray-900">45</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
                <Star :size="24" class="text-purple-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Clientes VIP</p>
            <h3 class="text-3xl font-black text-gray-900">28</h3>
          </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
          <h3 class="text-lg font-black text-gray-900 mb-4">💎 Mejores Clientes</h3>
          <div class="space-y-3">
            <div v-for="customer in topCustomers" :key="customer.id" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
              <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-black">
                {{ customer.name.charAt(0) }}
              </div>
              <div class="flex-1">
                <h4 class="font-bold text-gray-900">{{ customer.name }}</h4>
                <p class="text-sm text-gray-600">{{ customer.orders }} compras • Última: {{ customer.lastVisit }}</p>
              </div>
              <div class="text-right">
                <p class="text-lg font-black text-emerald-600">S/ {{ customer.spent.toFixed(2) }}</p>
                <span :class="[
                  'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold mt-1',
                  customer.segment === 'vip' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                ]">
                  {{ customer.segment.toUpperCase() }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Report -->
      <div v-if="activeReport === 'inventory'">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                <Package :size="24" class="text-blue-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Productos Totales</p>
            <h3 class="text-3xl font-black text-gray-900">124</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <CheckCircle :size="24" class="text-emerald-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Stock OK</p>
            <h3 class="text-3xl font-black text-gray-900">98</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                <AlertTriangle :size="24" class="text-amber-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Stock Bajo</p>
            <h3 class="text-3xl font-black text-gray-900">18</h3>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center">
                <XCircle :size="24" class="text-red-600" />
              </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Agotados</p>
            <h3 class="text-3xl font-black text-gray-900">8</h3>
          </div>
        </div>

        <!-- Stock Alerts -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
          <h3 class="text-lg font-black text-gray-900 mb-4">⚠️ Alertas de Stock</h3>
          <div class="space-y-2">
            <div v-for="alert in stockAlerts" :key="alert.id" class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-xl">
              <div class="flex items-center gap-3">
                <AlertTriangle :size="20" class="text-amber-600" />
                <div>
                  <h4 class="font-bold text-gray-900">{{ alert.name }}</h4>
                  <p class="text-sm text-gray-600">Stock: {{ alert.stock }} • Mínimo: {{ alert.min }}</p>
                </div>
              </div>
              <button class="px-4 py-2 bg-amber-600 text-white rounded-lg font-bold text-sm hover:bg-amber-700 transition-colors">
                Reabastecer
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Financial Report -->
      <div v-if="activeReport === 'financial'">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-black text-gray-900 mb-4">💰 Resumen Financiero</h3>
            <div class="space-y-4">
              <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-600">Ingresos Totales</span>
                <span class="text-lg font-black text-emerald-600">S/ 24,580.50</span>
              </div>
              <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-600">Costos de Productos</span>
                <span class="text-lg font-black text-red-600">- S/ 12,340.00</span>
              </div>
              <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-600">Gastos Operativos</span>
                <span class="text-lg font-black text-red-600">- S/ 3,450.00</span>
              </div>
              <div class="flex justify-between items-center py-4 bg-emerald-50 rounded-xl px-4">
                <span class="text-lg font-black text-gray-900">Utilidad Neta</span>
                <span class="text-2xl font-black text-emerald-600">S/ 8,790.50</span>
              </div>
              <div class="text-center pt-2">
                <p class="text-sm text-gray-500">Margen de utilidad: <span class="font-black text-emerald-600">35.8%</span></p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-black text-gray-900 mb-4">📊 Desglose de Ingresos</h3>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-sm font-bold text-gray-700">Ventas en Efectivo</span>
                  <span class="text-sm font-black text-gray-900">S/ 15,234.00 (62%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 62%"></div>
                </div>
              </div>

              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-sm font-bold text-gray-700">Ventas con Tarjeta</span>
                  <span class="text-sm font-black text-gray-900">S/ 8,456.50 (34%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-blue-500 h-2 rounded-full" style="width: 34%"></div>
                </div>
              </div>

              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-sm font-bold text-gray-700">Yape/Transferencias</span>
                  <span class="text-sm font-black text-gray-900">S/ 890.00 (4%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-purple-500 h-2 rounded-full" style="width: 4%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import {
  BarChart3,
  Download,
  DollarSign,
  ShoppingCart,
  TrendingUp,
  Users,
  Package,
  UserPlus,
  Star,
  CheckCircle,
  AlertTriangle,
  XCircle,
  Wallet
} from 'lucide-vue-next'

const currentDate = new Date().toLocaleDateString('es-PE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

// State
const selectedPeriod = ref('month')
const activeReport = ref('sales')

// Report Types
const reportTypes = [
  { id: 'sales', label: 'Ventas', description: 'Análisis de ventas', icon: ShoppingCart },
  { id: 'products', label: 'Productos', description: 'Rendimiento', icon: Package },
  { id: 'customers', label: 'Clientes', description: 'Base de datos', icon: Users },
  { id: 'inventory', label: 'Inventario', description: 'Estado de stock', icon: Package },
  { id: 'financial', label: 'Financiero', description: 'Resumen contable', icon: Wallet }
]

// Mock Data
const mockSalesData = [
  { date: '31 Ene 2026', count: 58, revenue: 3240.50, avgTicket: 55.87, cash: 65, card: 35 },
  { date: '30 Ene 2026', count: 62, revenue: 3456.00, avgTicket: 55.74, cash: 60, card: 40 },
  { date: '29 Ene 2026', count: 54, revenue: 2890.75, avgTicket: 53.53, cash: 68, card: 32 },
  { date: '28 Ene 2026', count: 48, revenue: 2567.00, avgTicket: 53.48, cash: 70, card: 30 },
  { date: '27 Ene 2026', count: 51, revenue: 2734.25, avgTicket: 53.61, cash: 62, card: 38 },
]

const topProducts = [
  { id: 1, name: 'Pollo a la Brasa 1/2', sold: 234, revenue: 6552.00, percentage: 100 },
  { id: 2, name: 'Pollo a la Brasa 1/4', sold: 189, revenue: 2835.00, percentage: 85 },
  { id: 3, name: 'Pollo Entero', sold: 145, revenue: 7540.00, percentage: 70 },
  { id: 4, name: 'Papas Fritas Grande', sold: 312, revenue: 2496.00, percentage: 55 },
  { id: 5, name: 'Arroz Chaufa', sold: 98, revenue: 1372.00, percentage: 40 },
]

const categoryPerformance = [
  { name: 'Pollo', revenue: 16927.00, sold: 568, percentage: 68 },
  { name: 'Guarniciones', revenue: 4368.00, sold: 410, percentage: 18 },
  { name: 'Bebidas', revenue: 2340.50, sold: 390, percentage: 10 },
  { name: 'Extras', revenue: 945.00, sold: 236, percentage: 4 },
]

const topCustomers = [
  { id: 1, name: 'Ana Torres Vega', orders: 52, spent: 2890.75, lastVisit: 'Hoy', segment: 'vip' },
  { id: 2, name: 'Juan Pérez García', orders: 45, spent: 2340.50, lastVisit: 'Hace 2 días', segment: 'vip' },
  { id: 3, name: 'María López Silva', orders: 28, spent: 1456.00, lastVisit: 'Hace 1 día', segment: 'frecuente' },
  { id: 4, name: 'Roberto Silva', orders: 22, spent: 1124.50, lastVisit: 'Hace 3 días', segment: 'frecuente' },
]

const stockAlerts = [
  { id: 1, name: 'Inca Kola 1.5L', stock: 8, min: 30 },
  { id: 2, name: 'Coca Cola 1.5L', stock: 5, min: 30 },
  { id: 3, name: 'Chicha Morada 1L', stock: 4, min: 10 },
]

// Methods
const exportReport = () => {
  alert('Exportando reporte a Excel...')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

* {
  font-family: 'Inter', sans-serif;
}
</style>
